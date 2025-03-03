<?php
require_once '../../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Get User Profile
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT u.*, d.name AS department_name 
                                      FROM users u 
                                      LEFT JOIN departments d ON u.department_id = d.id 
                                      WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Profile
    public function updateProfile($id, $name, $email, $contact, $password) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ?, contact = ?, password = ? WHERE id = ?");
            return $stmt->execute([$name, $email, $contact, $password, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>