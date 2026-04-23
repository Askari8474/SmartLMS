<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'instructor') {
    header("location: login.php");
    exit;
}

require_once '../data/db.php';

$quiz_id = $_GET['id'];
$course_id = $_GET['course_id'];

// First, delete questions associated with the quiz
$sql_delete_questions = "DELETE FROM questions WHERE quiz_id = ?";
if ($stmt_delete_questions = $conn->prepare($sql_delete_questions)) {
    $stmt_delete_questions->bind_param("i", $quiz_id);
    $stmt_delete_questions->execute();
    $stmt_delete_questions->close();
}

$sql = "DELETE FROM quizzes WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $quiz_id);

    if ($stmt->execute()) {
        header("location: quizzes.php?course_id=" . $course_id);
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>