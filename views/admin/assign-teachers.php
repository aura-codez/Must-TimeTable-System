<?php include '../../components/admin-header.php'; ?>
<?php include '../../config/database.php'; ?>

<div class="container mt-5">
    <h2 class="text-center text-light">Assign Teachers to Courses</h2>
    <form method="POST" class="bg-dark p-4 rounded text-light">
        <label>Teacher:</label>
        <select name="teacher_id" class="form-control mb-2">
            <?php
            $stmt = $conn->query("SELECT * FROM users WHERE role = 'teacher'");
            while ($teacher = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$teacher['id']}'>{$teacher['name']} - {$teacher['department']}</option>";
            }
            ?>
        </select>

        <label>Course:</label>
        <select name="course_id" class="form-control mb-2">
            <?php
            $stmt = $conn->query("SELECT * FROM courses");
            while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$course['id']}'>{$course['course_name']}</option>";
            }
            ?>
        </select>

        <button type="submit" class="btn btn-warning w-100">Assign Teacher</button>
    </form>
</div>

<?php include '../../components/admin-footer.php'; ?>
