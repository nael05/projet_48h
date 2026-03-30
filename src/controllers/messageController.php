<?php
namespace App\Controllers;

class MessageController {
    public function index() {
        if (empty($_SESSION['is_authenticated']) && empty($_SESSION['user_id'])) {
            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
            $basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;

            header('Location: ' . $basePath . '/login');
            exit;
        }

        $activeTab = 'messages';
        $activeNav = 'messages';
        $mockUrl = 'localhost:3000/messages';
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/messages.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}