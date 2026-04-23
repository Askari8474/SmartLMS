<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'instructor') {
    header("location: login.php");
    exit;
}

require_once '../data/db.php';

$resource_id = $_GET['id'];
$course_id = $_GET['course_id'];

// Get the resource path to delete the file
$sql_path = "SELECT path FROM resources WHERE id = ?";
if ($stmt_path = $conn->prepare($sql_path)) {
    $stmt_path->bind_param("i", $resource_id);
    $stmt_path->execute();
    $result_path = $stmt_path->get_result();
    $resource = $result_path->fetch_assoc();
    if ($resource['path']) {
        unlink($resource['path']);
    }
    $stmt_path->close();
}

$sql = "DELETE FROM resources WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $resource_id);

    if ($stmt->execute()) {
        header("location: resources.php?course_id=" . $course_id);
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>