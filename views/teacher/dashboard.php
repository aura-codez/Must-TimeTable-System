<?php include '../../components/admin-header.php'; ?>
<?php include '../../config/database.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">View Payments</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT p.*, u.name FROM payments p JOIN users u ON p.user_id = u.user_id");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['amount']}</td>
                        <td>{$row['payment_method']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include '../../components/admin-footer.php'; ?>
