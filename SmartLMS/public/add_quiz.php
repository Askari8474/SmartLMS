<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Add New Quiz</h2>
        <form action="add_quiz.php?course_id=<?php echo $course_id; ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Quiz</button>
            <a href="quizzes.php?course_id=<?php echo $course_id; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>