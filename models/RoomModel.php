<?php
include '../config/database.php';

class RoomModel {
    public static function addRoom($roomName, $capacity) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO rooms (room_name, capacity) VALUES (?, ?)");
        return $stmt->execute([$roomName, $capacity]);
    }

    public static function getAllRooms() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM rooms");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
