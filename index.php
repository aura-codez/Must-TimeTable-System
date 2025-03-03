<?php 
include 'config.php'; 
include 'config/database.php'; 
?>

<?php include 'components/guest-header.php'; ?>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php
require_once 'config/database.php';

try {
    $stmt = $conn->query("SELECT name, feedback, profile_pic FROM testimonials ORDER BY created_at DESC LIMIT 5");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $testimonials = [];
    error_log("Database Error: " . $e->getMessage());
}
?>

<!-- Custom Styling -->
<style>
    body {
        background-color: #1a1a1a; /* Dark Theme */
        color: white;
    }
    .section {
        padding: 30px 20px;
        background-color: #222;
        margin: 20px 10px; /* Less Margin for Compact Look */
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
    .text-primary-must {
        color: #004F9F !important;
    }
    .bg-primary-must {
        background-color: #004F9F !important;
    }
    .btn-warning:hover {
        background-color: #e0a800 !important;
        color: black;
        transition: 0.3s ease-in-out;
    }
    .shadow-lg:hover {
        box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.2);
        transition: 0.3s ease-in-out;
    }
</style>

<!-- Hero Banner -->
<div class="hero-banner text-center text-white d-flex align-items-center justify-content-center" 
     style="background: url('public/images/MUST-Hero-Banner.jpg') no-repeat center center/cover; height: 75vh;">
    <div style="background: rgba(0, 0, 0, 0.6); padding: 20px; border-radius: 10px;">
        <h1 class="fw-bold text-warning">Welcome to MUST Timetable Management System</h1>
        <p class="fs-5 text-light">Automating timetable scheduling for Mirpur University of Science and Technology.</p>
        <a href="views/guest/login.php" class="btn btn-warning">Login</a>
        <a href="views/guest/register.php" class="btn btn-outline-light">Register</a>
    </div>
</div>

<!-- About Section -->
<section id="about" class="container-md text-light section">
    <div class="row align-items-center">
        <div class="col-md-6 text-center">
            <h2 class="fw-bold text-warning">About MUST Timetable System</h2>
            <p>
                The MUST Timetable System helps students, teachers, and administrators efficiently manage university schedules,
                ensuring conflict-free classes and seamless timetable creation.
            </p>
        </div>
        <div class="col-md-6 text-center">
            <img src="public/images/MUST-ABOUT.jpg" alt="About MUST Timetable System" class="img-fluid rounded shadow-lg w-100">
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="container text-light section">
    <h2 class="text-center fw-bold text-warning">Our Services</h2>
    <div class="row text-center mt-3">
        <div class="col-md-4">
            <div class="p-3 bg-dark rounded shadow-lg">
                <h4 class="text-warning">Automated Timetable</h4>
                <p>Generate conflict-free schedules for students and teachers.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-dark rounded shadow-lg">
                <h4 class="text-warning">Teacher & Room Management</h4>
                <p>Manage teacher availability and classroom assignments seamlessly.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-dark rounded shadow-lg">
                <h4 class="text-warning">Download Timetable</h4>
                <p>Download your timetable as a PDF file for easy access.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="container text-light section bg-dark">
    <h2 class="text-center">What People Say</h2>
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $stmt = $conn->query("SELECT name, feedback, profile_pic FROM testimonials ORDER BY created_at DESC LIMIT 5");
            $first = true;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
                echo '<div class="card bg-secondary text-white p-3 text-center shadow-lg">';
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
</section>

<!-- Feedback Section -->
<section id="feedback" class="container text-light section">
    <h2 class="text-center fw-bold text-warning">Submit Your Feedback</h2>
    <form method="POST" action="logic/add-feedback.php">
        <input type="text" name="name" class="form-control mb-2" placeholder="Your Name" required>
        <textarea name="feedback" class="form-control mb-2" placeholder="Your Feedback" required></textarea>
        <button type="submit" class="btn btn-warning">Submit</button>
    </form>
</section>

<!-- Contact Section -->
<section id="contact" class="container text-light section">
    <h2 class="text-center fw-bold text-warning">Contact MUST University</h2>
    <p class="text-center">📧 Email: info@must.edu.pk | 📞 Phone: +92-XXXX-XXXXXX</p>
</section>

<!-- Footer -->
<?php include 'components/guest-footer.php'; ?>
