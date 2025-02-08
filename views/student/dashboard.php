<?php include '../../components/student-header.php'; ?>
<div class="container mt-5">
    <h2 class="text-center">Student Dashboard</h2>
    <div class="row">
        <div class="col-md-4">
            <a href="view-timetable.php" class="btn btn-primary btn-block">View Timetable</a>
        </div>
        <div class="col-md-4">
            <a href="request-change.php" class="btn btn-warning btn-block">Request Timetable Change</a>
        </div>
        <div class="col-md-4">
            <a href="subscribe.php" class="btn btn-success btn-block">Subscribe for Full Access</a>
        </div>
        <div class="col-md-4">
    <a href="../../logic/generate-pdf.php" class="btn btn-info btn-block">Download Timetable PDF</a>
</div>
<div class="col-md-4">
    <a href="../../logic/whatsapp-share.php" class="btn btn-success btn-block">Share on WhatsApp</a>
</div>

    </div>
</div>
<?php include '../../components/student-footer.php'; ?>
