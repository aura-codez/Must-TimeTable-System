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
                                      WHERE u.id = ? AND u.role = 'admin'");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Profile
    public function updateProfile($id, $name, $email, $contact, $password) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ?, contact = ?, password = ? 
                                          WHERE id = ? AND role = 'admin'");
            return $stmt->execute([$name, $email, $contact, $password, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get All Admins (modified to only fetch admins)
    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT u.*, d.name AS department_name 
                                      FROM users u 
                                      LEFT JOIN departments d ON u.department_id = d.id 
                                      WHERE u.role = 'admin'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
