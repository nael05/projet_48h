<?php
namespace App\Controllers;

class ProfileController {
    public function show() {
        $activeTab = 'profile';
        $activeNav = 'profile';
        $mockUrl = 'localhost:3000/profile/1';
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/profile.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
