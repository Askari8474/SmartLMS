<?php
require_once '../data/db.php';

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
} else {
    // Fallback or error for when quiz_id is not in the URL
    // For example, redirect to a page that lists quizzes
    header('Location: courses.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];

    $sql = "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issssss", $quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option);

        if ($stmt->execute()) {
            header("location: manage_questions.php?quiz_id=" . $quiz_id);
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Add New Question</h2>
        <form action="add_question.php?quiz_id=<?php echo $quiz_id; ?>" method="post">
            <div class="form-group">
                <label>Question</label>
                <textarea name="question_text" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Option A</label>
                <input type="text" name="option_a" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Option B</label>
                <input type="text" name="option_b" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Option C</label>
                <input type="text" name="option_c" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Option D</label>
                <input type="text" name="option_d" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Correct Option</label>
                <select name="correct_option" class="form-control" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Question</button>
            <a href="manage_questions.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>