<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
if (!isset($_GET['course_id'])) {
    header('Location: courses.php');
    exit;
}
$course_id = $_GET['course_id'];
require_once '../data/db.php';

// Get course title for breadcrumb
$c_stmt = $conn->prepare("SELECT title FROM courses WHERE id = ?");
$c_stmt->bind_param("i", $course_id);
$c_stmt->execute();
$course_title = $c_stmt->get_result()->fetch_assoc()['title'];
$c_stmt->close();
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);">
                    <i class="fas fa-tasks mr-3"></i>Quiz Management
                </h2>
                <small class="text-muted">Managing quizzes for: <strong><?php echo htmlspecialchars($course_title); ?></strong></small>
            </div>
            <div class="btn-group">
                <a href="courses.php" class="btn btn-light border mr-2">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Courses
                </a>
                <a href="add_quiz.php?course_id=<?php echo $course_id; ?>" class="btn btn-success shadow-sm">
                    <i class="fas fa-plus-circle mr-2"></i>Add New Quiz
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 border-0">Quiz Title</th>
                                <th class="border-0 text-center">Questions</th>
                                <th class="px-4 border-0 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT q.*, (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) as q_count 
                                    FROM quizzes q WHERE course_id = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("i", $course_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td class="px-4 py-3 font-weight-bold" style="color: var(--dark-teal);">' . htmlspecialchars($row['title']) . '</td>';
                                        echo '<td class="text-center"><span class="badge badge-primary py-2 px-3"><i class="fas fa-question-circle mr-2"></i>' . $row['q_count'] . '</span></td>';
                                        echo '<td class="px-4 text-right">';
                                        echo '<div class="btn-group">';
                                        echo '<a href="manage_questions.php?quiz_id=' . $row['id'] . '" class="btn btn-primary btn-sm mr-2 shadow-sm"><i class="fas fa-edit mr-2"></i>Manage Questions</a> ';
                                        echo '<a href="delete_quiz.php?id=' . $row['id'] . '&course_id=' . $course_id . '" class="btn btn-outline-danger btn-sm" onclick="return confirm(\'Delete this quiz and all its questions?\')"><i class="fas fa-trash-alt"></i></a>';
                                        echo '</div>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="3" class="text-center py-5 text-muted">No quizzes found for this course. Click "Add New Quiz" to get started.</td></tr>';
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
