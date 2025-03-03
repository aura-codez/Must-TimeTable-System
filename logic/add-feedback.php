<?php
include '../config/database.php';  

$defaultAvatar = "https://th.bing.com/th?q=Gender-Neutral+Icon+Avatar&w=120&h=120&c=1&rs=1&qlt=90&cb=1&pid=InlineBlock&mkt=en-WW&cc=PK&setlang=en&adlt=moderate&t=1&mw=247";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $feedback = htmlspecialchars($_POST['feedback']);
    $profile_pic = $defaultAvatar; // Assign default avatar

    if (!empty($name) && !empty($feedback)) {
        $stmt = $conn->prepare("INSERT INTO testimonials (name, feedback, profile_pic) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $feedback, $profile_pic])) {
            header("Location: ../index.php?success=1");
            exit();
        } else {
            die("Error inserting feedback: " . implode(" - ", $stmt->errorInfo()));
        }
    } else {
        header("Location: ../index.php?error=empty");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
