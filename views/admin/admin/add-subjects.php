<?php
session_start();
require_once '../../config/database.php';

// Ensure Admin is Logged In
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /MUST-Timetable-System/views/guest/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Fetch Admin's Department ID from departments table
$stmt = $conn->prepare("SELECT d.id, d.name FROM departments d WHERE d.admin_id = ?");
$stmt->execute([$admin_id]);
$admin_dept = $stmt->fetch(PDO::FETCH_ASSOC);
if ($admin_dept) {
    $admin_department_id = $admin_dept['id'];
    $admin_department_name = $admin_dept['name'];
} else {
    $admin_department_id = null;
    $admin_department_name = "Unknown";
}

if (!$admin_department_id) {
    die("<div class='alert alert-danger text-center'>⚠️ Error: Your department is not assigned. Contact superadmin.</div>");
}

// Fetch Registered Teachers for this department
$stmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'teacher' AND department_id = ?");
$stmt->execute([$admin_department_id]);
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Rooms for this department
$stmt = $conn->prepare("SELECT id, room_name FROM rooms WHERE department_id = ?");
$stmt->execute([$admin_department_id]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Form Submission (Add Subject)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_subject'])) {
    $session = $_POST['session'];
    $section = $_POST['section'];
    $subject_name = $_POST['subject_name'];
    $teacher_id = $_POST['teacher_id'];
    $room_id = $_POST['room_id'];
    $duration = isset($_POST['duration']) ? $_POST['duration'] : "1.5";
    $days_per_week = isset($_POST['days_per_week']) ? $_POST['days_per_week'] : 1;
    $class_type = isset($_POST['class_type']) ? $_POST['class_type'] : "Theory";
    $same_time = isset($_POST['same_time']) ? "yes" : "no";
    $preferred_days = isset($_POST['preferred_days']) ? implode(", ", $_POST['preferred_days']) : "Not Specified";

    // Insert Subjects for Single or Both Sections
    if ($section === "Both") {
        $stmt = $conn->prepare("INSERT INTO subjects 
            (department_id, session, section, subject_name, teacher_id, room_id, duration, days_per_week, class_type, preferred_days, same_time) 
            VALUES 
            (?, ?, 'A', ?, ?, ?, ?, ?, ?, ?, ?), 
            (?, ?, 'B', ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $admin_department_id, $session, $subject_name, $teacher_id, $room_id, $duration, $days_per_week, $class_type, $preferred_days, $same_time,
            $admin_department_id, $session, $subject_name, $teacher_id, $room_id, $duration, $days_per_week, $class_type, $preferred_days, $same_time
        ]);
    } else {
        $stmt = $conn->prepare("INSERT INTO subjects 
            (department_id, session, section, subject_name, teacher_id, room_id, duration, days_per_week, class_type, preferred_days, same_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$admin_department_id, $session, $section, $subject_name, $teacher_id, $room_id, $duration, $days_per_week, $class_type, $preferred_days, $same_time]);
    }
    echo "<div class='alert alert-success text-center'>✅ Subject Added Successfully!</div>";
}

// Handle Subject Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_subject'])) {
    $subject_id = $_POST['subject_id'];
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ? AND department_id = ?");
    $stmt->execute([$subject_id, $admin_department_id]);
    echo "<div class='alert alert-success text-center'>🗑️ Subject Deleted Successfully!</div>";
}

// Fetch Existing Subjects with Room Info
$stmt = $conn->prepare("SELECT s.*, u.name AS teacher_name, r.room_name 
                        FROM subjects s 
                        JOIN users u ON s.teacher_id = u.id 
                        LEFT JOIN rooms r ON s.room_id = r.id 
                        WHERE s.department_id = ? 
                        ORDER BY s.id DESC");
$stmt->execute([$admin_department_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <?php include "../../components/sidebar-admin.php"; ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="container mt-4">
                <div class="card bg-dark text-light p-4 shadow-lg rounded">
                    <h2 class="text-warning text-center">📖 Add Subjects & Assign Teachers (<?= htmlspecialchars($admin_department_name) ?>)</h2>

                    <div class="card bg-dark text-light p-4 shadow-lg rounded">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label text-light">📆 Select Session:</label>
                                    <select name="session" class="form-control bg-dark text-light border-warning" required>
                                        <option value="21-25">21-25</option>
                                        <option value="22-26">22-26</option>
                                        <option value="23-27">23-27</option>
                                        <option value="24-28">24-28</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-light">🏫 Section:</label>
                                    <select name="section" class="form-control bg-dark text-light border-warning" required>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-light">📖 Subject Name:</label>
                                    <input type="text" name="subject_name" class="form-control bg-dark text-light border-warning" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-light">👨‍🏫 Assign Teacher:</label>
                                    <select name="teacher_id" class="form-control bg-dark text-light border-warning" required>
                                        <option value="">-- Select Teacher --</option>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>"><?= $teacher['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="form-label text-light">🏫 Assign Room:</label>
                                    <select name="room_id" class="form-control bg-dark text-light border-warning" required>
                                        <option value="">-- Select Room --</option>
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?= $room['id'] ?>"><?= $room['room_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-light">⏳ Duration (Hours):</label>
                                    <select name="duration" class="form-control bg-dark text-light border-warning" required>
                                        <option value="1.5">1.5 Hours</option>
                                        <option value="2">2 Hours</option>
                                        <option value="3">3 Hours</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-light">📅 Days Per Week:</label>
                                    <select name="days_per_week" class="form-control bg-dark text-light border-warning" required>
                                        <option value="1">1 Day</option>
                                        <option value="2">2 Days</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-light">🏛️ Class Type:</label>
                                    <select name="class_type" class="form-control bg-dark text-light border-warning" required>
                                        <option value="Theory">Theory</option>
                                        <option value="Lab">Lab</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label text-light">✅ Same Time for Both Sections?</label>
                                <input type="checkbox" name="same_time" value="yes">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-light">✅ Preferred Days:</label><br>
                                <input type="checkbox" name="preferred_days[]" value="Monday"> Monday
                                <input type="checkbox" name="preferred_days[]" value="Tuesday"> Tuesday
                                <input type="checkbox" name="preferred_days[]" value="Wednesday"> Wednesday
                                <input type="checkbox" name="preferred_days[]" value="Thursday"> Thursday
                                <input type="checkbox" name="preferred_days[]" value="Friday"> Friday
                            </div>
                            <button type="submit" name="add_subject" class="btn btn-warning mt-3 w-100">➕ Add Subject</button>
                        </form>
                    </div>
                </div>

                <!-- Subjects Table -->
                <div class="card bg-dark text-light mt-5 p-4 shadow-lg rounded">
                    <h3 class="text-warning text-center">📋 Added Subjects</h3>
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>📆 Session</th>
                                <th>🏫 Section</th>
                                <th>📖 Subject</th>
                                <th>👨‍🏫 Teacher</th>
                                <th>🏫 Room</th>
                                <th>⏳ Duration</th>
                                <th>📅 Days/Week</th>
                                <th>🏛️ Type</th>
                                <th>✅ Same Time</th>
                                <th>📅 Preferred Days</th>
                                <th>🛠️ Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $subject): ?>
                                <tr>
                                    <td><?= htmlspecialchars($subject['session']) ?></td>
                                    <td><?= htmlspecialchars($subject['section']) ?></td>
                                    <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($subject['teacher_name']) ?></td>
                                    <td>
                                        <?php 
                                        if (isset($subject['room_name'])) {
                                            echo htmlspecialchars($subject['room_name']);
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($subject['duration']) ?> hrs</td>
                                    <td><?= htmlspecialchars($subject['days_per_week']) ?></td>
                                    <td><?= htmlspecialchars($subject['class_type']) ?></td>
                                    <td><?= htmlspecialchars($subject['same_time']) ?></td>
                                    <td><?= htmlspecialchars($subject['preferred_days']) ?></td>
                                    <td>
                                        <a href="edit-subject.php?id=<?= $subject['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="subject_id" value="<?= $subject['id'] ?>">
                                            <button type="submit" name="delete_subject" class="btn btn-danger btn-sm">🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../components/admin-footer.php"; ?>