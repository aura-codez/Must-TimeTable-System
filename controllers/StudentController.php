<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

class StudentController {
    private $conn;
    private $userModel;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->userModel = new UserModel();
    }

    // Kept for potential future use or profile page
    public function getStudentDepartmentId($user_id) {
        $user = $this->userModel->getUserById($user_id);
        if (isset($user['department_id'])) {
            return $user['department_id'];
        } else {
            return null;
        }
    }

    // Kept for potential future use or profile page
    public function getDepartmentName($department_id) {
        if (!$department_id) {
            return "Unknown";
        }

        $stmt = $this->conn->prepare("SELECT name FROM departments WHERE id = ?");
        $stmt->execute([$department_id]);
        $department = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($department && isset($department['name'])) {
            return $department['name'];
        } else {
            return "Unknown";
        }
    }
}
?>