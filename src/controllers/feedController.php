<?php
namespace App\Controllers;

class FeedController {
    public function index() {
        if (empty($_SESSION['is_authenticated']) && empty($_SESSION['user_id'])) {
            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
            $basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;

            header('Location: ' . $basePath . '/login');
            exit;
        }

        $activeTab = 'feed';
        $activeNav = 'feed';
        $mockUrl = 'localhost:3000/feed';
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/feed.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}