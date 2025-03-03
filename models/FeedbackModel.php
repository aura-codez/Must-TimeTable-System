<?php
require_once '../config.php';
require_once '../config/database.php';

class FeedbackModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // SUBMIT FEEDBACK
    public function submitFeedback($name, $feedback, $profile_pic) {
        $stmt = $this->conn->prepare("INSERT INTO feedback (name, feedback, profile_pic) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $feedback, $profile_pic]);
    }

    // GET LATEST FEEDBACK
    public function getLatestFeedback($limit = 5) {
        $stmt = $this->conn->prepare("SELECT * FROM feedback ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
