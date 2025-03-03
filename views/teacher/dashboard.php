<?php include '../../components/teacher-header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            <?php include '../../components/sidebar-teacher.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2 class="text-warning text-center mb-4">Welcome, Teacher</h2>
            <p class="text-light text-center mb-5">Manage your teaching schedule and request changes if needed.</p>

            <!-- Cards Section -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 g-4">
                <div class="col">
                    <div class="card bg-dark text-light h-100 shadow-lg">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title text-warning text-center">View Timetables</h4>
                            <p class="card-text text-light text-center flex-grow-1">Check confirmed department timetables.</p>
                            <div class="text-center mt-auto">
                                <a href="/MUST-Timetable-System/views/teacher/view-timetables.php" class="btn btn-warning w-75">View Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-dark text-light h-100 shadow-lg">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title text-warning text-center">Request Change</h4>
                            <p class="card-text text-light text-center flex-grow-1">Submit timetable modification requests.</p>
                            <div class="text-center mt-auto">
                                <a href="/MUST-Timetable-System/views/teacher/request-change.php" class="btn btn-warning w-75">Request Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../components/teacher-footer.php'; ?>
