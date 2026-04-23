<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!function_exists('checkCourseCompletion')) {
    function checkCourseCompletion($conn, $student_id, $course_id) {
        // Count resources
        $sql_total_res = "SELECT COUNT(*) as total FROM resources WHERE course_id = ?";
        $stmt_t = $conn->prepare($sql_total_res);
        $stmt_t->bind_param("i", $course_id);
        $stmt_t->execute();
        $total_res = $stmt_t->get_result()->fetch_assoc()['total'];
        $stmt_t->close();

        $sql_comp_res = "SELECT COUNT(DISTINCT resource_id) as completed 
                         FROM resource_completions 
                         WHERE student_id = ? AND resource_id IN (SELECT id FROM resources WHERE course_id = ?)";
        $stmt_comp = $conn->prepare($sql_comp_res);
        $stmt_comp->bind_param("ii", $student_id, $course_id);
        $stmt_comp->execute();
        $completed_res = $stmt_comp->get_result()->fetch_assoc()['completed'];
        $stmt_comp->close();

        // Count quizzes
        $sql_total_quiz = "SELECT COUNT(*) as total FROM quizzes WHERE course_id = ?";
        $stmt_tq = $conn->prepare($sql_total_quiz);
        $stmt_tq->bind_param("i", $course_id);
        $stmt_tq->execute();
        $total_quizzes = $stmt_tq->get_result()->fetch_assoc()['total'];
        $stmt_tq->close();

        $sql_comp_quiz = "SELECT COUNT(DISTINCT quiz_id) as completed 
                          FROM quiz_attempts 
                          WHERE student_id = ? AND quiz_id IN (SELECT id FROM quizzes WHERE course_id = ?)";
        $stmt_cq = $conn->prepare($sql_comp_quiz);
        $stmt_cq->bind_param("ii", $student_id, $course_id);
        $stmt_cq->execute();
        $completed_quizzes = $stmt_cq->get_result()->fetch_assoc()['completed'];
        $stmt_cq->close();

        // Update enrollment if all resources and quizzes are done
        // Ensure there's actually something to complete (total > 0)
        if (($total_res + $total_quizzes) > 0 && $completed_res >= $total_res && $completed_quizzes >= $total_quizzes) {
            $sql_upd = "UPDATE enrollments SET completed = 1 WHERE student_id = ? AND course_id = ?";
            $stmt_u = $conn->prepare($sql_upd);
            $stmt_u->bind_param("ii", $student_id, $course_id);
            $stmt_u->execute();
            $stmt_u->close();
            return true;
        }
        return false;
    }
}
?>