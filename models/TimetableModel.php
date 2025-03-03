<?php
require_once '../../config/database.php';

class TimetableModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getConfirmedDepartments() {
        $stmt = $this->conn->prepare("SELECT DISTINCT d.id, d.name 
                                      FROM departments d 
                                      JOIN timetables t ON d.id = t.department_id 
                                      WHERE t.is_confirmed = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConfirmedTimetableByDepartment($department_id) {
        $stmt = $this->conn->prepare("SELECT t.*, s.subject_name, s.duration, u.name AS teacher_name, r.room_name 
                                      FROM timetables t 
                                      LEFT JOIN subjects s ON t.subject_id = s.id 
                                      LEFT JOIN users u ON t.teacher_id = u.id 
                                      LEFT JOIN rooms r ON t.room_id = r.id 
                                      WHERE t.department_id = ? AND t.is_confirmed = 1 
                                      ORDER BY t.session, t.section, t.day, t.time_slot");
        $stmt->execute([$department_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>