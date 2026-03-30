<?php
// public/index.php
// Point d'entrée de l'application (Architecture MVC front-controller)

// Support pour le serveur web interne de PHP `php -S localhost:3000 -t public/`
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

session_start();

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

$router = new Router();
$router->add('/login', 'AuthController', 'login');
$router->add('/register', 'AuthController', 'register');

// Ajout des routes de la maquette YnovNet
$router->add('/feed', 'FeedController', 'index');
$router->add('/profile', 'ProfileController', 'show');
$router->add('/messages', 'MessageController', 'index');

// Dispatch final basé sur l'URL
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$router->dispatch($requestUri);
