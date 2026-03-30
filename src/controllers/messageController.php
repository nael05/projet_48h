<?php
class messageController extends Controller {
    public function index($friendId) {
        $messageModel = $this->model('Message');
        $myId = $_SESSION['user_id']; 
        $messages = $messageModel->getConversation($myId, $friendId);
        
        $this->view('messages', ['messages' => $messages, 'friendId' => $friendId]);
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = $this->model('Message');
            $messageModel->save($_SESSION['user_id'], $_POST['receiver_id'], $_POST['content']);
            header('Location: ' . $_SERVER['HTTP_REFERER']); // Recharge la page
        }
    }
}