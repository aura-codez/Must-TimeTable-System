<?php include '../../components/student-header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-student.php'; ?>
        </div>
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center mb-4">Welcome, Student</h2>
            <p class="text-light text-center mb-5">View your department's timetable here.</p>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card bg-dark text-light p-3 rounded shadow-lg">
                        <h4 class="text-warning text-center">View Timetable</h4>
                        <p class="text-light text-center">Check your department's confirmed timetable.</p>
                        <div class="text-center">
                            <a href="view-timetables.php" class="btn btn-warning">View Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../components/student-footer.php'; ?>
