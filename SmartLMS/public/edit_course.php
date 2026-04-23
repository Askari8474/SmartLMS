<?php include 'includes/header.php'; ?>

<?php
require_once '../data/db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }

    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];

    $sql = "UPDATE courses SET title = ?, description = ?, tags = ? WHERE id = ? AND instructor_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssii", $title, $description, $tags, $course_id, $_SESSION['id']);

        if ($stmt->execute()) {
            header("location: courses.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
} else {
    // Fetch course data for the form
    $course_id = $_GET['id'];
    $sql = "SELECT * FROM courses WHERE id = ? AND instructor_id = ?";
    $course = null; 
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $course_id, $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Edit Course</h2>
        <?php if ($course): ?>
        <form action="edit_course.php?id=<?php echo $course_id; ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($course['title']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Tags (comma-separated)</label>
                <input type="text" name="tags" class="form-control" value="<?php echo htmlspecialchars($course['tags'] ?? ''); ?>" placeholder="e.g., web, php, ai">
            </div>
            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="courses.php" class="btn btn-secondary">Cancel</a>
        </form>
        <?php else: ?>
            <div class="alert alert-danger">Course not found or you do not have permission to edit it.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>