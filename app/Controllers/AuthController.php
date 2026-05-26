<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Session;

class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginForm(): void
    {
        if (Session::has('user_id')) {
            $this->redirect('/produtos');
        }

        $this->render('auth/login', [
            'error' => Session::getFlash('error'),
        ]);
    }

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

        session_regenerate_id(true);

        Session::set('user_id',       $user['id']);
        Session::set('user_name',     $user['name']);
        Session::set('last_activity', time());

        $this->redirect('/produtos');
    }

    // GET /logout
    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }
}
