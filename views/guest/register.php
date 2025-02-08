<?php
include '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash password
    $role = $_POST['role'];
    $department = $_POST['department'];
    $session = $_POST['session'];
    $roll_no = $_POST['roll_no'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, department, session, roll_no) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $password, $role, $department, $session, $roll_no])) {
        echo "<script>alert('Registration Successful! You can now log in.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error in registration!');</script>";
    }
}
?>

<?php include '../../components/guest-header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Register</h2>
    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="name" class="form-control mb-2" required>
        
        <label>Email:</label>
        <input type="email" name="email" class="form-control mb-2" required>
        
        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-2" required>
        
        <label>Select Role:</label>
        <select name="role" class="form-control mb-2" required>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>

        <label>Department:</label>
        <select name="department" class="form-control mb-2" required>
            <option value="Software Engineering">Software Engineering</option>
            <option value="Electrical Engineering">Electrical Engineering</option>
            <option value="Mechanical Engineering">Mechanical Engineering</option>
        </select>

        <label>Session:</label>
        <select name="session" class="form-control mb-2" required>
            <option value="FA22">FA22</option>
            <option value="SP23">SP23</option>
            <option value="FA23">FA23</option>
        </select>

        <label>Roll No. (Format: FA22-BSE-020):</label>
        <input type="text" name="roll_no" class="form-control mb-2" pattern="FA\d{2}-[A-Z]+-\d{3}" required>

        <button type="submit" class="btn btn-secondary">Register</button>
    </form>
    <p class="text-center mt-3">Already have an account? <a href="login.php">Login Here</a></p>
</div>

<?php include '../../components/guest-footer.php'; ?>
