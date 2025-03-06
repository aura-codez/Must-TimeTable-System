<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /MUST-Timetable-System/views/guest/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT d.id FROM departments d WHERE d.admin_id = ?");
$stmt->execute([$admin_id]);
$admin_dept = $stmt->fetch(PDO::FETCH_ASSOC);
$admin_department_id = $admin_dept ? $admin_dept['id'] : null;

if (!$admin_department_id) {
    die("<div class='alert alert-danger text-center'>⚠️ Error: Your department is not assigned.</div>");
}

// Fetch Subject to Edit
$subject_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM subjects WHERE id = ? AND department_id = ?");
$stmt->execute([$subject_id, $admin_department_id]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subject) {
    die("<div class='alert alert-danger text-center'>⚠️ Subject not found or not authorized.</div>");
}

// Fetch Teachers
$stmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'teacher' AND department_id = ?");
$stmt->execute([$admin_department_id]);
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Edit Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_subject'])) {
    $year = $_POST['year'];
    $section = $_POST['section'];
    $subject_name = $_POST['subject_name'];
    $teacher_id = $_POST['teacher_id'];
    $duration = $_POST['duration'];
    $days_per_week = $_POST['days_per_week'];
    $class_type = $_POST['class_type'];
    $same_time = isset($_POST['same_time']) ? "yes" : "no";
    $preferred_days = isset($_POST['preferred_days']) ? implode(", ", $_POST['preferred_days']) : "Not Specified";

    $stmt = $conn->prepare("UPDATE subjects SET 
        year = ?, section = ?, subject_name = ?, teacher_id = ?, duration = ?, days_per_week = ?, class_type = ?, same_time = ?, preferred_days = ? 
        WHERE id = ? AND department_id = ?");
    $stmt->execute([$year, $section, $subject_name, $teacher_id, $duration, $days_per_week, $class_type, $same_time, $preferred_days, $subject_id, $admin_department_id]);

    echo "<script>alert('✅ Subject Updated Successfully!'); window.location.href='add-subjects.php';</script>";
}
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include "../../components/sidebar-admin.php"; ?>
        </div>
        <div class="col-md-9">
            <div class="container mt-4">
                <div class="card bg-dark text-light p-4 shadow-lg rounded">
                    <h2 class="text-warning text-center">✏️ Edit Subject</h2>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label text-light">📅 Year:</label>
                                <select name="year" class="form-control bg-dark text-light border-warning" required>
                                    <option value="2025" <?php if ($subject['year'] == '2025') echo 'selected'; ?>>2025</option>
                                    <option value="2024" <?php if ($subject['year'] == '2024') echo 'selected'; ?>>2024</option>
                                    <option value="2023" <?php if ($subject['year'] == '2023') echo 'selected'; ?>>2023</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-light">🏫 Section:</label>
                                <select name="section" class="form-control bg-dark text-light border-warning" required>
                                    <option value="A" <?php if ($subject['section'] == 'A') echo 'selected'; ?>>A</option>
                                    <option value="B" <?php if ($subject['section'] == 'B') echo 'selected'; ?>>B</option>
                                    <option value="Both" <?php if ($subject['section'] == 'Both') echo 'selected'; ?>>Both</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-light">📖 Subject Name:</label>
                                <input type="text" name="subject_name" value="<?= htmlspecialchars($subject['subject_name']) ?>" class="form-control bg-dark text-light border-warning" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-light">👨‍🏫 Teacher:</label>
                                <select name="teacher_id" class="form-control bg-dark text-light border-warning" required>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <option value="<?= $teacher['id'] ?>" <?php if ($subject['teacher_id'] == $teacher['id']) echo 'selected'; ?>><?= $teacher['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label text-light">⏳ Duration:</label>
                                <select name="duration" class="form-control bg-dark text-light border-warning" required>
                                    <option value="1.5" <?php if ($subject['duration'] == '1.5') echo 'selected'; ?>>1.5 Hours</option>
                                    <option value="2" <?php if ($subject['duration'] == '2') echo 'selected'; ?>>2 Hours</option>
                                    <option value="3" <?php if ($subject['duration'] == '3') echo 'selected'; ?>>3 Hours</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light">📅 Days/Week:</label>
                                <select name="days_per_week" class="form-control bg-dark text-light border-warning" required>
                                    <option value="1" <?php if ($subject['days_per_week'] == '1') echo 'selected'; ?>>1 Day</option>
                                    <option value="2" <?php if ($subject['days_per_week'] == '2') echo 'selected'; ?>>2 Days</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light">🏛️ Type:</label>
                                <select name="class_type" class="form-control bg-dark text-light border-warning" required>
                                    <option value="Theory" <?php if ($subject['class_type'] == 'Theory') echo 'selected'; ?>>Theory</option>
                                    <option value="Lab" <?php if ($subject['class_type'] == 'Lab') echo 'selected'; ?>>Lab</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label text-light">✅ Same Time?</label>
                            <input type="checkbox" name="same_time" value="yes" <?php if ($subject['same_time'] == 'yes') echo 'checked'; ?>>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label text-light">✅ Preferred Days:</label><br>
                            <?php $pref_days = explode(", ", $subject['preferred_days']); ?>
                            <input type="checkbox" name="preferred_days[]" value="Monday" <?php if (in_array('Monday', $pref_days)) echo 'checked'; ?>> Monday
                            <input type="checkbox" name="preferred_days[]" value="Tuesday" <?php if (in_array('Tuesday', $pref_days)) echo 'checked'; ?>> Tuesday
                            <input type="checkbox" name="preferred_days[]" value="Wednesday" <?php if (in_array('Wednesday', $pref_days)) echo 'checked'; ?>> Wednesday
                            <input type="checkbox" name="preferred_days[]" value="Thursday" <?php if (in_array('Thursday', $pref_days)) echo 'checked'; ?>> Thursday
                            <input type="checkbox" name="preferred_days[]" value="Friday" <?php if (in_array('Friday', $pref_days)) echo 'checked'; ?>> Friday
                        </div>
                        <button type="submit" name="edit_subject" class="btn btn-warning mt-3 w-100">💾 Update Subject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../components/admin-footer.php"; ?>