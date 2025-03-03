<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $profile_pic = $_FILES['profile_pic']['name'];

    if ($profile_pic) {
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], "../public/images/" . $profile_pic);
        $stmt = $conn->prepare("UPDATE users SET name = ?, contact = ?, profile_pic = ? WHERE id = ?");
        $stmt->execute([$name, $contact, $profile_pic, $user_id]);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, contact = ? WHERE id = ?");
        $stmt->execute([$name, $contact, $user_id]);
    }

    echo "<script>alert('Profile updated successfully!'); window.location.href='../views/superadmin/profile.php';</script>";
}
?>
