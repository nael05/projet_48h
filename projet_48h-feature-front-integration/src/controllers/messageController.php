<?php
namespace App\Controllers;

class MessageController {
    public function index() {
        $activeTab = 'messages';
        $activeNav = 'messages';
        $mockUrl = 'localhost:3000/messages';
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/messages.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
