<!-- Bootstrap CSS (Ideally in header, but included here for completeness) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-light" style="width: 250px; position: sticky; top: 0; height: 100vh; z-index: 1000;">
    <h4 class="text-warning text-center">📋 Superadmin Menu</h4>
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="/MUST-Timetable-System/views/superadmin/dashboard.php" class="nav-link text-light">🏠 Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="/MUST-Timetable-System/views/superadmin/manage-admins.php" class="nav-link text-light">👤 Manage Admins</a>
        </li>
        <li class="nav-item">
            <a href="/MUST-Timetable-System/views/superadmin/manage-departments.php" class="nav-link text-light">🏛️ Manage Departments</a>
        </li>
     
        
        <li class="nav-item">
            <a href="/MUST-Timetable-System/views/superadmin/profile.php" class="nav-link text-light">👤 My Profile</a>
        </li>
        <li class="nav-item">
            <a href="/MUST-Timetable-System/logout.php" class="nav-link text-danger">🚪 Logout</a>
        </li>
    </ul>
</div>

<!-- Bootstrap JS (Add to footer if not present) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>