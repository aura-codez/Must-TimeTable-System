<?php include '../../components/superadmin-header.php'; ?>
<?php include '../../config/database.php'; ?>

<?php
session_start();
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="text-center text-warning">My Profile</h2>
    <form method="POST" action="../../logic/update-profile.php" enctype="multipart/form-data" class="bg-dark p-4 rounded text-light">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" class="form-control mb-2" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control mb-2" readonly>

        <label>Contact Info:</label>
        <input type="text" name="contact" value="<?php echo $user['contact']; ?>" class="form-control mb-2">

        <label>Profile Picture:</label>
        <input type="file" name="profile_pic" class="form-control mb-2">

        <button type="submit" class="btn btn-warning w-100">Update Profile</button>
    </form>
</div>

<?php include '../../components/superadmin-footer.php'; ?>
