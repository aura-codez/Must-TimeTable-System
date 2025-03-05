<?php 
include '../../components/superadmin-header.php'; 
require_once '../../config/database.php';
require_once '../../controllers/SuperAdminController.php';

$superAdminController = new SuperAdminController();

// Handle Delete Department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_department'])) {
    $department_id = $_POST['department_id'];
    $result = $superAdminController->deleteDepartment($department_id);
    if ($result === true) {
        echo "<script>alert('✅ Department deleted successfully!');</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

// Fetch all departments
$departments = $superAdminController->getAllDepartments();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-superadmin.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center">Manage Departments</h2>

            <!-- Departments Table -->
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $department): ?>
                        <tr>
                            <td><?= htmlspecialchars($department['id']) ?></td>
                            <td><?= htmlspecialchars($department['name']) ?></td>
                            <td>
                                <!-- Update Button -->
                                <a href="update-department.php?id=<?= $department['id'] ?>" class="btn btn-sm btn-warning">Update</a>
                                <!-- Delete Form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="department_id" value="<?= $department['id'] ?>">
                                    <button type="submit" name="delete_department" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this department? This will remove it from all linked admins.');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../components/superadmin-footer.php'; ?>
