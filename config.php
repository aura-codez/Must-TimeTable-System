<?php
session_start();

// Define Base URL
define('BASE_URL', 'http://localhost/MUST-Timetable-System/');

// Check if User is Logged In
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect if Not Logged In
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "views/guest/login.php");
        exit();
    }
}

// Redirect if Logged In
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header("Location: " . BASE_URL . "views/student/dashboard.php");
        exit();
    }
}
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

?><?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';
?>


