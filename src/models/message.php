<?php
class Message extends Model {
    // Récupérer l'historique entre deux utilisateurs
    public function getConversation($myId, $friendId) {
        $sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) 
                OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC";
        return $this->query($sql, [$myId, $friendId, $friendId, $myId])->fetchAll();
    }

    // Enregistrer un nouveau message
    public function save($sender, $receiver, $content) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, NOW())";
        return $this->query($sql, [$sender, $receiver, $content]);
    }
}