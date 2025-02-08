<?php include '../../components/student-header.php'; ?>
<?php include '../../config/database.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Your Timetable</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Course</th>
                <th>Teacher</th>
                <th>Room</th>
                <th>Day</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $studentId = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT t.*, c.course_name, r.room_name, u.name AS teacher_name 
                                    FROM timetable t 
                                    JOIN courses c ON t.course_id = c.course_id 
                                    JOIN rooms r ON t.room_id = r.room_id 
                                    JOIN teachers u ON t.teacher_id = u.teacher_id
                                    WHERE t.course_id IN (SELECT course_id FROM student_courses WHERE student_id = ?)");
            $stmt->execute([$studentId]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['course_name']}</td>
                        <td>{$row['teacher_name']}</td>
                        <td>{$row['room_name']}</td>
                        <td>{$row['day']}</td>
                        <td>{$row['start_time']} - {$row['end_time']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include '../../components/student-footer.php'; ?>
