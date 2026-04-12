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

// Protected routes — checked by AuthMiddleware before the controller runs
$router->get('',                   'ItemController', 'index',  protected: true);
$router->get('items',              'ItemController', 'index',  protected: true);
$router->get('items/create',       'ItemController', 'create', protected: true);
$router->post('items/store',       'ItemController', 'store',  protected: true);
$router->get('items/edit/{id}',    'ItemController', 'edit',   protected: true);
$router->post('items/update/{id}', 'ItemController', 'update', protected: true);
$router->post('items/delete/{id}', 'ItemController', 'delete', protected: true);
$router->get('items/{id}',         'ItemController', 'show',   protected: true);

$router->dispatch();
