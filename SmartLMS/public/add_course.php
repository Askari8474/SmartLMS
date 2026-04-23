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

    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $instructor_id = $_SESSION['id'];

    $sql = "INSERT INTO courses (title, description, tags, instructor_id) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $title, $description, $tags, $instructor_id);

        if ($stmt->execute()) {
            header("location: courses.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Add New Course</h2>
        <form action="add_course.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label>Tags (comma-separated, e.g., web, php, ai)</label>
                <input type="text" name="tags" class="form-control" placeholder="Enter tags here...">
            </div>
            <button type="submit" class="btn btn-primary">Add Course</button>
            <a href="courses.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>