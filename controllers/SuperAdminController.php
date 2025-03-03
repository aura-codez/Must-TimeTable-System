<?php
require_once '../config.php';
require_once '../config/database.php';

class SuperAdminController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // GET ALL USERS (excluding superadmins)
    public function getUsers() {
        $stmt = $this->conn->prepare("SELECT u.id, u.name, u.email, u.role, u.is_active, d.name AS department_name 
                                      FROM users u 
                                      LEFT JOIN departments d ON u.department_id = d.id 
                                      WHERE u.role != 'superadmin'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ADD USER
    public function addUser($name, $email, $password, $role, $department_name) {
        try {
            $department_id = null;
            if (!empty($department_name)) {
                $stmt = $this->conn->prepare("SELECT id FROM departments WHERE name = ?");
                $stmt->execute([$department_name]);
                $dept = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($dept) {
                    $department_id = $dept['id'];
                } else {
                    $stmt = $this->conn->prepare("INSERT INTO departments (name) VALUES (?)");
                    $stmt->execute([$department_name]);
                    $department_id = $this->conn->lastInsertId();
                }
            }

            $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role, department_id, is_active) 
                                          VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->execute([$name, $email, md5($password), $role, $department_id]);
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // TOGGLE USER STATUS (enable/disable)
    public function toggleUserStatus($user_id, $is_active) {
        try {
            $new_status = $is_active == 1 ? 0 : 1;
            $stmt = $this->conn->prepare("UPDATE users SET is_active = ? WHERE id = ? AND role != 'superadmin'");
            $stmt->execute([$new_status, $user_id]);
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>