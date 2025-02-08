<?php include '../../components/admin-header.php'; ?>
<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>
    <div class="row">
        <div class="col-md-4">
            <a href="manage-timetable.php" class="btn btn-primary btn-block">Manage Timetable</a>
        </div>
        <div class="col-md-4">
            <a href="manage-users.php" class="btn btn-primary btn-block">Manage Users</a>
        </div>
        <div class="col-md-4">
            <a href="view-payments.php" class="btn btn-primary btn-block">View Payments</a>
        </div>
        <div class="col-md-4">
    <a href="../../logic/generate-pdf.php" class="btn btn-info btn-block">Download Timetable PDF</a>
</div>
<div class="col-md-4">
    <a href="../../logic/whatsapp-share.php" class="btn btn-success btn-block">Share on WhatsApp</a>
</div>

    </div>
</div>
<?php include '../../components/admin-footer.php'; ?>
