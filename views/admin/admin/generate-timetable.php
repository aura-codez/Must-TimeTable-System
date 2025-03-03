<?php
session_start();
require_once '../../config/database.php';

// Ensure Admin is Logged In
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../views/guest/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Fetch Department Info
$stmt = $conn->prepare("SELECT d.id, d.name FROM departments d WHERE d.admin_id = ?");
$stmt->execute([$admin_id]);
$admin_dept = $stmt->fetch(PDO::FETCH_ASSOC);
if ($admin_dept) {
    $department_id = $admin_dept['id'];
    $department_name = $admin_dept['name'];
} else {
    $department_id = null;
    $department_name = "Unknown";
}

if (!$department_id) {
    die("<div class='alert alert-danger text-center'>⚠️ Error: Department not found. Contact superadmin.</div>");
}

// Fetch Subjects with Room and Teacher Info
$stmt = $conn->prepare("SELECT s.*, u.name AS teacher_name, r.room_name 
                        FROM subjects s 
                        JOIN users u ON s.teacher_id = u.id 
                        LEFT JOIN rooms r ON s.room_id = r.id 
                        WHERE s.department_id = ?");
$stmt->execute([$department_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($subjects)) {
    echo "<div class='alert alert-warning text-center'>No subjects found for this department.</div>";
} else {
    echo "<div class='alert alert-info text-center'>Found " . count($subjects) . " subjects.</div>";
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$time_slots = [
    '08:30-10:00' => 1.5,
    '10:00-11:30' => 1.5,
    '11:30-13:00' => 1.5,
    '13:30-15:00' => 1.5,
    '15:00-16:30' => 1.5
];
$sessions = ['21-25', '22-26', '23-27', '24-28'];
$sections = ['A', 'B'];
$current_date = date('Y-m-d H:i:s');

// Handle Timetable Generation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_timetable'])) {
    $semester = $_POST['semester'];

    // Clear existing timetable for this department and semester
    $stmt = $conn->prepare("DELETE FROM timetables WHERE department_id = ? AND semester = ?");
    $stmt->execute([$department_id, $semester]);

    $conflicts = [];
    $inserted_count = 0;

    // Assign subjects based on preferred days and duration
    foreach ($subjects as $subject) {
        $preferred_days = explode(", ", $subject['preferred_days']);
        $days_needed = (int)$subject['days_per_week'];
        $session = $subject['session'];
        $section = $subject['section'];
        $assigned_count = 0;

        shuffle($preferred_days);
        $days_to_use = array_slice($preferred_days, 0, $days_needed);

        foreach ($days_to_use as $day) {
            if (!in_array($day, $days)) continue;

            $slot_keys = array_keys($time_slots);
            shuffle($slot_keys);
            $assigned = false;

            foreach ($slot_keys as $slot) {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM timetables 
                                        WHERE department_id = ? AND semester = ? AND section = ? AND day = ? AND time_slot = ? 
                                        AND (teacher_id = ? OR room_id = ?)");
                $stmt->execute([$department_id, $semester, $section, $day, $slot, $subject['teacher_id'], $subject['room_id']]);
                $conflict = $stmt->fetchColumn();

                if ($conflict == 0) {
                    $stmt = $conn->prepare("INSERT INTO timetables 
                        (session, section, day, time_slot, subject_id, teacher_id, room_id, department_id, semester, generated_date, is_confirmed) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
                    $stmt->execute([
                        $session, $section, $day, $slot, $subject['id'], $subject['teacher_id'], $subject['room_id'], 
                        $department_id, $semester, $current_date
                    ]);
                    $assigned_count++;
                    $inserted_count++;
                    $assigned = true;
                    break;
                }
            }

            if (!$assigned) {
                $conflicts[] = "Conflict: Could not schedule {$subject['subject_name']} on $day.";
            }
        }

        if ($assigned_count < $days_needed) {
            $conflicts[] = "Could not schedule {$subject['subject_name']} for all {$days_needed} days.";
        }
    }

    if (!empty($conflicts)) {
        echo "<div class='alert alert-warning text-center'><ul>";
        foreach ($conflicts as $conflict) {
            echo "<li>$conflict</li>";
        }
        echo "</ul></div>";
    } else {
        echo "<script>alert('✅ Timetable Generated Successfully for $semester! Inserted $inserted_count subject entries.'); window.location.href='/MUST-Timetable-System/views/admin/manage-timetables.php';</script>";
    }
}
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include "../../components/sidebar-admin.php"; ?>
        </div>
        <div class="col-md-9 p-4">
            <h2 class="text-warning text-center">⚡ Generate Timetable (<?= htmlspecialchars($department_name) ?>)</h2>
            <div class="card bg-dark text-light p-4 shadow-lg rounded">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-light">🌸 Select Semester:</label>
                            <select name="semester" class="form-control bg-dark text-light border-warning" required>
                                <option value="Spring">Spring</option>
                                <option value="Fall">Fall</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" name="generate_timetable" class="btn btn-primary w-100">⚡ Generate</button>
                        </div>
                    </div>
                </form>
                <p class="text-light mt-3">Generated on: <?= $current_date ?></p>
            </div>
        </div>
    </div>
</div>

<?php include "../../components/admin-footer.php"; ?>