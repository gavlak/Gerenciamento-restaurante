<?php
declare(strict_types=1);

namespace app\Controllers;

use app\Models\Item;

class ItemController extends BaseController
{
    private Item $itemModel;

    public function __construct()
    {
        $this->itemModel = new Item();
    }

    // GET /items
    public function index(): void
    {
        $items = $this->itemModel->findAll();
        $this->render('items/index', [
            'items'   => $items,
            'success' => \Session::getFlash('success'),
        ]);
    }

    // GET /items/create
    public function create(): void
    {
        $this->render('items/create');
    }

    // POST /items/store
    public function store(): void
    {
        $errors = $this->validate($_POST, [
            'name'        => 'required|min:2|max:100',
            'description' => 'required|min:5|max:500',
            'price'       => 'required|numeric',
        ]);

        if (!empty($errors)) {
            $this->render('items/create', [
                'errors' => $errors,
                'old'    => $_POST,
            ]);
            return;
        }

        $this->itemModel->create(
            name:        htmlspecialchars(trim($_POST['name']), ENT_QUOTES),
            description: htmlspecialchars(trim($_POST['description']), ENT_QUOTES),
            price:       (float) $_POST['price'],
        );

        \Session::flash('success', 'Item criado com sucesso!');
        $this->redirect('/items');
    }

    // GET /items/edit/{id}
    public function edit(string $id): void
    {
        $item = $this->itemModel->findById((int) $id);

        if (!$item) {
            http_response_code(404);
            $this->render('items/index', ['items' => [], 'error' => 'Item não encontrado.']);
            return;
        }

        $this->render('items/edit', ['item' => $item]);
    }

    // POST /items/update/{id}
    public function update(string $id): void
    {
        $errors = $this->validate($_POST, [
            'name'        => 'required|min:2|max:100',
            'description' => 'required|min:5|max:500',
            'price'       => 'required|numeric',
        ]);

        if (!empty($errors)) {
            $item = $this->itemModel->findById((int) $id);
            $this->render('items/edit', [
                'errors' => $errors,
                'old'    => $_POST,
                'item'   => $item,
            ]);
            return;
        }

        $this->itemModel->update(
            id:          (int) $id,
            name:        htmlspecialchars(trim($_POST['name']), ENT_QUOTES),
            description: htmlspecialchars(trim($_POST['description']), ENT_QUOTES),
            price:       (float) $_POST['price'],
        );

        \Session::flash('success', 'Item atualizado com sucesso!');
        $this->redirect('/items');
    }

    // POST /items/delete/{id}
    public function delete(string $id): void
    {
        $this->itemModel->delete((int) $id);
        \Session::flash('success', 'Item removido.');
        $this->redirect('/items');
    }

    // GET /items/{id}
    public function show(string $id): void
    {
        $item = $this->itemModel->findById((int) $id);

        if (!$item) {
            http_response_code(404);
            $this->render('items/index', ['items' => [], 'error' => 'Item não encontrado.']);
            return;
        }

        $this->render('items/show', ['item' => $item]);
    }
}
