<?php
session_start();
require_once '../data/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'learner') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource_id = $_POST['resource_id'] ?? null;
    $student_id = $_SESSION['id'];

    if (!$resource_id) {
        echo json_encode(['status' => 'error', 'message' => 'Missing resource ID']);
        exit;
    }

    // 1. Mark as complete in resource_completions
    $sql = "INSERT IGNORE INTO resource_completions (student_id, resource_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $student_id, $resource_id);
    
    if ($stmt->execute()) {
        
        // 2. Fetch course_id for this resource to check overall course completion
        $sql_course = "SELECT course_id FROM resources WHERE id = ?";
        $stmt_c = $conn->prepare($sql_course);
        $stmt_c->bind_param("i", $resource_id);
        $stmt_c->execute();
        $course_data = $stmt_c->get_result()->fetch_assoc();
        $course_id = $course_data['course_id'];
        $stmt_c->close();

        // 3. Centralized check for course completion
        checkCourseCompletion($conn, $student_id, $course_id);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
}
$conn->close();
?>