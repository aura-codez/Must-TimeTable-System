<?php
require_once '../config/database.php';

class TimetableController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // CREATE TIMETABLE ENTRY
    public function createTimetable($day, $time_slot, $subject, $teacher, $room, $department) {
        $stmt = $this->conn->prepare("INSERT INTO timetables (day, time_slot, subject, teacher, room, department) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$day, $time_slot, $subject, $teacher, $room, $department]);
    }

    // GET TIMETABLES FOR A DEPARTMENT
    public function getTimetablesByDepartment($department) {
        $stmt = $this->conn->prepare("SELECT * FROM timetables WHERE department = ?");
        $stmt->execute([$department]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // DELETE TIMETABLE
    public function deleteTimetable($timetable_id) {
        $stmt = $this->conn->prepare("DELETE FROM timetables WHERE id = ?");
        return $stmt->execute([$timetable_id]);
    }

    // UPDATE TIMETABLE (For Manual Adjustments)
    public function updateTimetable($id, $day, $time_slot, $subject, $teacher, $room) {
        $stmt = $this->conn->prepare("UPDATE timetables SET day = ?, time_slot = ?, subject = ?, teacher = ?, room = ? WHERE id = ?");
        return $stmt->execute([$day, $time_slot, $subject, $teacher, $room, $id]);
    }
}
?>
