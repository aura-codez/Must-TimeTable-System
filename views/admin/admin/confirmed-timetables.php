<?php
session_start();
require_once '../../config/database.php';

// Allow admins, teachers, and students to view
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'teacher', 'student'])) {
    header("Location: ../../views/guest/login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// For admin, fetch their department; for others, fetch all confirmed timetables
if ($role === 'admin') {
    $stmt = $conn->prepare("SELECT d.id, d.name FROM departments d WHERE d.admin_id = ?");
    $stmt->execute([$user_id]);
    $dept = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($dept) {
        $department_id = $dept['id'];
        $department_name = $dept['name'];
    } else {
        die("<div class='alert alert-danger text-center'>⚠️ Error: Department not found.</div>");
    }

    $stmt = $conn->prepare("SELECT t.*, s.subject_name, s.duration, u.name AS teacher_name, r.room_name 
                            FROM timetables t 
                            LEFT JOIN subjects s ON t.subject_id = s.id 
                            LEFT JOIN users u ON t.teacher_id = u.id 
                            LEFT JOIN rooms r ON t.room_id = r.id 
                            WHERE t.department_id = ? AND t.is_confirmed = 1 
                            ORDER BY t.semester, t.session, t.section, t.day, t.time_slot");
    $stmt->execute([$department_id]);
} else {
    // Teachers and students see all confirmed timetables
    $stmt = $conn->prepare("SELECT t.*, s.subject_name, s.duration, u.name AS teacher_name, r.room_name, d.name AS department_name, d.id AS department_id 
                            FROM timetables t 
                            LEFT JOIN subjects s ON t.subject_id = s.id 
                            LEFT JOIN users u ON t.teacher_id = u.id 
                            LEFT JOIN rooms r ON t.room_id = r.id 
                            JOIN departments d ON t.department_id = d.id 
                            WHERE t.is_confirmed = 1 
                            ORDER BY t.department_id, t.semester, t.session, t.section, t.day, t.time_slot");
    $stmt->execute();
}

$timetables = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sessions = ['21-25', '22-26', '23-27', '24-28'];
$sections = ['A', 'B'];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$time_slots = [
    '08:30-10:00' => 1.5,
    '10:00-11:30' => 1.5,
    '11:30-13:00' => 1.5,
    '13:00-13:30' => 0.5,
    '13:30-15:00' => 1.5,
    '15:00-16:30' => 1.5
];

$schedule = [];
$departments = [];
$generated_dates = [];
foreach ($timetables as $entry) {
    $semester = $entry['semester'];
    $session = $entry['session'];
    $section = $entry['section'];
    $day = $entry['day'];
    $time_slot = $entry['time_slot'];
    $dept_id = $role === 'admin' ? $department_id : $entry['department_id'];

    if (!isset($departments[$dept_id])) {
        $departments[$dept_id] = [
            'name' => $role === 'admin' ? $department_name : $entry['department_name'],
            'generated_date' => $entry['generated_date']
        ];
    }

    $schedule[$dept_id][$semester][$session][$section][$day][$time_slot] = [
        'subject' => $entry['subject_name'],
        'teacher' => $entry['teacher_name'],
        'room' => $entry['room_name'],
        'duration' => $entry['duration']
    ];
}
?>

<?php 
$header = $role === 'admin' ? "../../components/admin-header.php" : 
         ($role === 'teacher' ? "../../components/teacher-header.php" : "../../components/student-header.php");
$sidebar = $role === 'admin' ? "../../components/sidebar-admin.php" : 
          ($role === 'teacher' ? "../../components/sidebar-teacher.php" : "../../components/sidebar-student.php");
$footer = $role === 'admin' ? "../../components/admin-footer.php" : 
         ($role === 'teacher' ? "../../components/teacher-footer.php" : "../../components/student-footer.php");
include $header;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include $sidebar; ?>
        </div>
        <div class="col-md-10 p-4">
            <?php if (empty($departments)): ?>
                <div class='alert alert-warning text-center'>No confirmed timetables available.</div>
            <?php else: ?>
                <?php foreach ($departments as $dept_id => $dept_info): ?>
                    <h3 class="text-warning text-center bg-dark p-2">
                        <?= htmlspecialchars($dept_info['name']) ?> - Generated on: <?= htmlspecialchars($dept_info['generated_date']) ?>
                    </h3>

                    <?php foreach ($sessions as $session): ?>
                        <h4 class="text-light mt-5 bg-primary p-2">Session: <?= htmlspecialchars($session) ?></h4>
                        <?php foreach ($sections as $section): ?>
                            <h5 class="text-light mt-4 bg-secondary p-2">Section: <?= htmlspecialchars($section) ?></h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-bordered">
                                    <thead>
                                        <tr style="background-color: #343a40;">
                                            <th class="text-light">Day</th>
                                            <?php foreach ($time_slots as $slot => $duration): ?>
                                                <th class="text-light"><?= htmlspecialchars($slot) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $row_count = 0; ?>
                                        <?php foreach ($days as $day): ?>
                                            <tr style="background-color: <?php echo ($row_count % 2 == 0) ? '#343a40' : '#495057'; ?>">
                                                <td class="text-light"><?= htmlspecialchars($day) ?></td>
                                                <?php foreach ($time_slots as $slot => $slot_duration): ?>
                                                    <td>
                                                        <?php
                                                        if (isset($schedule[$dept_id][$semester][$session][$section][$day][$slot])) {
                                                            $entry = $schedule[$dept_id][$semester][$session][$section][$day][$slot];
                                                            $subject = $entry['subject'];
                                                            $teacher = $entry['teacher'];
                                                            $room = $entry['room'];

                                                            if ($subject && $teacher && $room) {
                                                                echo "<span style='color: #ffc107;'>$subject</span><br>";
                                                                echo "<span style='color: #28a745;'>$teacher</span><br>";
                                                                echo "<span style='color: #17a2b8;'>$room</span>";
                                                            } else {
                                                                echo " "; // Empty cell
                                                            }
                                                        } else {
                                                            echo " "; // Empty cell
                                                        }
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                            <?php $row_count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include $footer; ?>