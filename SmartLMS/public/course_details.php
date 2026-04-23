<?php include 'includes/header.php'; ?>

<?php
require_once '../data/db.php';

// Get course_id from URL and check if the user is enrolled
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $student_id = $_SESSION['id'];

    $sql_check = "SELECT id FROM enrollments WHERE course_id = ? AND student_id = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("ii", $course_id, $student_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) {
            // Not enrolled, redirect to dashboard
            header("location: learner_dashboard.php");
            exit;
        }
        $stmt_check->close();
    }
} else {
    // Redirect if no course_id is provided
    header("location: learner_dashboard.php");
    exit;
}

// Handle Rating Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_rating'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    $stmt = $conn->prepare("REPLACE INTO course_ratings (course_id, student_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $course_id, $student_id, $rating, $comment);
    if ($stmt->execute()) {
        $rating_success = "Thank you for your rating!";
    }
    $stmt->close();
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <?php
        // Fetch Course and Instructor details
        $course_sql = "SELECT c.title, u.first_name, u.last_name, u.email, u.id as instructor_id 
                       FROM courses c 
                       JOIN users u ON c.instructor_id = u.id 
                       WHERE c.id = ?";
        if ($stmt_course = $conn->prepare($course_sql)) {
            $stmt_course->bind_param("i", $course_id);
            $stmt_course->execute();
            $course_data = $stmt_course->get_result()->fetch_assoc();
            $stmt_course->close();
        }
        ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);">Course: <?php echo htmlspecialchars($course_data['title']); ?></h2>
            <?php
            // Show average rating
            $avg_res = $conn->query("SELECT AVG(rating) as avg_r, COUNT(*) as count_r FROM course_ratings WHERE course_id = $course_id");
            $avg_data = $avg_res->fetch_assoc();
            if ($avg_data['count_r'] > 0) {
                echo "<div><span class='badge bg-warning text-dark' style='font-size: 1.1rem; padding: 10px 15px;'><i class='fas fa-star mr-1'></i> " . number_format($avg_data['avg_r'], 1) . " (" . $avg_data['count_r'] . " reviews)</span></div>";
            }
            ?>
        </div>

        <div class="row">
            <div class="col-md-8">
                <?php
                // Calculate Progress
                $res_count_sql = "SELECT COUNT(*) as total FROM resources WHERE course_id = $course_id";
                $total_res = $conn->query($res_count_sql)->fetch_assoc()['total'];
                $res_comp_sql = "SELECT COUNT(DISTINCT resource_id) as comp FROM resource_completions WHERE student_id = $student_id AND resource_id IN (SELECT id FROM resources WHERE course_id = $course_id)";
                $completed_res = $conn->query($res_comp_sql)->fetch_assoc()['comp'];
                $quiz_count_sql = "SELECT COUNT(*) as total FROM quizzes WHERE course_id = $course_id";
                $total_quizzes = $conn->query($quiz_count_sql)->fetch_assoc()['total'];
                $quiz_comp_sql = "SELECT COUNT(DISTINCT quiz_id) as comp FROM quiz_attempts WHERE student_id = $student_id AND quiz_id IN (SELECT id FROM quizzes WHERE course_id = $course_id)";
                $completed_quizzes = $conn->query($quiz_comp_sql)->fetch_assoc()['comp'];
                $total_items = $total_res + $total_quizzes;
                $completed_items = $completed_res + $completed_quizzes;
                $percentage = ($total_items > 0) ? round(($completed_items / $total_items) * 100) : 0;
                if ($percentage == 100) {
                    $conn->query("UPDATE enrollments SET completed = 1 WHERE student_id = $student_id AND course_id = $course_id");
                }
                ?>
                <div class="card mb-4 border-left-primary">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold" style="color: var(--primary-teal);">Your Learning Progress</h5>
                        <div class="progress mb-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $percentage; ?>%; background-color: var(--primary-teal);"><?php echo $percentage; ?>%</div>
                        </div>
                        <small class="text-muted"><?php echo $completed_items; ?> of <?php echo $total_items; ?> items completed</small>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Resources</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0 table-hover">
                            <thead><tr><th class="px-4 border-0">Title</th><th class="border-0">Type</th><th class="px-4 border-0 text-right">Action</th></tr></thead>
                            <tbody>
                                <?php
                                $sql_resources = "SELECT * FROM resources WHERE course_id = $course_id";
                                $result_resources = $conn->query($sql_resources);
                                while ($row = $result_resources->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='px-4 font-weight-bold'>" . htmlspecialchars($row['title']) . "</td>";
                                    echo "<td><span class='badge bg-light text-dark'>" . ucfirst($row['resource_type']) . "</span></td>";
                                    echo "<td class='px-4 text-right'><a href='view_resource.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm px-3'>View</a></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header" style="background-color: var(--dark-teal);">
                        <h5 class="mb-0"><i class="fas fa-question-circle mr-2"></i>Quizzes</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0 table-hover">
                            <thead><tr><th class="px-4 border-0">Quiz Title</th><th class="px-4 border-0 text-right">Action</th></tr></thead>
                            <tbody>
                                <?php
                                $sql_quizzes = "SELECT * FROM quizzes WHERE course_id = $course_id";
                                $result_quizzes = $conn->query($sql_quizzes);
                                while ($row = $result_quizzes->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='px-4 font-weight-bold'>" . htmlspecialchars($row['title']) . "</td>";
                                    echo "<td class='px-4 text-right'><a href='take_quiz.php?quiz_id=" . $row['id'] . "' class='btn btn-primary btn-sm px-3'>Take Quiz</a></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ratings Section -->
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="mb-0">Rate this Course</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($rating_success)) echo "<div class='alert alert-success'><i class='fas fa-check-circle mr-2'></i>$rating_success</div>"; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label class="font-weight-bold">Your Rating (1-5 Stars)</label>
                                <select name="rating" class="form-control" required style="height: auto; padding: 12px;">
                                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option value="4">⭐⭐⭐⭐ (Good)</option>
                                    <option value="3">⭐⭐⭐ (Average)</option>
                                    <option value="2">⭐⭐ (Poor)</option>
                                    <option value="1">⭐ (Very Poor)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Your Comment</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Write your feedback here..."></textarea>
                            </div>
                            <button type="submit" name="submit_rating" class="btn btn-primary shadow-sm px-4">Submit Rating</button>
                        </form>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="font-weight-bold mb-3" style="color: var(--primary-teal);">Student Reviews</h4>
                    <hr>
                    <?php
                    $sql_reviews = "SELECT r.*, u.first_name, u.last_name FROM course_ratings r JOIN users u ON r.student_id = u.id WHERE r.course_id = $course_id ORDER BY r.created_at DESC";
                    $res_reviews = $conn->query($sql_reviews);
                    if ($res_reviews->num_rows > 0) {
                        while($rev = $res_reviews->fetch_assoc()) {
                            echo "<div class='card mb-3 border-0 shadow-sm'>";
                            echo "<div class='card-body'>";
                            echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                            echo "<strong style='color: var(--dark-teal);'>" . $rev['first_name'] . " " . $rev['last_name'] . "</strong>";
                            echo "<span class='text-warning'>";
                            for($i=0; $i<$rev['rating']; $i++) echo "★";
                            echo "</span></div>";
                            echo "<p class='mb-1'>" . htmlspecialchars($rev['comment']) . "</p>";
                            echo "<small class='text-muted'><i class='far fa-clock mr-1'></i>" . date('M d, Y', strtotime($rev['created_at'])) . "</small>";
                            echo "</div></div>";
                        }
                    } else {
                        echo "<div class='alert alert-light border text-center p-4'>No reviews yet. Be the first to rate!</div>";
                    }
                    ?>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card mb-4 border-0">
                    <div class="card-header" style="background-color: #343a40;">
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i>Instructor</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-user-circle fa-4x text-muted mb-2"></i>
                            <h5 class="font-weight-bold mb-0"><?php echo htmlspecialchars($course_data['first_name'] . ' ' . $course_data['last_name']); ?></h5>
                            <small class="text-muted"><?php echo htmlspecialchars($course_data['email']); ?></small>
                        </div>
                        <hr>
                        <a href="instructor_courses.php?instructor_id=<?php echo $course_data['instructor_id']; ?>" class="btn btn-outline-primary btn-block">Explore more courses</a>
                    </div>
                </div>

                <div class="card bg-dark text-white border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h5>Learning Tip</h5>
                        <p class="small mb-0 opacity-75">Take regular breaks and participate in quizzes to improve your retention and engagement score!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>