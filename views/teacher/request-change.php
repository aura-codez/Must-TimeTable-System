<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/TeacherController.php';
include '../../components/teacher-header.php';

$teacherController = new TeacherController();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "<div class='alert alert-danger text-center'>Error: User not logged in.</div>";
        exit;
    }

    $teacher_id = $_SESSION['user_id'];
    $reason = trim($_POST['reason']);
    $requested_time = trim($_POST['requested_time']);

    if (empty($reason) || empty($requested_time)) {
        echo "<div class='alert alert-danger text-center'>All fields are required!</div>";
    } else {
        if ($teacherController->requestTimetableChange($teacher_id, $reason, $requested_time)) {
            echo "<div class='alert alert-success text-center'>Request Submitted Successfully!</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error submitting request.</div>";
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center text-warning">Request Timetable Change</h2>
    <div class="card bg-dark p-4 shadow-lg rounded">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-warning">Reason for Change:</label>
                <textarea name="reason" class="form-control bg-dark text-light border-warning" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label text-warning">Preferred Time Slot:</label>
                <input type="text" name="requested_time" class="form-control bg-dark text-light border-warning" placeholder="e.g. 10:30 - 12:00" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Submit Request</button>
        </form>
    </div>
</div>

<?php include '../../components/teacher-footer.php'; ?>
