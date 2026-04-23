<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'instructor') {
    header("location: login.php");
    exit;
}

if (!hash_equals($_SESSION['csrf_token'], $_GET['csrf_token'])) {
    die("CSRF token validation failed.");
}

require_once '../data/db.php';

$course_id = $_GET['id'];

// Check for enrollments
$sql_check = "SELECT id FROM enrollments WHERE course_id = ?";
if ($stmt_check = $conn->prepare($sql_check)) {
    $stmt_check->bind_param("i", $course_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['error'] = "Cannot delete course with active enrollments.";
        header("location: courses.php");
        exit;
    }
    $stmt_check->close();
}


$sql = "DELETE FROM courses WHERE id = ? AND instructor_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $course_id, $_SESSION['id']);

    if ($stmt->execute()) {
        header("location: courses.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>