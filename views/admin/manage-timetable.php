<?php include '../../components/admin-header.php'; ?>
<?php include '../../config/database.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Manage Timetable</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Course</th>
                <th>Teacher</th>
                <th>Room</th>
                <th>Day</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $stmt = $conn->query("SELECT t.*, c.course_name, r.room_name, u.name AS teacher_name 
                                  FROM timetable t 
                                  JOIN courses c ON t.course_id = c.course_id 
                                  JOIN rooms r ON t.room_id = r.room_id 
                                  JOIN teachers u ON t.teacher_id = u.teacher_id");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['course_name']}</td>
                        <td>{$row['teacher_name']}</td>
                        <td>{$row['room_name']}</td>
                        <td>{$row['day']}</td>
                        <td>{$row['start_time']} - {$row['end_time']}</td>
                        <td>
                            <a href='edit-timetable.php?id={$row['timetable_id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='../controllers/AdminController.php?deleteTimetable={$row['timetable_id']}' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include '../../components/admin-footer.php'; ?>