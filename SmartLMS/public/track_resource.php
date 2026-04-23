<?php
session_start();
require_once '../data/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource_id = $_POST['resource_id'] ?? null;
    $time_spent = $_POST['time_spent'] ?? null;
    $student_id = $_SESSION['id'] ?? null;

    if ($resource_id && $time_spent && $student_id) {
        $sql = "INSERT INTO resource_views (resource_id, student_id, time_spent) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iii", $resource_id, $student_id, $time_spent);
            $stmt->execute();
            $stmt->close();
        }
    }
}
$conn->close();
?>
