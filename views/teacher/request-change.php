<?php include '../../components/teacher-header.php'; ?>
<?php include '../../config/database.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacherId = $_SESSION['user_id'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO timetable_requests (teacher_id, reason, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$teacherId, $reason]);

    echo "<div class='alert alert-success text-center'>Timetable change request submitted!</div>";
}
?>

<div class="container mt-5">
    <h2 class="text-center">Request Timetable Change</h2>
    <form method="POST">
        <label>Reason for Change:</label>
        <textarea name="reason" class="form-control" required></textarea>
        <br>
        <button type="submit" class="btn btn-warning">Submit Request</button>
    </form>
</div>

<?php include '../../components/teacher-footer.php'; ?>
