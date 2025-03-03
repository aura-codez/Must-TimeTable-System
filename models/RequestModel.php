<?php
require_once '../config.php';
require_once '../config/database.php';

class RequestModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // CREATE A NEW REQUEST
    public function createRequest($requested_by, $reason, $requested_time) {
        $stmt = $this->conn->prepare("INSERT INTO requests (requested_by, reason, requested_time) VALUES (?, ?, ?)");
        return $stmt->execute([$requested_by, $reason, $requested_time]);
    }

    // GET ALL REQUESTS
    public function getAllRequests() {
        $stmt = $this->conn->query("SELECT requests.id, users.name, requests.reason, requests.requested_time, requests.status 
            FROM requests JOIN users ON requests.requested_by = users.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // UPDATE REQUEST STATUS
    public function updateRequestStatus($request_id, $status) {
        $stmt = $this->conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $request_id]);
    }
}
?>
