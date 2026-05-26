<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Cardapio;
use App\Models\Produto;
use Core\Session;

class CardapioController extends BaseController
{
    private Cardapio $cardapioModel;
    private Produto  $produtoModel;

    /** Dias da semana aceitos. */
    private const DIAS_VALIDOS = [
        'Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta', 'Sabado', 'Domingo',
    ];

    public function __construct()
    {
        $this->cardapioModel = new Cardapio();
        $this->produtoModel  = new Produto();
    }

    public function index(): void
    {
        $cardapios = $this->cardapioModel->findAllComInsumos();

        $this->render('cardapios/index', [
            'cardapios' => $cardapios,
            'success'   => Session::getFlash('success'),
            'error'     => Session::getFlash('error'),
        ]);
    }

    public function create(): void
    {
        $produtos = $this->produtoModel->findAllOrdenado();

        $this->render('cardapios/create', [
            'produtos' => $produtos,
            'dias'     => self::DIAS_VALIDOS,
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate($_POST, [
            'nome'     => 'required|min:2|max:150',
            'dia'      => 'required|max:20',
            'detalhes' => 'required|min:2|max:2000',
        ]);

        $dia = trim((string) ($_POST['dia'] ?? ''));
        if (empty($errors['dia']) && !in_array($dia, self::DIAS_VALIDOS, true)) {
            $errors['dia'] = 'Dia inválido.';
        }

        $insumoIds = array_map('intval', (array) ($_POST['insumos'] ?? []));

        if (!empty($errors)) {
            $this->render('cardapios/create', [
                'errors'   => $errors,
                'old'      => $_POST,
                'produtos' => $this->produtoModel->findAllOrdenado(),
                'dias'     => self::DIAS_VALIDOS,
                'selecionados' => $insumoIds,
            ]);
            return;
        }

        $this->cardapioModel->create(
            nome:      htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            dia:       $dia,
            detalhes:  htmlspecialchars(trim($_POST['detalhes']), ENT_QUOTES),
            insumoIds: $insumoIds,
        );

        Session::flash('success', 'Cardapio cadastrado com sucesso!');
        $this->redirect('/cardapios');
    }

    public function edit(string $id): void
    {
        $cardapio = $this->cardapioModel->findById((int) $id);

        if (!$cardapio) {
            http_response_code(404);
            Session::flash('error', 'Cardapio não encontrado.');
            $this->redirect('/cardapios');
            return;
        }

        $selecionados = $this->cardapioModel->getInsumoIds((int) $id);

        $this->render('cardapios/edit', [
            'cardapio'     => $cardapio,
            'produtos'     => $this->produtoModel->findAllOrdenado(),
            'dias'         => self::DIAS_VALIDOS,
            'selecionados' => $selecionados,
        ]);
    }

    public function update(string $id): void
    {
        $errors = $this->validate($_POST, [
            'nome'     => 'required|min:2|max:150',
            'dia'      => 'required|max:20',
            'detalhes' => 'required|min:2|max:2000',
        ]);

        $dia = trim((string) ($_POST['dia'] ?? ''));
        if (empty($errors['dia']) && !in_array($dia, self::DIAS_VALIDOS, true)) {
            $errors['dia'] = 'Dia inválido.';
        }

        $insumoIds = array_map('intval', (array) ($_POST['insumos'] ?? []));

        if (!empty($errors)) {
            $cardapio = $this->cardapioModel->findById((int) $id);
            $this->render('cardapios/edit', [
                'errors'       => $errors,
                'old'          => $_POST,
                'cardapio'     => $cardapio,
                'produtos'     => $this->produtoModel->findAllOrdenado(),
                'dias'         => self::DIAS_VALIDOS,
                'selecionados' => $insumoIds,
            ]);
            return;
        }

        $this->cardapioModel->update(
            id:        (int) $id,
            nome:      htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            dia:       $dia,
            detalhes:  htmlspecialchars(trim($_POST['detalhes']), ENT_QUOTES),
            insumoIds: $insumoIds,
        );

        Session::flash('success', 'Cardapio atualizado com sucesso!');
        $this->redirect('/cardapios');
    }

    public function delete(string $id): void
    {
        $this->cardapioModel->delete((int) $id);
        Session::flash('success', 'Cardapio removido.');
        $this->redirect('/cardapios');
    }
}
