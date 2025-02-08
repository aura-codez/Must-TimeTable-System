<?php include '../../components/admin-header.php'; ?>
<?php include '../../config/database.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Manage Users</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM users");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <a href='edit-user.php?id={$row['user_id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='../controllers/AdminController.php?deleteUser={$row['user_id']}' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include '../../components/admin-footer.php'; ?>
