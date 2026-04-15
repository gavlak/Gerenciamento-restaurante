<?php
declare(strict_types=1);

namespace app\Controllers;

use app\Models\Produto;

class NotaFiscalController extends BaseController
{
    private Produto $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new Produto();
    }

    /**
     * Exibe a tela do scanner QR Code (webcam).
     */
    public function scanner(): void
    {
        $this->render('notas/scanner', [
            'error' => \Session::getFlash('error'),
        ]);
    }

    /**
     * Recebe a URL da NFC-e, faz scraping e importa os produtos.
     */
    public function importar(): void
    {
        $url = trim($_POST['url'] ?? '');
        $chave = trim($_POST['chave_acesso'] ?? '');

        // Se recebeu chave de acesso em vez de URL, montar a URL de consulta
        if ($url === '' && $chave !== '') {
            $chave = preg_replace('/\D/', '', $chave); // só dígitos
            if (strlen($chave) !== 44) {
                \Session::flash('error', 'A chave de acesso deve ter exatamente 44 dígitos.');
                $this->redirect('/notas/scanner');
                return;
            }
            $url = 'https://www.fazenda.pr.gov.br/nfce/qrcode?p=' . $chave;
        }

        if ($url === '') {
            \Session::flash('error', 'Nenhuma URL ou chave de acesso informada.');
            $this->redirect('/notas/scanner');
            return;
        }

        // Buscar HTML da página da NFC-e
        $html = $this->fetchNfce($url);

        if ($html === false) {
            \Session::flash('error', 'Não foi possível acessar a página da nota fiscal. Verifique sua conexão.');
            $this->redirect('/notas/scanner');
            return;
        }

        // Extrair produtos do HTML
        $produtos = $this->parseNfce($html);

        if (empty($produtos)) {
            \Session::flash('error', 'Nenhum produto encontrado na nota fiscal. Verifique se o QR Code é de uma NFC-e válida.');
            $this->redirect('/notas/scanner');
            return;
        }

        // Salvar produtos no banco
        $count = 0;
        $dataCompra = date('Y-m-d');

        foreach ($produtos as $p) {
            $this->produtoModel->create(
                nome:              $p['nome'],
                quantidade:        $p['quantidade'],
                quantidade_minima: 1,
                valor:             $p['valor'],
                data_compra:       $dataCompra,
            );
            $count++;
        }

        \Session::flash('success', "{$count} produto(s) importado(s) com sucesso!");
        $this->redirect('/produtos');
    }

    /**
     * Busca o HTML da página da NFC-e via cURL.
     */
    private function fetchNfce(string $url): string|false
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            return false;
        }

        return $response;
    }

    /**
     * Faz parsing do HTML da NFC-e e extrai os produtos.
     * Compatível com a estrutura da SEFAZ-PR.
     */
    private function parseNfce(string $html): array
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $produtos = [];

        // Buscar linhas de produtos na tabela da NFC-e
        // A estrutura da SEFAZ-PR geralmente usa tabelas com classes específicas
        // Tentamos múltiplos seletores para maior compatibilidade
        $rows = $xpath->query('//tr[contains(@class, "txtTit2")]');

        if ($rows === false || $rows->length === 0) {
            // Fallback: buscar por padrão de tabela genérico de NFC-e
            $rows = $xpath->query('//table//tr');
        }

        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');

            if ($cells->length < 2) {
                continue;
            }

            // Tentar extrair nome, quantidade e valor das células
            $nome = '';
            $quantidade = 0.0;
            $valor = 0.0;

            // Buscar spans com classes específicas da NFC-e (SEFAZ-PR)
            $spans = $row->getElementsByTagName('span');

            if ($spans->length > 0) {
                foreach ($spans as $span) {
                    $class = $span->getAttribute('class');
                    $text  = trim($span->nodeValue ?? '');

                    if (str_contains($class, 'txtTit') && $nome === '') {
                        $nome = $text;
                    }
                }
            }

            // Se não encontrou via spans, tenta pelas células da tabela
            if ($nome === '') {
                for ($i = 0; $i < $cells->length; $i++) {
                    $text = trim($cells->item($i)->nodeValue ?? '');

                    if ($text === '' || is_numeric(str_replace([',', '.'], '', $text))) {
                        continue;
                    }

                    // Primeiro texto não numérico é provavelmente o nome
                    if ($nome === '') {
                        $nome = $text;
                    }
                }
            }

            if ($nome === '') {
                continue;
            }

            // Buscar quantidade e valor no texto completo da linha
            $rowText = $row->nodeValue ?? '';

            // Padrão: "Qtde.:2,0000" ou "Qtde: 2"
            if (preg_match('/Qtde\.?:?\s*([\d.,]+)/i', $rowText, $m)) {
                $quantidade = (float) str_replace(',', '.', $m[1]);
            }

            // Padrão: "Vl. Unit.:5,99" ou "Val. Unit: 5.99"
            if (preg_match('/Vl\.?\s*Unit\.?:?\s*([\d.,]+)/i', $rowText, $m)) {
                $valor = (float) str_replace(',', '.', $m[1]);
            }

            // Se não achou valor unitário, tentar valor total
            if ($valor === 0.0 && preg_match('/Vl\.?\s*Total\.?:?\s*([\d.,]+)/i', $rowText, $m)) {
                $totalItem = (float) str_replace(',', '.', $m[1]);
                $valor = $quantidade > 0 ? $totalItem / $quantidade : $totalItem;
            }

            // Limpar e sanitizar nome
            $nome = htmlspecialchars(mb_substr(trim($nome), 0, 200), ENT_QUOTES);

            if ($quantidade <= 0) {
                $quantidade = 1;
            }

            $produtos[] = [
                'nome'       => $nome,
                'quantidade' => $quantidade,
                'valor'      => round($valor, 2),
            ];
        }

        return $produtos;
    }
}
