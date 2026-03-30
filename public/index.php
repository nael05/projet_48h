<?php

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
$router->add('/logout', 'AuthController', 'logout');

$router->add('/feed', 'FeedController', 'index');
$router->add('/feed.php', 'FeedController', 'index');
$router->add('/posts', 'PostController', 'create');
$router->add('/posts/like', 'PostController', 'toggleLike');
$router->add('/posts/comment', 'PostController', 'addComment');
$router->add('/posts/comment/delete', 'PostController', 'deleteComment');
$router->add('/posts/delete', 'PostController', 'delete');
$router->add('/profile', 'ProfileController', 'show');
$router->add('/profile/update', 'ProfileController', 'update');
$router->add('/user', 'ProfileController', 'showUser');
$router->add('/messages', 'MessageController', 'index');
$router->add('/messages/send', 'MessageController', 'send');
$router->add('/messages/share', 'MessageController', 'sharePost');
$router->add('/messages/poll', 'MessageController', 'poll');

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

$requestPath = parse_url($requestUri, PHP_URL_PATH) ?? '/';
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');

if ($basePath !== '' && $basePath !== '/' && strpos($requestPath, $basePath) === 0) {
    $requestPath = substr($requestPath, strlen($basePath));
}

if ($requestPath === '' || $requestPath === false) {
    $requestPath = '/';
}

$router->dispatch($requestPath);