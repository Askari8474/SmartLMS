<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'learner') {
    header("location: login.php");
    exit;
}

require_once '../data/db.php';

$course_id = $_GET['course_id'];
$student_id = $_SESSION['id'];

// Check if already enrolled
$sql_check = "SELECT id FROM enrollments WHERE course_id = ? AND student_id = ?";
if ($stmt_check = $conn->prepare($sql_check)) {
    $stmt_check->bind_param("ii", $course_id, $student_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Already enrolled, redirect to dashboard
        header("location: learner_dashboard.php");
        exit;
    }
    $stmt_check->close();
}


$sql = "INSERT INTO enrollments (course_id, student_id) VALUES (?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $course_id, $student_id);

    if ($stmt->execute()) {
        header("location: learner_dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>