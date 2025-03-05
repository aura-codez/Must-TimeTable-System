<?php

require_once '../../config/database.php';



class SuperAdminController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // GET ALL ADMINS (only admins, not all users)
    public function getUsers() {
        $stmt = $this->conn->prepare("SELECT u.id, u.name, u.email, u.contact, u.role, u.is_active, d.name AS department_name 
                                      FROM users u 
                                      LEFT JOIN departments d ON u.department_id = d.id 
                                      WHERE u.role = 'admin'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ADD ADMIN (only admins can be added)
    public function addUser($name, $email, $password, $role, $department_name) {
        try {
            if ($role !== 'admin') {
                return "Error: Superadmin can only add admins.";
            }

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

            $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role, department_id, contact, is_active) 
                                          VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([$name, $email, md5($password), $role, $department_id, '']); // Default empty contact
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // TOGGLE ADMIN STATUS (enable/disable admins only)
    public function toggleUserStatus($user_id, $is_active) {
        try {
            $new_status = $is_active == 1 ? 0 : 1;
            $stmt = $this->conn->prepare("UPDATE users SET is_active = ? WHERE id = ? AND role = 'admin'");
            $stmt->execute([$new_status, $user_id]);
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // UPDATE ADMIN
    public function updateAdmin($user_id, $name, $email, $contact, $department_name) {
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

            $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ?, contact = ?, department_id = ? 
                                          WHERE id = ? AND role = 'admin'");
            $stmt->execute([$name, $email, $contact, $department_id, $user_id]);
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // GET ALL DEPARTMENTS
    public function getAllDepartments() {
        $stmt = $this->conn->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // DELETE DEPARTMENT (Modified to set linked users' department_id to NULL)
    public function deleteDepartment($department_id) {
        try {
            // Set department_id to NULL for all users linked to this department
            $stmt = $this->conn->prepare("UPDATE users SET department_id = NULL WHERE department_id = ?");
            $stmt->execute([$department_id]);

            // Delete the department
            $stmt = $this->conn->prepare("DELETE FROM departments WHERE id = ?");
            $stmt->execute([$department_id]);
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // UPDATE DEPARTMENT (New method for update-department.php)
    public function updateDepartment($department_id, $name) {
        try {
            $stmt = $this->conn->prepare("UPDATE departments SET name = ? WHERE id = ?");
            $stmt->execute([$name, $department_id]);
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>
