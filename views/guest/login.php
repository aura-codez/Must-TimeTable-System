<?php
include '../../config/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash password
    $role = $_POST['role'];

    // Fetch user details including department_id (and optionally department name)
    $stmt = $conn->prepare("SELECT u.id, u.role, u.department_id, d.name AS department_name 
                            FROM users u 
                            LEFT JOIN departments d ON u.department_id = d.id 
                            WHERE u.email = ? AND u.password = ? AND u.role = ?");
    $stmt->execute([$email, $password, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Store department name or "Unknown" if not applicable
        if (!empty($user['department_name'])) {
            $_SESSION['department'] = $user['department_name'];
        } else {
            $_SESSION['department'] = "Unknown";
        }

        // Redirect based on role
        $redirects = [
            "superadmin" => "../superadmin/dashboard.php",
            "admin" => "../admin/dashboard.php",
            "teacher" => "../teacher/dashboard.php",
            "student" => "../student/dashboard.php"
        ];

        header("Location: " . $redirects[$role]);
        exit();
    } else {
        echo "<script>alert('❌ Invalid credentials or role selection!');</script>";
    }
}
?>

<?php include '../../components/guest-header.php'; ?>
<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Styling -->
<style>
    body {
        background-color: #1a1a1a;
        color: white;
    }
    .login-container {
        max-width: 400px;
        margin: 50px auto;
        background-color: #222;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
    .btn-warning:hover {
        background-color: #e0a800 !important;
        transition: 0.3s;
    }
</style>

<!-- Login Form -->
<div class="login-container text-light">
    <h2 class="text-center text-warning">Login</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" class="form-control mb-2" required>

        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-2" required>

        <label>Select Role:</label>
        <select name="role" class="form-control mb-2" required>
            <option value="superadmin">Super Admin</option>
            <option value="admin">Department Admin</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>

        <button type="submit" class="btn btn-warning w-100">Login</button>
    </form>
</div>
<?php include '../../components/guest-footer.php'; ?>
