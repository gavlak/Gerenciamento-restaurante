<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Produto;
use Core\Session;

class ProdutoController extends BaseController
{
    private Produto $produtoModel;

    /** Unidades de medida válidas aceitas no cadastro. */
    private const UNIDADES_VALIDAS = ['UN', 'KG', 'G', 'L', 'ML', 'CX', 'PCT'];

    public function __construct()
    {
        $this->produtoModel = new Produto();
    }

    public function index(): void
    {
        $produtos = $this->produtoModel->findAll();

        $this->render('produtos/index', [
            'produtos' => $produtos,
            'success'  => Session::getFlash('success'),
            'error'    => Session::getFlash('error'),
        ]);
    }

    public function create(): void
    {
        $this->render('produtos/create', [
            'unidades' => self::UNIDADES_VALIDAS,
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate($_POST, [
            'nome'              => 'required|min:2|max:200',
            'quantidade'        => 'required|numeric',
            'quantidade_minima' => 'required|numeric',
            'unidade'           => 'required|max:10',
            'valor'             => 'required|numeric',
            'data_compra'       => 'required',
        ]);

        $unidade = strtoupper(trim((string) ($_POST['unidade'] ?? '')));
        if (empty($errors['unidade']) && !in_array($unidade, self::UNIDADES_VALIDAS, true)) {
            $errors['unidade'] = 'Unidade inválida.';
        }

        if (!empty($errors)) {
            $this->render('produtos/create', [
                'errors'   => $errors,
                'old'      => $_POST,
                'unidades' => self::UNIDADES_VALIDAS,
            ]);
            return;
        }

        $this->produtoModel->create(
            nome:              htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            quantidade:        (float) $_POST['quantidade'],
            quantidade_minima: (float) $_POST['quantidade_minima'],
            unidade:           $unidade,
            valor:             (float) $_POST['valor'],
            data_compra:       $_POST['data_compra'],
        );

        Session::flash('success', 'Produto cadastrado com sucesso!');
        $this->redirect('/produtos');
    }

    public function edit(string $id): void
    {
        $produto = $this->produtoModel->findById((int) $id);

        if (!$produto) {
            http_response_code(404);
            $this->render('produtos/index', [
                'produtos' => [],
                'error'    => 'Produto não encontrado.',
            ]);
            return;
        }

        $this->render('produtos/edit', [
            'produto'  => $produto,
            'unidades' => self::UNIDADES_VALIDAS,
        ]);
    }

    public function update(string $id): void
    {
        $errors = $this->validate($_POST, [
            'nome'              => 'required|min:2|max:200',
            'quantidade'        => 'required|numeric',
            'quantidade_minima' => 'required|numeric',
            'unidade'           => 'required|max:10',
            'valor'             => 'required|numeric',
            'data_compra'       => 'required',
        ]);

        $unidade = strtoupper(trim((string) ($_POST['unidade'] ?? '')));
        if (empty($errors['unidade']) && !in_array($unidade, self::UNIDADES_VALIDAS, true)) {
            $errors['unidade'] = 'Unidade inválida.';
        }

        if (!empty($errors)) {
            $produto = $this->produtoModel->findById((int) $id);
            $this->render('produtos/edit', [
                'errors'   => $errors,
                'old'      => $_POST,
                'produto'  => $produto,
                'unidades' => self::UNIDADES_VALIDAS,
            ]);
            return;
        }

        $this->produtoModel->update(
            id:                (int) $id,
            nome:              htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            quantidade:        (float) $_POST['quantidade'],
            quantidade_minima: (float) $_POST['quantidade_minima'],
            unidade:           $unidade,
            valor:             (float) $_POST['valor'],
            data_compra:       $_POST['data_compra'],
        );

        Session::flash('success', 'Produto atualizado com sucesso!');
        $this->redirect('/produtos');
    }

    public function delete(string $id): void
    {
        $this->produtoModel->delete((int) $id);
        Session::flash('success', 'Produto removido.');
        $this->redirect('/produtos');
    }

    public function show(string $id): void
    {
        $produto = $this->produtoModel->findById((int) $id);

        if (!$produto) {
            http_response_code(404);
            $this->render('produtos/index', [
                'produtos' => [],
                'error'    => 'Produto não encontrado.',
            ]);
            return;
        }

        $this->render('produtos/show', ['produto' => $produto]);
    }
}
