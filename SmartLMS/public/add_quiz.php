<?php include 'includes/header.php'; ?>

<?php
require_once '../data/db.php';

// Get course_id from the URL
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} else {
    header('Location: courses.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];

    $sql = "INSERT INTO quizzes (course_id, title) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $course_id, $title);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header('Location: quizzes.php?course_id=' . $course_id);
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);">
                <i class="fas fa-plus-circle mr-3"></i>Add New Quiz
            </h2>
            <a href="quizzes.php?course_id=<?php echo $course_id; ?>" class="btn btn-light border">
                <i class="fas fa-arrow-left mr-2"></i>Back to Quizzes
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger shadow-sm border-0">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm col-md-8 mx-auto">
            <div class="card-body py-5">
                <form action="add_quiz.php?course_id=<?php echo $course_id; ?>" method="post">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color: var(--dark-teal);">Quiz Title</label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g., Final Assessment" required>
                        <small class="text-muted">Give your quiz a descriptive name for students.</small>
                    </div>
                    
                    <div class="d-flex justify-content-between pt-3">
                        <a href="quizzes.php?course_id=<?php echo $course_id; ?>" class="btn btn-light border px-4">
                            <i class="fas fa-times mr-2 text-danger"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="fas fa-save mr-2"></i>Create Quiz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
