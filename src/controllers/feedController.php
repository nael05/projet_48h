<?php
namespace App\Controllers;

class FeedController {
    public function index() {
        $activeTab = 'feed';
        $activeNav = 'feed';
        $mockUrl = 'localhost:3000/feed';
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/feed.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
