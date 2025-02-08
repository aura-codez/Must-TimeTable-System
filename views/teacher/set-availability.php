<?php include '../../components/teacher-header.php'; ?>
<?php include '../../config/database.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacherId = $_SESSION['user_id'];
    $availability = implode(", ", $_POST['availability']);

    $stmt = $conn->prepare("UPDATE teachers SET availability = ? WHERE teacher_id = ?");
    $stmt->execute([$availability, $teacherId]);

    echo "<div class='alert alert-success text-center'>Availability updated successfully!</div>";
}
?>

<div class="container mt-5">
    <h2 class="text-center">Set Availability</h2>
    <form method="POST">
        <label>Select Available Days:</label><br>
        <input type="checkbox" name="availability[]" value="Monday"> Monday
        <input type="checkbox" name="availability[]" value="Tuesday"> Tuesday
        <input type="checkbox" name="availability[]" value="Wednesday"> Wednesday
        <input type="checkbox" name="availability[]" value="Thursday"> Thursday
        <input type="checkbox" name="availability[]" value="Friday"> Friday
        <br><br>
        <button type="submit" class="btn btn-success">Update Availability</button>
    </form>
</div>

<?php include '../../components/teacher-footer.php'; ?>
