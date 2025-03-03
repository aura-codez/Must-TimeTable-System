<?php
require_once '../../config/database.php';

class TeacherController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Request Timetable Change
    public function requestTimetableChange($teacher_id, $reason, $requested_time) {
        $stmt = $this->conn->prepare("INSERT INTO timetable_requests (teacher_id, reason, requested_time) VALUES (?, ?, ?)");
        return $stmt->execute([$teacher_id, $reason, $requested_time]);
    }
}
?>