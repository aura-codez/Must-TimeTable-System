<?php
require_once '../../config/database.php';

class AdminController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Get Pending Requests
    public function getPendingRequests($department_id) {
        $stmt = $this->conn->prepare("SELECT tr.id, tr.teacher_id, tr.reason, tr.requested_time, u.name AS teacher_name 
                                      FROM timetable_requests tr 
                                      JOIN users u ON tr.teacher_id = u.id 
                                      WHERE u.department_id = ?");
        $stmt->execute([$department_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Move Timetable Subject
    public function moveTimetableSubject($timetable_id, $new_day, $new_time_slot, $department_id) {
        try {
            // Check for conflicts
            $stmt = $this->conn->prepare("SELECT teacher_id, room_id FROM timetables WHERE id = ?");
            $stmt->execute([$timetable_id]);
            $entry = $stmt->fetch(PDO::FETCH_ASSOC);
            $teacher_id = $entry['teacher_id'];
            $room_id = $entry['room_id'];

            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM timetables 
                                          WHERE department_id = ? AND day = ? AND time_slot = ? 
                                          AND (teacher_id = ? OR room_id = ?) AND id != ?");
            $stmt->execute([$department_id, $new_day, $new_time_slot, $teacher_id, $room_id, $timetable_id]);
            if ($stmt->fetchColumn() > 0) {
                return "Conflict: Teacher or room already assigned at this time.";
            }

            $stmt = $this->conn->prepare("UPDATE timetables SET day = ?, time_slot = ? WHERE id = ?");
            $stmt->execute([$new_day, $new_time_slot, $timetable_id]);
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>