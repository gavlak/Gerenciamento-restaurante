<?php
declare(strict_types=1);

define('ROOT_PATH', __DIR__);

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$scriptDir = trim($scriptDir, '/');
define('BASE_URL', $scriptDir !== '' ? '/' . $scriptDir : '');

require __DIR__ . '/vendor/autoload.php';

use Pecee\SimpleRouter\SimpleRouter;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use App\Middleware\AuthMiddleware;
use Core\Session;

Session::start();

SimpleRouter::group(['prefix' => BASE_URL], function () {

    SimpleRouter::get('/login',  'App\Controllers\AuthController@loginForm');
    SimpleRouter::post('/login', 'App\Controllers\AuthController@loginSubmit');
    SimpleRouter::get('/logout', 'App\Controllers\AuthController@logout');

    SimpleRouter::group(['middleware' => AuthMiddleware::class], function () {

        SimpleRouter::get('/',                       'App\Controllers\ProdutoController@index');
        SimpleRouter::get('/produtos',               'App\Controllers\ProdutoController@index');
        SimpleRouter::get('/produtos/create',        'App\Controllers\ProdutoController@create');
        SimpleRouter::post('/produtos/store',        'App\Controllers\ProdutoController@store');
        SimpleRouter::get('/produtos/edit/{id}',     'App\Controllers\ProdutoController@edit');
        SimpleRouter::post('/produtos/update/{id}',  'App\Controllers\ProdutoController@update');
        SimpleRouter::post('/produtos/delete/{id}',  'App\Controllers\ProdutoController@delete');
        SimpleRouter::get('/produtos/{id}',          'App\Controllers\ProdutoController@show')
            ->where(['id' => '[0-9]+']);

        SimpleRouter::get('/funcionarios',              'App\Controllers\FuncionarioController@index');
        SimpleRouter::get('/funcionarios/create',       'App\Controllers\FuncionarioController@create');
        SimpleRouter::post('/funcionarios/store',       'App\Controllers\FuncionarioController@store');
        SimpleRouter::get('/funcionarios/edit/{id}',    'App\Controllers\FuncionarioController@edit');
        SimpleRouter::post('/funcionarios/update/{id}', 'App\Controllers\FuncionarioController@update');
        SimpleRouter::post('/funcionarios/delete/{id}', 'App\Controllers\FuncionarioController@delete');

        SimpleRouter::get('/cardapios',              'App\Controllers\CardapioController@index');
        SimpleRouter::get('/cardapios/create',       'App\Controllers\CardapioController@create');
        SimpleRouter::post('/cardapios/store',       'App\Controllers\CardapioController@store');
        SimpleRouter::get('/cardapios/edit/{id}',    'App\Controllers\CardapioController@edit');
        SimpleRouter::post('/cardapios/update/{id}', 'App\Controllers\CardapioController@update');
        SimpleRouter::post('/cardapios/delete/{id}', 'App\Controllers\CardapioController@delete');

        SimpleRouter::get('/notas/scanner',   'App\Controllers\NotaFiscalController@scanner');
        SimpleRouter::post('/notas/importar', 'App\Controllers\NotaFiscalController@importar');
    });
});

SimpleRouter::error(function ($request, \Exception $exception) {
    if ($exception instanceof NotFoundHttpException) {
        http_response_code(404);
        require ROOT_PATH . '/views/layout/header.php';
        echo '<h2>404 — Pagina nao encontrada</h2><p><a href="' . BASE_URL . '/produtos">Voltar</a></p>';
        require ROOT_PATH . '/views/layout/footer.php';
        exit;
    }
});

SimpleRouter::start();
