<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'instructor') {
    header("location: login.php");
    exit;
}

require_once '../data/db.php';

$question_id = $_GET['id'];
$quiz_id = $_GET['quiz_id'];

$sql = "DELETE FROM questions WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        header("location: manage_questions.php?quiz_id=" . $quiz_id);
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>