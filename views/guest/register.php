<?php
include '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash password
    $role = $_POST['role'];
    $department_id = isset($_POST['department_id']) && $role === 'teacher' ? $_POST['department_id'] : NULL;

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('User already exists!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, department_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role, $department_id]);

        echo "<script>alert('Registration Successful! You can now log in.'); window.location.href='login.php';</script>";
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
    .register-container {
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

<!-- Registration Form -->
<div class="register-container text-light">
    <h2 class="text-center text-warning">Register</h2>
    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="name" class="form-control mb-2" required>

        <label>Email:</label>
        <input type="email" name="email" class="form-control mb-2" required>

        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-2" required>

        <label>Select Role:</label>
        <select name="role" id="roleSelect" class="form-control mb-2" required onchange="toggleDepartmentField()">
            <option value="superadmin">Super Admin</option>
            <option value="admin">Department Admin</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>

        <!-- Department (Only for Teachers) -->
        <div id="departmentField" style="display: none;">
            <label>Department:</label>
            <select name="department_id" class="form-control mb-2">
                <option value="1">Software Engineering</option>
                <option value="2">Computer Science</option>
                <option value="3">Electrical Engineering</option>
                <option value="4">Mechanical Engineering</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning w-100">Register</button>
    </form>
</div>

<script>
    function toggleDepartmentField() {
        var role = document.getElementById("roleSelect").value;
        var departmentField = document.getElementById("departmentField");

        if (role === "teacher") {
            departmentField.style.display = "block";
        } else {
            departmentField.style.display = "none";
        }
    }
</script>

<?php include '../../components/guest-footer.php'; ?>
