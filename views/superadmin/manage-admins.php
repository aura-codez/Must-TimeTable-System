<?php 
include '../../components/superadmin-header.php'; 
require_once '../../config/database.php';
require_once '../../controllers/SuperAdminController.php';
require_once '../../models/UserModel.php';

$superAdminController = new SuperAdminController();
$userModel = new UserModel();

// Handle Add Admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
  
    $role = 'admin'; // Hardcoded to admin only
    $department_name = trim($_POST['department_name']);

    $result = $superAdminController->addUser($name, $email, $password, $role, $department_name);
    if ($result === true) {
        echo "<script>alert('✅ Admin added successfully!');</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

// Handle Enable/Disable Admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_user'])) {
    $user_id = $_POST['user_id'];
    $is_active = $_POST['is_active'];
    $result = $superAdminController->toggleUserStatus($user_id, $is_active);
    if ($result === true) {
        echo "<script>alert('✅ Admin status updated successfully!');</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

// Handle Update Admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $department_name = trim($_POST['department_name']);

    $result = $superAdminController->updateAdmin($user_id, $name, $email, $contact, $department_name);
    if ($result === true) {
        echo "<script>alert('✅ Admin updated successfully!');</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

$admins = $userModel->getAllUsers();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-superadmin.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center">Manage Admins</h2>

            <!-- Add Admin Form -->
            <div class="card bg-dark text-light p-4 mb-4 shadow-lg">
                <h4 class="text-warning">Add New Admin</h4>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label text-light">Name:</label>
                            <input type="text" name="name" class="form-control bg-dark text-light border-warning" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-light">Email:</label>
                            <input type="email" name="email" class="form-control bg-dark text-light border-warning" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-light">Password:</label>
                            <input type="password" name="password" class="form-control bg-dark text-light border-warning" required>
                        </div>
                   
                        <div class="col-md-3">
                            <label class="form-label text-light">Department:</label>
                            <input type="text" name="department_name" class="form-control bg-dark text-light border-warning" placeholder="Enter Department Name">
                        </div>
                    
                    <button type="submit" name="add_user" class="btn btn-warning mt-3 w-100">➕ Add Admin</button>
                </form>
            </div>

            <!-- Admins Table -->
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?= htmlspecialchars($admin['id']) ?></td>
                            <td><?= htmlspecialchars($admin['name']) ?></td>
                            <td><?= htmlspecialchars($admin['email']) ?></td>
                            <td>
                                <?php 
                                if (empty($admin['contact'])) {
                                    echo "N/A";
                                } else {
                                    echo htmlspecialchars($admin['contact']);
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if (empty($admin['department_name'])) {
                                    echo "N/A";
                                } else {
                                    echo htmlspecialchars($admin['department_name']);
                                }
                                ?>
                            </td>
                            <td><?php echo $admin['is_active'] ? "Active" : "Disabled"; ?></td>
                            <td>
                                <!-- Enable/Disable Form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $admin['id'] ?>">
                                    <input type="hidden" name="is_active" value="<?= $admin['is_active'] ?>">
                                    <button type="submit" name="toggle_user" class="btn btn-sm <?php echo $admin['is_active'] ? 'btn-danger' : 'btn-success'; ?>">
                                        <?php echo $admin['is_active'] ? "Disable" : "Enable"; ?>
                                    </button>
                                </form>
                                <!-- Update Form (Modal Trigger) -->
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal<?= $admin['id'] ?>">
                                    Update
                                </button>

                                <!-- Update Modal -->
                                <div class="modal fade" id="updateModal<?= $admin['id'] ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-dark text-light">
                                            <div class="modal-header border-warning">
                                                <h5 class="modal-title text-warning" id="updateModalLabel">Update Admin</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST">
                                                    <input type="hidden" name="user_id" value="<?= $admin['id'] ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Name:</label>
                                                        <input type="text" name="name" class="form-control bg-dark text-light border-warning" value="<?= htmlspecialchars($admin['name']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Email:</label>
                                                        <input type="email" name="email" class="form-control bg-dark text-light border-warning" value="<?= htmlspecialchars($admin['email']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Contact:</label>
                                                        <input type="text" name="contact" class="form-control bg-dark text-light border-warning" value="<?php echo empty($admin['contact']) ? '' : htmlspecialchars($admin['contact']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Department:</label>
                                                        <input type="text" name="department_name" class="form-control bg-dark text-light border-warning" value="<?php echo empty($admin['department_name']) ? '' : htmlspecialchars($admin['department_name']); ?>" placeholder="Enter Department Name">
                                                    </div>
                                                    <button type="submit" name="update_user" class="btn btn-warning w-100">Update Admin</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../components/superadmin-footer.php'; ?>
