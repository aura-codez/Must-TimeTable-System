<?php
include '../../config/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['email']; // This will be email or roll_no depending on role
    $password = md5($_POST['password']); // Hash password
    $role = $_POST['role'];

    // Use different column based on role
    if ($role === 'student') {
        // For students, check roll_no
        $stmt = $conn->prepare("SELECT u.id, u.role, u.department_id, d.name AS department_name 
                                FROM users u 
                                LEFT JOIN departments d ON u.department_id = d.id 
                                WHERE u.roll_no = ? AND u.password = ? AND u.role = ?");
    } else {
        // For other roles, check email
        $stmt = $conn->prepare("SELECT u.id, u.role, u.department_id, d.name AS department_name 
                                FROM users u 
                                LEFT JOIN departments d ON u.department_id = d.id 
                                WHERE u.email = ? AND u.password = ? AND u.role = ?");
    }
    $stmt->execute([$input, $password, $role]);
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
    <form method="POST" onsubmit="return validateForm()">
        <label>Select Role:</label>
        <select name="role" id="roleSelect" class="form-control mb-2" required onchange="toggleInputField()">
            <option value="" disabled selected>Select a role</option>
            <option value="superadmin">Super Admin</option>
            <option value="admin">Department Admin</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>

        <div id="emailField" style="display: block;">
            <label>Email:</label>
            <input type="email" name="email" id="email" class="form-control mb-2" placeholder="Enter your email" required>
        </div>

        <div id="rollNoField" style="display: none;">
            <label>Roll Number:</label>
            <input type="text" id="roll_no" class="form-control mb-2" placeholder="e.g., FA22-BSE-020" required>
        </div>

        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-2" required>

        <button type="submit" class="btn btn-warning w-100">Login</button>
    </form>
    <script>
        function toggleInputField() {
            var role = document.getElementById("roleSelect").value;
            var emailField = document.getElementById("emailField");
            var rollNoField = document.getElementById("rollNoField");

            if (role === "student") {
                emailField.style.display = "none";
                rollNoField.style.display = "block";
            } else {
                emailField.style.display = "block";
                rollNoField.style.display = "none";
            }
        }

        function validateForm() {
            var role = document.getElementById("roleSelect").value;
            var emailInput = document.getElementById("email");

            if (role === "student") {
                var rollNo = document.getElementById("roll_no").value;
                var rollNoRegex = /^FA[0-9]{2}-(BSE|BCS|BIT)-[0-9]{3}$/;
                if (!rollNoRegex.test(rollNo)) {
                    alert("Invalid roll number! It must be in the format FA[year]-[program]-[roll], e.g., FA22-BSE-020.");
                    return false;
                }
                // Copy roll number to email field for submission
                emailInput.value = rollNo;
            }

            return true;
        }
    </script>
</div>
<?php include '../../components/guest-footer.php'; ?>
