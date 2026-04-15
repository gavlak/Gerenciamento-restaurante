<?php
declare(strict_types=1);

namespace app\Controllers;

use app\Models\Produto;

class ProdutoController extends BaseController
{
    private Produto $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new Produto();
    }

    public function index(): void
    {
        $produtos = $this->produtoModel->findAll();

        $this->render('produtos/index', [
            'produtos' => $produtos,
            'success'  => \Session::getFlash('success'),
            'error'    => \Session::getFlash('error'),
        ]);
    }

    public function create(): void
    {
        $this->render('produtos/create');
    }

    public function store(): void
    {
        $errors = $this->validate($_POST, [
            'nome'              => 'required|min:2|max:200',
            'quantidade'        => 'required|numeric',
            'quantidade_minima' => 'required|numeric',
            'valor'             => 'required|numeric',
            'data_compra'       => 'required',
        ]);

        if (!empty($errors)) {
            $this->render('produtos/create', [
                'errors' => $errors,
                'old'    => $_POST,
            ]);
            return;
        }

        $this->produtoModel->create(
            nome:              htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            quantidade:        (float) $_POST['quantidade'],
            quantidade_minima: (float) $_POST['quantidade_minima'],
            valor:             (float) $_POST['valor'],
            data_compra:       $_POST['data_compra'],
        );

        \Session::flash('success', 'Produto cadastrado com sucesso!');
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

        $this->render('produtos/edit', ['produto' => $produto]);
    }

    public function update(string $id): void
    {
        $errors = $this->validate($_POST, [
            'nome'              => 'required|min:2|max:200',
            'quantidade'        => 'required|numeric',
            'quantidade_minima' => 'required|numeric',
            'valor'             => 'required|numeric',
            'data_compra'       => 'required',
        ]);

        if (!empty($errors)) {
            $produto = $this->produtoModel->findById((int) $id);
            $this->render('produtos/edit', [
                'errors'  => $errors,
                'old'     => $_POST,
                'produto' => $produto,
            ]);
            return;
        }

        $this->produtoModel->update(
            id:                (int) $id,
            nome:              htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            quantidade:        (float) $_POST['quantidade'],
            quantidade_minima: (float) $_POST['quantidade_minima'],
            valor:             (float) $_POST['valor'],
            data_compra:       $_POST['data_compra'],
        );

        \Session::flash('success', 'Produto atualizado com sucesso!');
        $this->redirect('/produtos');
    }

    public function delete(string $id): void
    {
        $this->produtoModel->delete((int) $id);
        \Session::flash('success', 'Produto removido.');
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
