<?php
include '../../config/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Password is stored as MD5 hash
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND role = ?");
    $stmt->execute([$email, $password, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['department'] = $user['department'];

        if ($role == "admin") {
            header("Location: ../admin/dashboard.php");
        } elseif ($role == "teacher") {
            header("Location: ../teacher/dashboard.php");
        } elseif ($role == "student") {
            header("Location: ../student/dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid credentials or role selection!');</script>";
    }
}
?>

<?php include '../../components/guest-header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" class="form-control mb-2" required>
        
        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-2" required>
        
        <label>Select Role:</label>
        <select name="role" class="form-control mb-2" required>
            <option value="admin">Admin</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>
        
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p class="text-center mt-3">Don't have an account? <a href="register.php">Register Here</a></p>
</div>

<?php include '../../components/guest-footer.php'; ?>
