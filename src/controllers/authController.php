<?php
namespace App\Controllers;

class AuthController {

    public function login() {
        $activeTab = 'login';
        $activeNav = 'login';
        $mockUrl   = 'localhost:3000/login';

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function register() {
        $activeTab = 'login';
        $activeNav = 'login';
        $mockUrl   = 'localhost:3000/register';

        require_once __DIR__ . '/../Views/auth/register.php';
    }
}
