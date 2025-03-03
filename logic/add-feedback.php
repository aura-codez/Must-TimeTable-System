<?php
include '../config/database.php';

$defaultAvatar = "https://www.gravatar.com/avatar/00000000000000000000000000000000?s=80&d=identicon";

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