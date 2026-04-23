<?php include 'includes/header.php'; ?>

<?php
require_once '../data/db.php';

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
} else {
    header('Location: learner_dashboard.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $sql_questions = "SELECT id, correct_option FROM questions WHERE quiz_id = ?";
    if ($stmt_questions = $conn->prepare($sql_questions)) {
        $stmt_questions->bind_param("i", $quiz_id);
        $stmt_questions->execute();
        $result_questions = $stmt_questions->get_result();
        $total_questions = $result_questions->num_rows;
        while ($question = $result_questions->fetch_assoc()) {
            if (isset($_POST['question_' . $question['id']]) && $_POST['question_' . $question['id']] == $question['correct_option']) {
                $score++;
            }
        }
        $stmt_questions->close();
    }

    $student_id = $_SESSION['id'];
    $sql_insert_attempt = "INSERT INTO quiz_attempts (quiz_id, student_id, score) VALUES (?, ?, ?)";
    if ($stmt_insert_attempt = $conn->prepare($sql_insert_attempt)) {
        $stmt_insert_attempt->bind_param("iii", $quiz_id, $student_id, $score);
        $stmt_insert_attempt->execute();
        $stmt_insert_attempt->close();

        // Check if this quiz was the last item for course completion
        $res_q = $conn->query("SELECT course_id FROM quizzes WHERE id = $quiz_id");
        if ($res_q && $course_row = $res_q->fetch_assoc()) {
            checkCourseCompletion($conn, $student_id, $course_row['course_id']);
        }
    }

    $_SESSION['quiz_result'] = "You scored " . $score . " out of " . $total_questions . ".";
    header("location: learner_dashboard.php");
    exit;

} else {
    $sql_quiz = "SELECT title FROM quizzes WHERE id = ?";
    if ($stmt_quiz = $conn->prepare($sql_quiz)) {
        $stmt_quiz->bind_param("i", $quiz_id);
        $stmt_quiz->execute();
        $result_quiz = $stmt_quiz->get_result();
        $quiz = $result_quiz->fetch_assoc();
        $stmt_quiz->close();
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4"><?php echo htmlspecialchars($quiz['title']); ?></h2>
        <form action="take_quiz.php?quiz_id=<?php echo $quiz_id; ?>" method="post">
            <?php
            $sql_questions = "SELECT * FROM questions WHERE quiz_id = ?";
            if ($stmt_questions = $conn->prepare($sql_questions)) {
                $stmt_questions->bind_param("i", $quiz_id);
                $stmt_questions->execute();
                $result_questions = $stmt_questions->get_result();
                while ($question = $result_questions->fetch_assoc()) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($question['question_text']) . "</h5>";
                    echo "<div class='form-check'>";
                    echo "<input class='form-check-input' type='radio' name='question_" . $question['id'] . "' value='A' required>";
                    echo "<label class='form-check-label'>" . htmlspecialchars($question['option_a']) . "</label>";
                    echo "</div>";
                    echo "<div class='form-check'>";
                    echo "<input class='form-check-input' type='radio' name='question_" . $question['id'] . "' value='B'>";
                    echo "<label class='form-check-label'>" . htmlspecialchars($question['option_b']) . "</label>";
                    echo "</div>";
                    echo "<div class='form-check'>";
                    echo "<input class='form-check-input' type='radio' name='question_" . $question['id'] . "' value='C'>";
                    echo "<label class='form-check-label'>" . htmlspecialchars($question['option_c']) . "</label>";
                    echo "</div>";
                    echo "<div class='form-check'>";
                    echo "<input class='form-check-input' type='radio' name='question_" . $question['id'] . "' value='D'>";
                    echo "<label class='form-check-label'>" . htmlspecialchars($question['option_d']) . "</label>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                $stmt_questions->close();
            }
            $conn->close();
            ?>
            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>