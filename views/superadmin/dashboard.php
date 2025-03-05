<?php include '../../components/superadmin-header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-superadmin.php'; ?>
        </div>
        <div class="col-md-10 p-4 d-flex flex-column align-items-center" style="min-height: 100vh;">
            <!-- Welcome message outside container -->
            <h2 class="text-warning mt-4">Welcome, Super Admin</h2>
            <p class="text-light">Manage your admin team efficiently.</p>

            <!-- Improved container -->
            <div class="card bg-dark text-light p-4 mt-4 rounded shadow-lg" style="max-width: 600px; width: 100%; border: 2px solid #ffc107;">
                <h4 class="text-warning text-center mb-3">Admin Management</h4>
                <p class="text-center mb-4">View, add, update, or enable/disable admins and their details.</p>
                <a href="manage-admins.php" class="btn btn-warning btn-lg w-100">Go to Admin Management</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../components/superadmin-footer.php'; ?>
