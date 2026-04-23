<?php
require_once '../data/db.php';

// Course completion: Define completion as viewing all resources at least once.
// We get all enrollments that are not yet marked as completed.
$sql_enrollments = "SELECT id, course_id, student_id FROM enrollments WHERE completed = 0";
$result_enrollments = $conn->query($sql_enrollments);

if ($result_enrollments->num_rows > 0) {
    while ($enrollment = $result_enrollments->fetch_assoc()) {
        $enrollment_id = $enrollment['id'];
        $course_id = $enrollment['course_id'];
        $student_id = $enrollment['student_id'];

        // Get total resources for this course
        $sql_total_resources = "SELECT COUNT(*) as total FROM resources WHERE course_id = ?";
        $stmt_total = $conn->prepare($sql_total_resources);
        $stmt_total->bind_param("i", $course_id);
        $stmt_total->execute();
        $total_res = $stmt_total->get_result()->fetch_assoc()['total'];
        $stmt_total->close();

        if ($total_res > 0) {
            // Get number of distinct resources viewed by this student in this course
            $sql_viewed_resources = "SELECT COUNT(DISTINCT resource_id) as viewed 
                                    FROM resource_views 
                                    WHERE student_id = ? 
                                    AND resource_id IN (SELECT id FROM resources WHERE course_id = ?)";
            $stmt_viewed = $conn->prepare($sql_viewed_resources);
            $stmt_viewed->bind_param("ii", $student_id, $course_id);
            $stmt_viewed->execute();
            $viewed_res = $stmt_viewed->get_result()->fetch_assoc()['viewed'];
            $stmt_viewed->close();

            if ($viewed_res >= $total_res) {
                // Mark as completed
                $sql_update = "UPDATE enrollments SET completed = 1 WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("i", $enrollment_id);
                $stmt_update->execute();
                $stmt_update->close();
                echo "Enrollment ID $enrollment_id marked as completed.\n";
            }
        }
    }
}

$conn->close();
?>
