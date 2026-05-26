<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Funcionario;
use Core\Session;

class FuncionarioController extends BaseController
{
    private Funcionario $funcionarioModel;

    public function __construct()
    {
        $this->funcionarioModel = new Funcionario();
    }

    public function index(): void
    {
        $funcionarios = $this->funcionarioModel->findAll();

        $this->render('funcionarios/index', [
            'funcionarios' => $funcionarios,
            'success'      => Session::getFlash('success'),
            'error'        => Session::getFlash('error'),
        ]);
    }

    public function create(): void
    {
        $this->render('funcionarios/create');
    }

    public function store(): void
    {
        $errors = $this->validate($_POST, [
            'nome'  => 'required|min:2|max:150',
            'cargo' => 'required|min:2|max:100',
        ]);

        if (!empty($errors)) {
            $this->render('funcionarios/create', [
                'errors' => $errors,
                'old'    => $_POST,
            ]);
            return;
        }

        $this->funcionarioModel->create(
            nome:     htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            cargo:    htmlspecialchars(trim($_POST['cargo']), ENT_QUOTES),
            telefone: htmlspecialchars(trim($_POST['telefone'] ?? ''), ENT_QUOTES),
        );

        Session::flash('success', 'Funcionário cadastrado com sucesso!');
        $this->redirect('/funcionarios');
    }

    public function edit(string $id): void
    {
        $funcionario = $this->funcionarioModel->findById((int) $id);

        if (!$funcionario) {
            http_response_code(404);
            $this->render('funcionarios/index', [
                'funcionarios' => [],
                'error'        => 'Funcionário não encontrado.',
            ]);
            return;
        }

        $this->render('funcionarios/edit', ['funcionario' => $funcionario]);
    }

    public function update(string $id): void
    {
        $errors = $this->validate($_POST, [
            'nome'  => 'required|min:2|max:150',
            'cargo' => 'required|min:2|max:100',
        ]);

        if (!empty($errors)) {
            $funcionario = $this->funcionarioModel->findById((int) $id);
            $this->render('funcionarios/edit', [
                'errors'      => $errors,
                'old'         => $_POST,
                'funcionario' => $funcionario,
            ]);
            return;
        }

        $this->funcionarioModel->update(
            id:       (int) $id,
            nome:     htmlspecialchars(trim($_POST['nome']), ENT_QUOTES),
            cargo:    htmlspecialchars(trim($_POST['cargo']), ENT_QUOTES),
            telefone: htmlspecialchars(trim($_POST['telefone'] ?? ''), ENT_QUOTES),
        );

        Session::flash('success', 'Funcionário atualizado com sucesso!');
        $this->redirect('/funcionarios');
    }

    public function delete(string $id): void
    {
        $this->funcionarioModel->delete((int) $id);
        Session::flash('success', 'Funcionário removido.');
        $this->redirect('/funcionarios');
    }
}
