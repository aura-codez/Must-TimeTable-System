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

// Fetch Pending Timetable Change Requests
$pending_requests = $adminController->getPendingRequests($department_id);
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-0">
            <?php include "../../components/sidebar-admin.php"; ?>
        </div>
        <div class="col-md-9 p-4">
            <h2 class="text-warning text-center bg-dark p-2">📑 View Timetable Change Requests (<?= htmlspecialchars($department_name) ?>)</h2>

            <?php if (empty($pending_requests)): ?>
                <div class="alert alert-info text-center">No pending timetable change requests.</div>
            <?php else: ?>
                <div class="card bg-dark text-light p-4 shadow-lg">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>👨‍🏫 Teacher</th>
                                <th>✏️ Reason</th>
                                <th>🕒 Requested Time</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_requests as $request): ?>
                                <tr>
                                    <td><?= htmlspecialchars($request['teacher_name']) ?></td>
                                    <td><?= htmlspecialchars($request['reason']) ?></td>
                                    <td><?= htmlspecialchars($request['requested_time']) ?></td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?php include "../../components/admin-footer.php"; ?>