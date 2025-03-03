<?php
require_once '../config.php';
require_once '../config/database.php';

class AuthController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // LOGIN FUNCTION
    public function login($email, $password, $role) {
        $stmt = $this->conn->prepare("SELECT u.id, u.role, u.department_id, d.name AS department_name 
                                      FROM users u 
                                      LEFT JOIN departments d ON u.department_id = d.id 
                                      WHERE u.email = ? AND u.role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && md5($password) == $user['password']) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Set department name
            if (!empty($user['department_name'])) {
                $_SESSION['department'] = $user['department_name'];
            } else {
                $_SESSION['department'] = "General";
            }

            $dashboard = [
                'superadmin' => '../views/superadmin/dashboard.php',
                'admin' => '../views/admin/dashboard.php',
                'teacher' => '../views/teacher/dashboard.php',
                'student' => '../views/student/dashboard.php'
            ];

            header("Location: " . $dashboard[$user['role']]);
            exit();
        } else {
            return "Invalid Email, Password, or Role.";
        }
    }
}
?>