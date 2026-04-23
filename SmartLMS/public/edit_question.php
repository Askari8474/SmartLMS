<?php
require_once '../data/db.php';

// Get IDs from URL
if (isset($_GET['id']) && isset($_GET['quiz_id'])) {
    $question_id = $_GET['id'];
    $quiz_id = $_GET['quiz_id'];
} else {
    // Redirect if IDs are missing
    header('Location: courses.php');
    exit;
}

// Handle form submission for UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];

    $sql = "UPDATE questions SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option, $question_id);

        if ($stmt->execute()) {
            header("location: manage_questions.php?quiz_id=" . $quiz_id);
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
} else {
    // Fetch existing question data for GET request
    $sql = "SELECT * FROM questions WHERE id = ?";
    $question = null;
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $question = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Edit Question</h2>
        <?php if ($question): ?>
        <form action="edit_question.php?id=<?php echo $question_id; ?>&quiz_id=<?php echo $quiz_id; ?>" method="post">
            <div class="form-group">
                <label>Question</label>
                <textarea name="question_text" class="form-control" rows="3" required><?php echo htmlspecialchars($question['question_text']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Option A</label>
                <input type="text" name="option_a" class="form-control" value="<?php echo htmlspecialchars($question['option_a']); ?>" required>
            </div>
            <div class="form-group">
                <label>Option B</label>
                <input type="text" name="option_b" class="form-control" value="<?php echo htmlspecialchars($question['option_b']); ?>" required>
            </div>
            <div class="form-group">
                <label>Option C</label>
                <input type="text" name="option_c" class="form-control" value="<?php echo htmlspecialchars($question['option_c']); ?>" required>
            </div>
            <div class="form-group">
                <label>Option D</label>
                <input type="text" name="option_d" class="form-control" value="<?php echo htmlspecialchars($question['option_d']); ?>" required>
            </div>
            <div class="form-group">
                <label>Correct Option</label>
                <select name="correct_option" class="form-control" required>
                    <option value="A" <?php if ($question['correct_option'] == 'A') echo 'selected'; ?>>A</option>
                    <option value="B" <?php if ($question['correct_option'] == 'B') echo 'selected'; ?>>B</option>
                    <option value="C" <?php if ($question['correct_option'] == 'C') echo 'selected'; ?>>C</option>
                    <option value="D" <?php if ($question['correct_option'] == 'D') echo 'selected'; ?>>D</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Question</button>
            <a href="manage_questions.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-secondary">Cancel</a>
        </form>
        <?php else: ?>
            <div class="alert alert-danger">Question not found.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>