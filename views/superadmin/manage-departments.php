<?php include '../../components/superadmin-header.php'; ?>
<?php include '../../config/database.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-superadmin.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center">Manage Departments</h2>

            <!-- Add Department Form -->
            <div class="card bg-dark text-light p-4 mb-4 shadow-lg">
                <h4 class="text-warning">Add New Department</h4>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-light">Department Name:</label>
                            <input type="text" name="name" class="form-control bg-dark text-light border-warning" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Admin (Optional):</label>
                            <select name="admin_id" class="form-control bg-dark text-light border-warning">
                                <option value="">-- No Admin --</option>
                                <?php
                                $stmt = $conn->query("SELECT id, name FROM users WHERE role = 'admin' AND is_active = 1");
                                while ($admin = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$admin['id']}'>{$admin['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="add_department" class="btn btn-warning mt-3 w-100">➕ Add Department</button>
                </form>
            </div>

            <?php
            // Handle Add Department
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_department'])) {
                $name = $_POST['name'];
                $admin_id = !empty($_POST['admin_id']) ? $_POST['admin_id'] : NULL;

                try {
                    $stmt = $conn->prepare("INSERT INTO departments (name, admin_id) VALUES (?, ?)");
                    $stmt->execute([$name, $admin_id]);
                    echo "<script>alert('✅ Department added successfully!');</script>";
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
                }
            }
            ?>

            <!-- Departments Table -->
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT d.id, d.name AS dept_name, u.name AS admin_name 
                                            FROM departments d 
                                            LEFT JOIN users u ON d.admin_id = u.id");
                    $stmt->execute();
                    while ($dept = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>{$dept['id']}</td>";
                        echo "<td>{$dept['dept_name']}</td>";
                        echo "<td>";
                        if ($dept['admin_name']) {
                            echo htmlspecialchars($dept['admin_name']);
                        } else {
                            echo "N/A";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../components/superadmin-footer.php'; ?>