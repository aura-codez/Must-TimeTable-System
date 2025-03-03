<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $reason = $_POST['reason'];
    $requested_time = $_POST['requested_time'];

    $stmt = $conn->prepare("INSERT INTO requests (requested_by, reason, requested_time, status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $reason, $requested_time]);

    echo "<script>alert('Request submitted successfully!'); window.location.href='../views/student/dashboard.php';</script>";
}
?>
