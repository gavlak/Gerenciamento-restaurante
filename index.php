<?php
declare(strict_types=1);

define('ROOT_PATH', __DIR__);

// Detect the subfolder prefix (e.g. "/PhpProjs") so redirects work correctly
// when the app is served from a subdirectory like localhost/PhpProjs/
$scriptDir = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
define('BASE_URL', $scriptDir !== '' ? '/' . $scriptDir : '');

// Autoloader — maps namespaced classes to file paths automatically
spl_autoload_register(function (string $class): void {
    $file = ROOT_PATH . DIRECTORY_SEPARATOR
          . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

require_once ROOT_PATH . '/core/Session.php';
require_once ROOT_PATH . '/core/Router.php';

Session::start();

// ---------------------------------------------------------------------------
// Route definitions
// ---------------------------------------------------------------------------
$router = new Router();

// Public routes
$router->get('login',  'AuthController', 'loginForm');
$router->post('login', 'AuthController', 'loginSubmit');
$router->get('logout', 'AuthController', 'logout');

// Produtos (estoque)
$router->get('',                       'ProdutoController',      'index',  protected: true);
$router->get('produtos',              'ProdutoController',      'index',  protected: true);
$router->get('produtos/create',       'ProdutoController',      'create', protected: true);
$router->post('produtos/store',       'ProdutoController',      'store',  protected: true);
$router->get('produtos/edit/{id}',    'ProdutoController',      'edit',   protected: true);
$router->post('produtos/update/{id}', 'ProdutoController',      'update', protected: true);
$router->post('produtos/delete/{id}', 'ProdutoController',      'delete', protected: true);
$router->get('produtos/{id}',         'ProdutoController',      'show',   protected: true);

// Funcionários
$router->get('funcionarios',              'FuncionarioController', 'index',  protected: true);
$router->get('funcionarios/create',       'FuncionarioController', 'create', protected: true);
$router->post('funcionarios/store',       'FuncionarioController', 'store',  protected: true);
$router->get('funcionarios/edit/{id}',    'FuncionarioController', 'edit',   protected: true);
$router->post('funcionarios/update/{id}', 'FuncionarioController', 'update', protected: true);
$router->post('funcionarios/delete/{id}', 'FuncionarioController', 'delete', protected: true);

// Scanner QR Code (NFC-e)
$router->get('notas/scanner',   'NotaFiscalController', 'scanner',  protected: true);
$router->post('notas/importar', 'NotaFiscalController', 'importar', protected: true);

$router->dispatch();
