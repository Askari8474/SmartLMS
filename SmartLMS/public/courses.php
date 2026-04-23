<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);">Course Management</h2>
            <a href="add_course.php" class="btn btn-success shadow-sm">
                <i class="fas fa-plus-circle mr-2"></i>Create New Course
            </a>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger shadow-sm border-0">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 border-0">Course Details</th>
                                <th class="border-0">Statistics & Management</th>
                                <th class="px-4 border-0 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once '../data/db.php';
                            $instructor_id = $_SESSION['id'];
                            $sql = "SELECT * FROM courses WHERE instructor_id = ? ORDER BY created_at DESC";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("i", $instructor_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Get student count for badge
                                        $c_id = $row['id'];
                                        $count_sql = "SELECT COUNT(*) as count FROM enrollments WHERE course_id = $c_id";
                                        $stu_count = $conn->query($count_sql)->fetch_assoc()['count'];

                                        echo '<tr>';
                                        echo '<td class="px-4 py-4">';
                                        echo '<h6 class="font-weight-bold mb-1" style="color: var(--dark-teal);">' . htmlspecialchars($row['title']) . '</h6>';
                                        echo '<p class="text-muted small mb-0">' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>';
                                        if(!empty($row['tags'])) {
                                            echo '<div class="mt-2">';
                                            $tags = explode(',', $row['tags']);
                                            foreach($tags as $tag) {
                                                echo '<span class="badge badge-light border text-muted mr-1">' . trim($tag) . '</span>';
                                            }
                                            echo '</div>';
                                        }
                                        echo '</td>';
                                        
                                        echo '<td class="py-4">';
                                        echo '<div class="mb-2"><span class="badge badge-primary px-3 py-2"><i class="fas fa-users mr-2"></i>' . $stu_count . ' Enrolled</span></div>';
                                        echo '<div class="btn-group">';
                                        echo '<a href="resources.php?course_id=' . $row['id'] . '" class="btn btn-outline-info btn-sm mr-1"><i class="fas fa-folder-open mr-1"></i>Resources</a>';
                                        echo '<a href="quizzes.php?course_id=' . $row['id'] . '" class="btn btn-outline-warning btn-sm"><i class="fas fa-tasks mr-1"></i>Quizzes</a>';
                                        echo '</div>';
                                        echo '</td>';

                                        echo '<td class="px-4 py-4 text-right">';
                                        echo '<div class="btn-group">';
                                        echo '<a href="course_enrollments.php?course_id=' . $row['id'] . '" class="btn btn-light btn-sm mr-1 border" title="View Enrollments"><i class="fas fa-user-friends"></i></a> ';
                                        echo '<a href="edit_course.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm mr-1" title="Edit Course"><i class="fas fa-edit"></i></a> ';
                                        echo '<a href="delete_course.php?id=' . $row['id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '" class="btn btn-danger btn-sm" title="Delete Course" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash-alt"></i></a> ';
                                        echo '</div>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="3" class="text-center py-5 text-muted">You haven\'t created any courses yet.</td></tr>';
                                }
                                $stmt->close();
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>