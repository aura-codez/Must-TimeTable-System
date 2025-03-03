<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/AdminController.php';

// Ensure Admin is Logged In
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../views/guest/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$adminController = new AdminController();

// Fetch Admin Department
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
    die("<div class='alert alert-danger text-center'>⚠️ Error: Department not found.</div>");
}

// Fetch Total Teachers & Students
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'teacher' AND department_id = ?");
$stmt->execute([$department_id]);
$total_teachers = $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'student' AND department_id = ?");
$stmt->execute([$department_id]);
$total_students = $stmt->fetchColumn();

// Fetch Total Confirmed Timetables
$stmt = $conn->prepare("SELECT COUNT(DISTINCT semester) FROM timetables WHERE department_id = ? AND is_confirmed = 1");
$stmt->execute([$department_id]);
$total_timetables = $stmt->fetchColumn();

// Fetch All Teachers for Admin's Department
$stmt = $conn->prepare("SELECT id, name, email, contact FROM users WHERE role = 'teacher' AND department_id = ?");
$stmt->execute([$department_id]);
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Pending Timetable Change Requests
$pending_requests = $adminController->getPendingRequests($department_id);

// Handle Room Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_room'])) {
    $room_name = trim($_POST['room_name']);
    if (empty($room_name)) {
        echo "<script>alert('❌ Please enter Room Name.');</script>";
    } else {
        try {
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM rooms WHERE room_name = ? AND department_id = ?");
            $check_stmt->execute([$room_name, $department_id]);
            $room_exists = $check_stmt->fetchColumn();

            if ($room_exists > 0) {
                echo "<script>alert('❌ Room \"{$room_name}\" already exists in this department!');</script>";
            } else {
                $stmt = $conn->prepare("INSERT INTO rooms (room_name, department_id) VALUES (?, ?)");
                $stmt->execute([$room_name, $department_id]);
                // Redirect to avoid resubmission and looping
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=Room+added+successfully");
                exit();
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
        }
    }
}

// Handle Room Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_room'])) {
    $room_id = $_POST['room_id'];
    try {
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM timetables WHERE room_id = ?");
        $check_stmt->execute([$room_id]);
        $room_in_use = $check_stmt->fetchColumn();

        if ($room_in_use > 0) {
            echo "<script>alert('❌ Cannot delete room - it is currently used in timetables!');</script>";
        } else {
            $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ? AND department_id = ?");
            $stmt->execute([$room_id, $department_id]);
            // Redirect to avoid resubmission and looping
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=Room+deleted+successfully");
            exit();
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
    }
}

// Display success message if present
if (isset($_GET['success'])) {
    $success_message = htmlspecialchars($_GET['success']);
    echo "<script>alert('✅ $success_message');</script>";
}
// Fetch Existing Rooms
$stmt = $conn->prepare("SELECT r.id, r.room_name, d.name as department_name FROM rooms r JOIN departments d ON r.department_id = d.id WHERE r.department_id = ? ORDER BY r.id DESC");
$stmt->execute([$department_id]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-0">
            <?php include "../../components/sidebar-admin.php"; ?>
        </div>
        <div class="col-md-9 p-4">
            <h2 class="text-warning text-center">🛠️ Admin Dashboard (<?= htmlspecialchars($department_name) ?>)</h2>

            <div class="row text-center">
                <div class="col-md-6">
                    <div class="card bg-dark text-light p-3 shadow-lg">
                        <h4>👨‍🏫 Total Teachers: <?= $total_teachers ?></h4>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-dark text-light p-3 shadow-lg">
                        <h4>📅 Timetables Created: <?= $total_timetables ?></h4>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="generate-timetable.php" class="btn btn-primary">⚡ Generate Timetable</a>
                <a href="manage-timetables.php" class="btn btn-warning">🛠️ Manage Timetable</a>
                <a href="#add-rooms-section" class="btn btn-info">🏫 Add Rooms</a>
            </div>

            <!-- Teacher Management Section -->
            <div class="card bg-dark text-light mt-5 p-4 shadow-lg">
                <h3 class="text-warning text-center">👨‍🏫 Teacher Management</h3>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>👤 Name</th>
                            <th>📧 Email</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td><?= htmlspecialchars($teacher['name']) ?></td>
                                <td><?= htmlspecialchars($teacher['email']) ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Rooms Section -->
            <div class="card bg-dark text-light mt-5 p-4 shadow-lg" id="add-rooms-section">
    <h3 class="text-warning text-center">🏫 Manage Rooms (<?= htmlspecialchars($department_name) ?>)</h3>
    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <input type="text" name="room_name" class="form-control bg-dark text-light border-warning" placeholder="Enter Room Name" required>
            </div>
            <div class="col-md-4">
                <button type="submit" name="add_room" class="btn btn-warning w-100">➕ Add Room</button>
            </div>
        </div>
    </form>

    <h4 class="text-warning text-center">📋 Room List</h4>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>🏫 Room Name</th>
                <th>🏛️ Department</th>
                <th>🛠️ Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $room): ?>
                <tr>
                    <td><?= htmlspecialchars($room['room_name']) ?></td>
                    <td><?= htmlspecialchars($room['department_name']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this room?');">
                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                            <button type="submit" name="delete_room" class="btn btn-danger btn-sm">🗑️ Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
          
                           
</div>

<?php include "../../components/admin-footer.php"; ?>
