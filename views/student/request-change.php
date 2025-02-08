<?php include '../../components/student-header.php'; ?>
<?php include '../../config/database.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_SESSION['user_id'];
    $courseId = $_POST['course_id'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO timetable_requests (student_id, course_id, reason, status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$studentId, $courseId, $reason]);

    echo "<div class='alert alert-success text-center'>Timetable change request submitted!</div>";
}
?>

<div class="container mt-5">
    <h2 class="text-center">Request Timetable Change</h2>
    <form method="POST">
        <label>Select Course:</label>
        <select name="course_id" class="form-control" required>
            <?php
            $stmt = $conn->query("SELECT * FROM courses");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
            }
            ?>
        </select>
        <br>
        <label>Reason for Change:</label>
        <textarea name="reason" class="form-control" required></textarea>
        <br>
        <button type="submit" class="btn btn-warning">Submit Request</button>
    </form>
</div>

<?php include '../../components/student-footer.php'; ?>
