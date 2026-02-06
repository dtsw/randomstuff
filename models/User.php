<?php
class User {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Register a new user
    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }

    // Get user by ID
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
}
?>