<?php
include 'config.php';
include 'config/database.php';
?>
<?php include 'components/guest-header.php'; ?>
<?php include 'components/popup-notification.php'; ?>

<!-- Hero Banner with Background Image -->
<div class="hero-banner text-center text-white d-flex align-items-end justify-content-center"
    style="background: url('public/images/MUST-Hero-Banner.jpg') no-repeat center center/cover; height: 80vh;">
    <div style="background: rgba(0, 0, 0, 0.6); padding: 20px; border-radius: 10px;">
        <h1 class="fw-bold text-warning">Welcome to MUST Timetable Management System</h1>
        <p class="fs-5 text-light">Automating timetable scheduling for Mirpur University of Science and Technology.</p>
        <a href="views/guest/login.php" class="btn btn-warning">Login</a>
        <a href="views/guest/register.php" class="btn btn-outline-light">Register</a>
    </div>
</div>

<!-- About Section -->
<section id="about" class="container text-light p-5 mt-5 rounded" style="background-color: #222;">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-warning">About MUST Timetable System</h2>
            <p>
                The MUST Timetable System helps students, teachers, and administrators efficiently manage university
                schedules,
                ensuring conflict-free classes and seamless timetable creation.
            </p>
        </div>
        <div class="col-md-6 text-center">
            <img src="public/images/about-image.jpg" alt="About Image" class="img-fluid rounded shadow-lg">
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="container text-light p-5 mt-5 rounded" style="background-color: #1c1c1c;">
    <h2 class="text-center fw-bold text-warning">Our Services</h2>
    <div class="row text-center mt-4">
        <div class="col-md-4">
            <div class="p-4 bg-dark rounded shadow">
                <h4 class="text-warning">Automated Timetable</h4>
                <p>Generate conflict-free schedules for students and teachers.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-dark rounded shadow">
                <h4 class="text-warning">Teacher & Room Management</h4>
                <p>Manage teacher availability and classroom assignments seamlessly.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-dark rounded shadow">
                <h4 class="text-warning">Subscription & Payments</h4>
                <p>Access detailed timetables for just 500 PKR per semester.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="container text-light p-4 mt-4 bg-dark rounded">
    <h2 class="text-center">What People Say</h2>

    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $stmt = $conn->query("SELECT name, feedback, profile_pic FROM testimonials ORDER BY created_at DESC LIMIT 5");
            $first = true;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
                echo '<div class="card bg-secondary text-white p-3 text-center">';
                echo '<img src="' . $row['profile_pic'] . '" alt="User Avatar" class="rounded-circle mx-auto d-block" width="80">';
                echo '<p class="mt-3">"' . $row['feedback'] . '"</p>';
                echo '<h4>- ' . $row['name'] . '</h4>';
                echo '</div></div>';
                $first = false;
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>










    <!-- Add Feedback Form -->
    <div class="mt-4">
        <h3 class="text-center">Submit Your Feedback</h3>
        <form method="POST" action="logic/add-feedback.php">
            <input type="text" name="name" class="form-control mb-2" placeholder="Your Name" required>
            <textarea name="feedback" class="form-control mb-2" placeholder="Your Feedback" required></textarea>
            <button type="submit" class="btn btn-warning">Submit</button>
        </form>
    </div>


</section>


<!-- Contact Section -->
<section id="contact" class="container text-light p-5 mt-5 rounded" style="background-color: #1c1c1c;">
    <h2 class="text-center fw-bold text-warning">Contact MUST University</h2>
    <p class="text-center">📧 Email: info@must.edu.pk | 📞 Phone: +92-XXXX-XXXXXX</p>
</section>

<!-- Footer -->
<footer class="text-center text-light p-4 mt-5" style="background-color: #111;">
    <p>Follow us:
        <a href="https://www.instagram.com/must_university-officially?igsh=MWp0ZXp5dHFuZWox"
            class="text-decoration-none text-warning">Instagram</a> |
        <a href="https://www/facebook.com/profile.php?id=100079274237254"
            class="text-decoration-none text-warning">Facebook</a> |
        <a href="https://www.must.edu.pk" class="text-decoration-none text-warning">Official Website</a>
    </p>
    <p>Created by Muqadas Meherban & Ahmed Ali (Session 22-25, Mirpur University of Science and Technology).</p>
</footer>