<?php 
include '../../components/superadmin-header.php'; 
require_once '../../config/database.php';
require_once '../../controllers/SuperAdminController.php';
require_once '../../models/UserModel.php';

$superAdminController = new SuperAdminController();
$userModel = new UserModel();

// Handle Add User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $department_name = trim($_POST['department_name']);

    $result = $superAdminController->addUser($name, $email, $password, $role, $department_name);
    if ($result === true) {
        echo "<script>alert('✅ User added successfully!');</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

// Handle Enable/Disable User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_user'])) {
    $user_id = $_POST['user_id'];
    $is_active = $_POST['is_active'];
    $result = $superAdminController->toggleUserStatus($user_id, $is_active);
    if ($result === true) {
        echo "<script>alert('✅ User status updated successfully!');</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>$result</div>";
    }
}

$users = $userModel->getAllUsers();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-superadmin.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center">Manage Users</h2>

            <!-- Add User Form -->
            <div class="card bg-dark text-light p-4 mb-4 shadow-lg">
                <h4 class="text-warning">Add New User</h4>
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
                            <label class="form-label text-light">Role:</label>
                            <select name="role" class="form-control bg-dark text-light border-warning" required>
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="student">Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label text-light">Department:</label>
                            <input type="text" name="department_name" class="form-control bg-dark text-light border-warning" placeholder="Enter Department Name">
                        </div>
                    </div>
                    <button type="submit" name="add_user" class="btn btn-warning mt-3 w-100">➕ Add User</button>
                </form>
            </div>

            <!-- Users Table -->
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <?php 
                                if ($user['department_name']) {
                                    echo htmlspecialchars($user['department_name']);
                                } else {
                                    echo "N/A";
                                }
                                ?>
                            </td>
                            <td><?php echo $user['is_active'] ? "Active" : "Disabled"; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="is_active" value="<?= $user['is_active'] ?>">
                                    <button type="submit" name="toggle_user" class="btn btn-sm <?php echo $user['is_active'] ? 'btn-danger' : 'btn-success'; ?>">
                                        <?php echo $user['is_active'] ? "Disable" : "Enable"; ?>
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