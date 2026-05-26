<?php
declare(strict_types=1);

namespace App\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use Core\Session;

class AuthMiddleware implements IMiddleware
{
    private const IDLE_TIMEOUT_SECONDS = 30 * 60;

    public function handle(Request $request): void
    {
        if (!Session::has('user_id')) {
            Session::flash('error', 'Voce precisa estar logado para acessar esta pagina.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $lastActivity = (int) Session::get('last_activity', time());
        if (time() - $lastActivity > self::IDLE_TIMEOUT_SECONDS) {
            Session::destroy();
            Session::start();
            Session::flash('error', 'Sua sessao expirou por inatividade. Faca login novamente.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        Session::set('last_activity', time());

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
