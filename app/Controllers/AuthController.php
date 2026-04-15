<?php
declare(strict_types=1);

namespace app\Controllers;

use app\Models\User;

class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // GET /login
    public function loginForm(): void
    {
        if (\Session::has('user_id')) {
            $this->redirect('/produtos');
        }

        $this->render('auth/login', [
            'error' => \Session::getFlash('error'),
        ]);
    }

    // POST /login
    public function loginSubmit(): void
    {
        $errors = $this->validate($_POST, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!empty($errors)) {
            $this->render('auth/login', [
                'errors' => $errors,
                'old'    => ['email' => $_POST['email'] ?? ''],
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($_POST['email']);

        if (!$user || !password_verify($_POST['password'], $user['password_hash'])) {
            $this->render('auth/login', [
                'errors' => ['general' => 'E-mail ou senha inválidos.'],
                'old'    => ['email' => $_POST['email'] ?? ''],
            ]);
            return;
        }

        \Session::set('user_id',   $user['id']);
        \Session::set('user_name', $user['name']);

        $this->redirect('/produtos');
    }

    // GET /logout
    public function logout(): void
    {
        \Session::destroy();
        $this->redirect('/login');
    }
}
