<?php include '../../components/teacher-header.php'; ?>
<?php include '../../config/database.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Your Assigned Timetable</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Course</th>
                <th>Room</th>
                <th>Day</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $teacherId = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT t.*, c.course_name, r.room_name 
                                    FROM timetable t 
                                    JOIN courses c ON t.course_id = c.course_id 
                                    JOIN rooms r ON t.room_id = r.room_id 
                                    WHERE t.teacher_id = ?");
            $stmt->execute([$teacherId]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['course_name']}</td>
                        <td>{$row['room_name']}</td>
                        <td>{$row['day']}</td>
                        <td>{$row['start_time']} - {$row['end_time']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include '../../components/teacher-footer.php'; ?>
