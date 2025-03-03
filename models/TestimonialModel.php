<?php
include '../config/database.php';

class TestimonialModel {
    public static function addTestimonial($name, $feedback) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO testimonials (name, feedback) VALUES (?, ?)");
        return $stmt->execute([$name, $feedback]);
    }

    public static function getAllTestimonials() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 5");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
