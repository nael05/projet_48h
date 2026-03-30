<?php
class User extends Model {
    public function findById($id) {
        return $this->query("SELECT * FROM users WHERE id = ?", [$id])->fetch();
    }

    public function updateProfile($id, $username, $bio) {
        $sql = "UPDATE users SET username = ?, bio = ? WHERE id = ?";
        return $this->query($sql, [$username, $bio, $id]);
    }
}