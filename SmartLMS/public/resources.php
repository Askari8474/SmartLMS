<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
$course_id = $_GET['course_id'];
?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Resource Management for Course ID: <?php echo $course_id; ?></h2>

        <div class="mb-4">
            <a href="add_resource.php?course_id=<?php echo $course_id; ?>" class="btn btn-success">Add New Resource</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../data/db.php';
                $sql = "SELECT * FROM resources WHERE course_id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $course_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['title'] . '</td>';
                        echo '<td>' . $row['resource_type'] . '</td>';
                        echo '<td>' . $row['tags'] . '</td>';
                        echo '<td>';
                        if ($row['resource_type'] == 'file') {
                            echo '<a href="' . $row['path'] . '" class="btn btn-info btn-sm" target="_blank">View</a> ';
                        } else {
                            echo '<a href="' . $row['link'] . '" class="btn btn-info btn-sm" target="_blank">Open Link</a> ';
                        }
                        echo '<a href="delete_resource.php?id=' . $row['id'] . '&course_id=' . $course_id . '" class="btn btn-danger btn-sm">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    $stmt->close();
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>