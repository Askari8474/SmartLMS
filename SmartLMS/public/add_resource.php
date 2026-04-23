<?php
require_once '../data/db.php';

// Get course_id from the URL. It's needed for both GET (displaying the form) and POST (processing it).
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} else {
    // Or handle error appropriately, maybe redirect to a course list page
    $course_id = 0; 
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $resource_type = $_POST['resource_type'];
    $tags = $_POST['tags'];
    $path = null;
    $link = null;

    if ($resource_type == 'file') {
        if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/"; // Web-accessible path
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $path = $target_file;
            }
        }
    } else { // 'link'
        $link = $_POST['link'];
    }

    $sql = "INSERT INTO resources (course_id, title, resource_type, path, link, tags) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssss", $course_id, $title, $resource_type, $path, $link, $tags);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();

    // Redirect back to the resources list for the course
    header('Location: resources.php?course_id=' . $course_id);
    exit;
}

?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Add New Resource</h2>
        <form action="add_resource.php?course_id=<?php echo $course_id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Resource Type</label>
                <select name="resource_type" id="resource_type" class="form-control" required>
                    <option value="file">File</option>
                    <option value="link">Link</option>
                </select>
            </div>
            <div id="file-upload" class="form-group">
                <label>Upload File</label>
                <input type="file" name="fileToUpload" class="form-control">
            </div>
            <div id="link-upload" class="form-group" style="display: none;">
                <label>External Link</label>
                <input type="text" name="link" class="form-control">
            </div>
            <div class="form-group">
                <label>Tags (comma-separated)</label>
                <input type="text" name="tags" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Resource</button>
            <a href="resources.php?course_id=<?php echo $course_id; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    document.getElementById('resource_type').addEventListener('change', function () {
        if (this.value == 'file') {
            document.getElementById('file-upload').style.display = 'block';
            document.getElementById('link-upload').style.display = 'none';
        } else {
            document.getElementById('file-upload').style.display = 'none';
            document.getElementById('link-upload').style.display = 'block';
        }
    });
</script>

<?php include 'includes/footer.php'; ?>