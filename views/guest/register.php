<?php
include '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash password
    $role = $_POST['role'];
    $department_id = isset($_POST['department_id']) && $role === 'teacher' ? $_POST['department_id'] : NULL;
    $roll_no = isset($_POST['roll_no']) && $role === 'student' ? $_POST['roll_no'] : NULL;

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('User already exists!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, department_id, roll_no) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role, $department_id, $roll_no]);

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
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-4 register-container text-light">
            <h2 class="text-center text-warning mb-4">Register</h2>
            <form method="POST" autocomplete="off" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter 4-digit password" required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="roleSelect" class="form-label">Select Role:</label>
                    <select name="role" id="roleSelect" class="form-select" required onchange="toggleDepartmentField()">
                        <option value="" disabled selected>Select a role</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <!-- Department (Only for Teachers) -->
                <div id="departmentField" style="display: none;" class="mb-3">
                    <label for="department_id" class="form-label">Department:</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="" disabled selected>Select a department</option>
                        <option value="1">Software Engineering</option>
                        <option value="2">Computer Science</option>
                        <option value="3">Electrical Engineering</option>
                        <option value="4">Mechanical Engineering</option>
                    </select>
                </div>

                <!-- Roll Number (Only for Students) -->
                <div id="rollNoField" style="display: none;" class="mb-3">
                    <label for="roll_no" class="form-label">Roll Number:</label>
                    <input type="text" name="roll_no" id="roll_no" class="form-control" placeholder="e.g., FA22-BSE-020">
                </div>

                <button type="submit" class="btn btn-warning w-100">Register</button>
            </form>
            <script>
                // Validate form before submission
                function validateForm() {
                    var password = document.getElementById("password").value;
                    var role = document.getElementById("roleSelect").value;
                    var rollNo = document.getElementById("roll_no").value;

                    // Password must be exactly 4 digits (0-9)
                    var passwordRegex = /^[0-9]{4}$/;
                    if (!passwordRegex.test(password)) {
                        alert("Password must be exactly 4 digits (0-9 only)!");
                        return false;
                    }

                    // Roll number validation for students
                    if (role === "student") {
                        var rollNoRegex = /^FA[0-9]{2}-(BSE|BCS|BIT)-[0-9]{3}$/;
                        if (!rollNoRegex.test(rollNo)) {
                            alert("Roll number must be in format FA[year]-[program]-[roll]. Year is 2 digits, program is LIKE; BSE/BCS/BIT, roll is 3 digits.");
                            return false;
                        }
                    }

                    return true;
                }
            </script>
        </div>
    </div>
</div>

<script>
    function toggleDepartmentField() {
        var role = document.getElementById("roleSelect").value;
        var departmentField = document.getElementById("departmentField");
        var rollNoField = document.getElementById("rollNoField");

        if (role === "teacher") {
            departmentField.style.display = "block";
            rollNoField.style.display = "none";
        } else if (role === "student") {
            departmentField.style.display = "none";
            rollNoField.style.display = "block";
        } else {
            departmentField.style.display = "none";
            rollNoField.style.display = "none";
        }
    }
</script>

<?php include '../../components/guest-footer.php'; ?>
