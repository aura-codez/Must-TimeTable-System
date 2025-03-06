<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/AdminController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../views/guest/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$adminController = new AdminController();

// Fetch Admin Department
$stmt = $conn->prepare("SELECT d.id, d.name, d.timetables_created FROM departments d WHERE d.admin_id = ?");
$stmt->execute([$admin_id]);
$admin_dept = $stmt->fetch(PDO::FETCH_ASSOC);
if ($admin_dept) {
    $department_id = $admin_dept['id'];
    $department_name = $admin_dept['name'];
    $timetables_created = $admin_dept['timetables_created'];
} else {
    $department_id = null;
    $department_name = "Unknown";
    $timetables_created = 0;
}

if (!$department_id) {
    die("<div class='alert alert-danger text-center'>⚠️ Error: Department not found.</div>");
}

// Handle Timetable Confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_all_timetables'])) {
    try {
        $stmt = $conn->prepare("UPDATE timetables SET is_confirmed = 1 WHERE department_id = ? AND is_confirmed = 0");
        $stmt->execute([$department_id]);

        $timetables_created++;
        $stmt = $conn->prepare("UPDATE departments SET timetables_created = ? WHERE id = ?");
        $stmt->execute([$timetables_created, $department_id]);

        echo "<script>alert('✅ All Timetables Confirmed Successfully! Total timetables: $timetables_created'); window.location.href='confirmed-timetables.php';</script>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
    }
}

// Handle Delete Timetable
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_timetable'])) {
    $semester = $_POST['semester'];
    try {
        $stmt = $conn->prepare("DELETE FROM timetables WHERE department_id = ? AND semester = ?");
        $stmt->execute([$department_id, $semester]);

        if ($timetables_created > 0) {
            $timetables_created--;
            $stmt = $conn->prepare("UPDATE departments SET timetables_created = ? WHERE id = ?");
            $stmt->execute([$timetables_created, $department_id]);
        }

        echo "<script>alert('✅ Timetable for $semester deleted successfully!'); window.location.href='manage-timetables.php';</script>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
    }
}

// Handle Manual Placement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['move_subject'])) {
    $timetable_id = $_POST['timetable_id'];
    $new_day = $_POST['new_day'];
    $new_time_slot = $_POST['new_time_slot'];

    $result = $adminController->moveTimetableSubject($timetable_id, $new_day, $new_time_slot, $department_id);
    if ($result === true) {
        echo "<script>alert('✅ Subject moved successfully!'); window.location.href='manage-timetables.php';</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

// Fetch Timetables with combined section detection
$stmt = $conn->prepare("SELECT t.*, s.subject_name, s.duration, u.name AS teacher_name, r.room_name 
                        FROM timetables t 
                        LEFT JOIN subjects s ON t.subject_id = s.id 
                        LEFT JOIN users u ON t.teacher_id = u.id 
                        LEFT JOIN rooms r ON t.room_id = r.id 
                        WHERE t.department_id = ? 
                        ORDER BY t.semester, t.session DESC, t.section, t.day, t.time_slot");
$stmt->execute([$department_id]);
$timetables = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sessions = ['24-28', '23-27', '22-26', '21-25'];
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
$semesters = [];
$generated_dates = [];
foreach ($timetables as $entry) {
    $semester = $entry['semester'];
    $session = $entry['session'];
    $section = $entry['section'];
    $day = $entry['day'];
    $time_slot = $entry['time_slot'];

    if (!in_array($semester, $semesters)) {
        $semesters[] = $semester;
        $generated_dates[$semester] = $entry['generated_date'];
    }

    $schedule[$semester][$session][$section][$day][$time_slot] = [
        'id' => $entry['id'],
        'subject' => $entry['subject_name'],
        'teacher' => $entry['teacher_name'],
        'room' => $entry['room_name'],
        'duration' => $entry['duration'],
        'is_confirmed' => $entry['is_confirmed']
    ];

    // Check for matching entries across sections
    $other_section = ($section === 'A') ? 'B' : 'A';
    if (isset($schedule[$semester][$session][$other_section][$day][$time_slot])) {
        $current = $schedule[$semester][$session][$section][$day][$time_slot];
        $other = $schedule[$semester][$session][$other_section][$day][$time_slot];
        
        if ($current['subject'] === $other['subject'] && 
            $current['teacher'] === $other['teacher'] && 
            $current['room'] === $other['room']) {
            // Combine sections
            $schedule[$semester][$session]['A+B'][$day][$time_slot] = $current;
        }
    }
}
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-0">
            <?php include "../../components/sidebar-admin.php"; ?>
        </div>
        <div class="col-md-9 p-4">
            <h2 class="text-warning text-center bg-dark p-2">🛠️ Manage Timetables (<?= htmlspecialchars($department_name) ?>) - Total Created: <?= $timetables_created ?></h2>

            <?php if (empty($semesters)): ?>
                <div class='alert alert-warning text-center'>No semesters found. Generate a timetable first.</div>
            <?php else: ?>
                <?php foreach ($semesters as $semester): ?>
                    <h3 class="text-warning text-center bg-dark p-2 mt-5">
                        <?= htmlspecialchars($semester) ?> - Generated on: <?= htmlspecialchars($generated_dates[$semester]) ?>
                        <form method="POST" style="display:inline;" class="ms-3">
                            <input type="hidden" name="semester" value="<?= $semester ?>">
                            <button type="submit" name="delete_timetable" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete the timetable for <?= $semester ?>?');">🗑️ Delete</button>
                        </form>
                    </h3>

                    <?php foreach ($sessions as $session): ?>
                        <h4 class="text-light mt-5 bg-primary p-2">Session: <?= htmlspecialchars($session) ?></h4>
                        <?php $display_sections = array_merge($sections, ['A+B']); ?>
                        <?php foreach ($display_sections as $section): ?>
                            <?php if ($section !== 'A+B' || isset($schedule[$semester][$session]['A+B'])): ?>
                                <h5 class="text-light mt-4 bg-secondary p-2">Section: <?= htmlspecialchars($section) ?></h5>
                                <div class="table-responsive">
                                    <table class="table table-dark table-bordered">
                                        <thead>
                                            <tr style="background-color: #343a40;">
                                                <th class="text-light">Day</th>
                                                <?php foreach ($time_slots as $slot => $duration): ?>
                                                    <th class="text-light"><?= htmlspecialchars($slot) ?></th>
                                                <?php endforeach; ?>
                                                <th class="text-light">Move</th>
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
                                                            if (isset($schedule[$semester][$session][$section][$day][$slot])) {
                                                                $entry = $schedule[$semester][$session][$section][$day][$slot];
                                                                $subject = $entry['subject'];
                                                                $teacher = $entry['teacher'];
                                                                $room = $entry['room'];
                                                                $duration = (float)$entry['duration'];

                                                                if ($subject && $teacher && $room) {
                                                                    $display_slot = $slot;
                                                                    if ($duration > $slot_duration) {
                                                                        $start_time = substr($slot, 0, 5);
                                                                        $end_time = date("H:i", strtotime("$start_time + $duration hours"));
                                                                        $display_slot = "$start_time - $end_time";
                                                                    }
                                                                    echo "<span style='color: #ffc107;'>$subject</span><br>";
                                                                    echo "<span style='color: #28a745;'>$teacher</span><br>";
                                                                    echo "<span style='color: #17a2b8;'>$room ($display_slot)</span>";
                                                                }
                                                            } else {
                                                                echo " "; // Empty cell
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td>
                                                        <?php if (isset($schedule[$semester][$session][$section][$day])): ?>
                                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#moveModal_<?= $semester . $session . $section . $day ?>">
                                                                Move Subject
                                                            </button>
                                                            <div class="modal fade" id="moveModal_<?= $semester . $session . $section . $day ?>" tabindex="-1">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content bg-dark text-light">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title text-warning">Move Subject</h5>
                                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form method="POST">
                                                                                <div class="mb-3">
                                                                                    <label class="form-label text-warning">Select Subject:</label>
                                                                                    <select name="timetable_id" class="form-control bg-dark text-light border-warning" required>
                                                                                        <option value="">-- Select Subject --</option>
                                                                                        <?php foreach ($schedule[$semester][$session][$section][$day] as $slot => $entry): ?>
                                                                                            <?php if ($entry['subject']): ?>
                                                                                                <option value="<?= $entry['id'] ?>"><?= htmlspecialchars($entry['subject']) ?> (<?= $slot ?>)</option>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label class="form-label text-warning">New Day:</label>
                                                                                    <select name="new_day" class="form-control bg-dark text-light border-warning" required>
                                                                                        <?php foreach ($days as $d): ?>
                                                                                            <option value="<?= $d ?>"><?= $d ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label class="form-label text-warning">New Time Slot:</label>
                                                                                    <select name="new_time_slot" class="form-control bg-dark text-light border-warning" required>
                                                                                        <?php foreach ($time_slots as $slot => $dur): ?>
                                                                                            <option value="<?= $slot ?>"><?= $slot ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                                <button type="submit" name="move_subject" class="btn btn-warning w-100">Move</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php $row_count++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php if (!empty($timetables) && !$timetables[0]['is_confirmed']): ?>
                    <form method="POST" class="text-center mt-3">
                        <button type="submit" name="confirm_all_timetables" class="btn btn-success">✅ Confirm All Timetables</button>
                    </form>
                <?php else: ?>
                    <p class="text-success text-center mt-3">✅ All Timetables Confirmed</p>
                <?php endif; ?>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="/MUST-Timetable-System/views/admin/generate-timetable.php" class="btn btn-warning">⚡ Generate New Timetable</a>
            </div>
        </div>
    </div>
</div>

<?php include "../../components/admin-footer.php"; ?>