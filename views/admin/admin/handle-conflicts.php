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

$conflicts = $adminController->getTeacherConflicts($department_id);
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-0">
            <?php include "../../components/sidebar-admin.php"; ?>
        </div>
        <div class="col-md-9 p-4">
            <h2 class="text-warning text-center bg-dark p-2">🔍 Handle Teacher Conflicts (<?= htmlspecialchars($department_name) ?>)</h2>

            <?php if (empty($conflicts)): ?>
                <div class="alert alert-success text-center">No teacher conflicts found.</div>
            <?php else: ?>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Day</th>
                            <th>Time Slot</th>
                            <th>Conflicting Classes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($conflicts as $conflict): ?>
                            <tr>
                                <td><?= htmlspecialchars($conflict['teacher_name']) ?></td>
                                <td><?= htmlspecialchars($conflict['day']) ?></td>
                                <td><?= htmlspecialchars($conflict['time_slot']) ?></td>
                                <td>
                                    <?php foreach ($conflict['classes'] as $class): ?>
                                        <?= htmlspecialchars($class['subject_name']) ?> (<?= $class['session'] ?>-<?= $class['section'] ?>)<br>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?php include "../../components/admin-footer.php"; ?>