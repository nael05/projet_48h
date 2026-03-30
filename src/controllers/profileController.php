<?php
class profileController extends Controller {
    public function index() {
        $userModel = $this->model('User');
        $user = $userModel->findById($_SESSION['user_id']);
        $this->view('profile', ['user' => $user]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $userModel->updateProfile($_SESSION['user_id'], $_POST['username'], $_POST['bio']);
            header('Location: /profile');
        }
    }
}