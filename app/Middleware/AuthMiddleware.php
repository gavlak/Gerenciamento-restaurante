<?php
declare(strict_types=1);

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Session::has('user_id')) {
            Session::flash('error', 'Você precisa estar logado para acessar esta página.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}
