<?php
require_once 'DB.php'; 

class UserModel {
    private $pdo;

    public function __construct() {
        global $_DB;
        $this->pdo = $_DB->getPDO(); 
    }

    public function getAllUsers() {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addUser($username, $email, $accessLevel) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, access_level, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $accessLevel]);
    }

    public function updateUser($id, $username, $email, $access_level) {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, access_level = ? WHERE user_id = ?");
        return $stmt->execute([$username, $email, $access_level, $id]);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleAccess($id, $newLevel) {
        $stmt = $this->pdo->prepare("UPDATE users SET access_level = ? WHERE user_id = ?");
        return $stmt->execute([$newLevel, $id]);
    }
}
