<?php 
include '../../components/superadmin-header.php'; 
require_once '../../config/database.php';
require_once '../../controllers/SuperAdminController.php';

$superAdminController = new SuperAdminController();

// Get department ID from URL
$department_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch department details
$department = null;
if ($department_id > 0) {
    $stmt = $GLOBALS['conn']->prepare("SELECT * FROM departments WHERE id = ?");
    $stmt->execute([$department_id]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle Update Department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_department'])) {
    $name = trim($_POST['name']);
    $result = $superAdminController->updateDepartment($department_id, $name);
    if ($result === true) {
        echo "<script>alert('✅ Department updated successfully!'); window.location.href='manage-departments.php';</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

// Check if department exists
if (!$department) {
    echo "<div class='alert alert-danger text-center'>Department not found!</div>";
    include '../../components/superadmin-footer.php';
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-superadmin.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center">Update Department</h2>

            <!-- Update Department Form -->
            <div class="card bg-dark text-light p-4 mb-4 shadow-lg" style="max-width: 500px; margin: 0 auto;">
                <h4 class="text-warning text-center">Edit Department</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label text-light">Department Name:</label>
                        <input type="text" name="name" class="form-control bg-dark text-light border-warning" value="<?= htmlspecialchars($department['name']) ?>" required>
                    </div>
                    <button type="submit" name="update_department" class="btn btn-warning w-100">Update Department</button>
                    <a href="manage-departments.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../components/superadmin-footer.php'; ?>