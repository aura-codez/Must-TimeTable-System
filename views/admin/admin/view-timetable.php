<?php
session_start();
require_once '../../config/database.php';

// Fetch All Timetables
$stmt = $conn->query("SELECT t.*, u.name AS teacher_name, s.subject_name 
                      FROM timetables t 
                      JOIN users u ON t.teacher_id = u.id 
                      JOIN subjects s ON t.subject_id = s.id
                      ORDER BY t.created_at DESC");
$timetables = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../../components/admin-header.php"; ?>

<div class="container mt-5">
    <h2 class="text-warning text-center">📋 Available Timetables</h2>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>📆 Session</th>
                <th>🔢 Section</th>
                <th>📖 Subject</th>
                <th>👨‍🏫 Teacher</th>
                <th>📍 Room</th>
                <th>📅 Day</th>
                <th>⏰ Time Slot</th>
                <th>📥 Download</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timetables as $row): ?>
                <tr>
                    <td><?= $row['session'] ?></td>
                    <td><?= $row['section'] ?></td>
                    <td><?= $row['subject_name'] ?></td>
                    <td><?= $row['teacher_name'] ?></td>
                    <td><?= $row['room'] ?? 'N/A' ?></td>
                    <td><?= $row['day'] ?></td>
                    <td><?= $row['time_slot'] ?></td>
                    <td><a href="download-timetable.php?id=<?= $row['id'] ?>" class="btn btn-success">📥 PDF</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "../../components/admin-footer.php"; ?>
