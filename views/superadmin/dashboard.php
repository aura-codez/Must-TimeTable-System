<?php include '../../components/superadmin-header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-superadmin.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning">Welcome, Super Admin</h2>
            <p class="text-light">Manage users and departments.</p>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-dark text-light p-3 rounded shadow-lg">
                        <h4 class="text-warning">Manage Users & Departments</h4>
                        <p>Add, enable, disable users, and manage departments.</p>
                        <a href="manage-admins.php" class="btn btn-warning">Go to Management</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../components/superadmin-footer.php'; ?>