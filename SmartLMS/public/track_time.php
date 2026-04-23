<?php
require_once '../data/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$view_id = $data['view_id'];

if ($view_id) {
    $end_time = date("Y-m-d H:i:s");

    // Get start time
    $sql_start = "SELECT start_time FROM resource_views WHERE id = ?";
    if ($stmt_start = $conn->prepare($sql_start)) {
        $stmt_start->bind_param("i", $view_id);
        $stmt_start->execute();
        $result_start = $stmt_start->get_result();
        $view = $result_start->fetch_assoc();
        $start_time = new DateTime($view['start_time']);
        $end_time_obj = new DateTime($end_time);
        $time_spent = $end_time_obj->getTimestamp() - $start_time->getTimestamp();
        $stmt_start->close();
    }

    $sql = "UPDATE resource_views SET end_time = ?, time_spent = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sii", $end_time, $time_spent, $view_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
?>