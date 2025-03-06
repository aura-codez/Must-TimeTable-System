<?php 
include '../../components/teacher-header.php'; 
require_once '../../config/database.php';
require_once '../../controllers/TeacherController.php';
require_once '../../models/TimetableModel.php';

$teacherController = new TeacherController();
$timetableModel = new TimetableModel();

// Fetch all departments with confirmed timetables
$departments = $timetableModel->getConfirmedDepartments();
$selected_department_id = '';
if (isset($_POST['department_id'])) {
    $selected_department_id = $_POST['department_id'];
} else {
    $selected_department_id = '';
}

$selected_timetable = [];
$generated_date = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_timetable']) && !empty($selected_department_id)) {
    $selected_timetable = $timetableModel->getConfirmedTimetableByDepartment($selected_department_id);
    if (!empty($selected_timetable)) {
        $generated_date = $selected_timetable[0]['generated_date'];
    } else {
        $generated_date = '';
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-teacher.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center">View Confirmed Timetables</h2>

            <!-- Department Selection Form -->
            <div class="card bg-dark text-light p-4 mb-4 shadow-lg">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-warning">Select Department:</label>
                            <select name="department_id" class="form-control bg-dark text-light border-warning" required>
                                <option value="">-- Select Department --</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?php if ($selected_department_id == $dept['id']) echo 'selected'; else echo ''; ?>>
                                        <?= htmlspecialchars($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" name="view_timetable" class="btn btn-warning w-100">View Timetable</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (!empty($selected_timetable)): ?>
                <h3 class="text-warning text-center bg-dark p-2">
                    <?php 
                    $dept_name = '';
                    foreach ($departments as $d) {
                        if ($d['id'] == $selected_department_id) {
                            $dept_name = $d['name'];
                            break;
                        }
                    }
                    echo htmlspecialchars($dept_name) . " - Generated on: " . htmlspecialchars($generated_date);
                    ?>
                </h3>

                <?php 
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
                foreach ($selected_timetable as $entry) {
                    $session = $entry['session'];
                    $section = $entry['section'];
                    $day = $entry['day'];
                    $time_slot = $entry['time_slot'];
                    $schedule[$session][$section][$day][$time_slot] = [
                        'subject' => $entry['subject_name'],
                        'teacher' => $entry['teacher_name'],
                        'room' => $entry['room_name'],
                        'duration' => $entry['duration']
                    ];
                }
                ?>

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
                                                    if (isset($schedule[$session][$section][$day][$slot])) {
                                                        $entry = $schedule[$session][$section][$day][$slot];
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../components/teacher-footer.php'; ?>