<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../views/guest/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userModel = new UserModel();
$user = $userModel->getUserById($user_id);

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = !empty($_POST['password']) ? md5($_POST['password']) : $user['password'];

    if ($userModel->updateProfile($user_id, $name, $email, $contact, $password)) {
        echo "<script>alert('✅ Profile updated successfully!'); window.location.href='/MUST-Timetable-System/views/student/profile.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Error updating profile.</div>";
    }
}
?>

<?php include "../../components/student-header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include "../../components/sidebar-student.php"; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center mb-4">👤 Student Profile</h2>

            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card bg-dark text-light p-4 shadow-lg">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label text-warning">Name:</label>
                                <input type="text" name="name" class="form-control bg-dark text-light border-warning" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-warning">Email:</label>
                                <input type="email" name="email" class="form-control bg-dark text-light border-warning" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-warning">Contact:</label>
                                <input type="text" name="contact" class="form-control bg-dark text-light border-warning" value="<?php if (isset($user['contact'])) echo htmlspecialchars($user['contact']); else echo ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-warning">New Password (leave blank to keep current):</label>
                                <input type="password" name="password" class="form-control bg-dark text-light border-warning" placeholder="Enter new password">
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-warning w-100">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../components/student-footer.php"; ?>