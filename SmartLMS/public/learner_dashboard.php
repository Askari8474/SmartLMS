<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
require_once '../data/db.php';
$student_id = $_SESSION['id'];

// Fetch counts
$enrolled_courses = $conn->query("SELECT COUNT(*) as count FROM enrollments WHERE student_id = $student_id")->fetch_assoc()['count'];
$completed_courses = $conn->query("SELECT COUNT(*) as count FROM enrollments WHERE student_id = $student_id AND completed = 1")->fetch_assoc()['count'];
?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4 font-weight-bold" style="color: var(--primary-teal);"><i class="fas fa-user-graduate mr-3"></i>Learner Dashboard</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small font-weight-bold">Enrolled Courses</h6>
                            <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);"><?php echo $enrolled_courses; ?></h2>
                        </div>
                        <div class="card-icon"><i class="fas fa-book-reader"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small font-weight-bold">Completed Courses</h6>
                            <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);"><?php echo $completed_courses; ?></h2>
                        </div>
                        <div class="card-icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <?php
            if (isset($_SESSION['quiz_result'])) {
                echo '<div class="alert alert-info shadow-sm border-0"><i class="fas fa-info-circle mr-2"></i>' . $_SESSION['quiz_result'] . '</div>';
                unset($_SESSION['quiz_result']);
            }
            ?>
            <h3 class="mb-4 font-weight-bold"><i class="fas fa-list-ul mr-2 text-muted"></i>Your Enrolled Courses</h3>
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="px-4 border-0">Course</th>
                                    <th class="border-0">Instructor</th>
                                    <th class="border-0">Progress</th>
                                    <th class="px-4 border-0 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT courses.id, courses.title, users.first_name, users.last_name FROM courses JOIN enrollments ON courses.id = enrollments.course_id JOIN users ON courses.instructor_id = users.id WHERE enrollments.student_id = ?";
                                if ($stmt = $conn->prepare($sql)) {
                                    $stmt->bind_param("i", $student_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        // Progress calculation
                                        $c_id = $row['id'];
                                        $res_total = $conn->query("SELECT COUNT(*) as total FROM resources WHERE course_id = $c_id")->fetch_assoc()['total'];
                                        $res_comp = $conn->query("SELECT COUNT(DISTINCT resource_id) as comp FROM resource_completions WHERE student_id = $student_id AND resource_id IN (SELECT id FROM resources WHERE course_id = $c_id)")->fetch_assoc()['comp'];
                                        $quiz_total = $conn->query("SELECT COUNT(*) as total FROM quizzes WHERE course_id = $c_id")->fetch_assoc()['total'];
                                        $quiz_comp = $conn->query("SELECT COUNT(DISTINCT quiz_id) as comp FROM quiz_attempts WHERE student_id = $student_id AND quiz_id IN (SELECT id FROM quizzes WHERE course_id = $c_id)")->fetch_assoc()['comp'];

                                        $total_items = $res_total + $quiz_total;
                                        $comp_items = $res_comp + $quiz_comp;
                                        $percentage = ($total_items > 0) ? round(($comp_items / $total_items) * 100) : 0;
                                        
                                        echo "<tr>";
                                        echo "<td class='px-4 font-weight-bold' style='color: var(--dark-teal);'>" . htmlspecialchars($row['title']) . "</td>";
                                        echo "<td><i class='fas fa-user-tie mr-2 text-muted'></i>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</td>";
                                        echo "<td>
                                                <div class='d-flex align-items-center'>
                                                    <div class='progress flex-grow-1 mr-2'>
                                                        <div class='progress-bar' style='width: $percentage%'></div>
                                                    </div>
                                                    <small class='font-weight-bold'>$percentage%</small>
                                                </div>
                                              </td>";
                                        echo "<td class='px-4 text-right'><a href='course_details.php?course_id=" . $row['id'] . "' class='btn btn-primary btn-sm px-3'><i class='fas fa-play mr-2'></i>Continue</a></td>";
                                        echo "</tr>";
                                    }
                                    $stmt->close();
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <h3 class="mb-4 font-weight-bold"><i class="fas fa-trophy mr-2 text-muted"></i>Recent Quiz Scores</h3>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="px-4 border-0">Quiz</th>
                                        <th class="border-0 text-center">Score</th>
                                        <th class="px-4 border-0 text-right">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_scores = "SELECT quizzes.title, quiz_attempts.score, quiz_attempts.attempted_at FROM quizzes JOIN quiz_attempts ON quizzes.id = quiz_attempts.quiz_id WHERE quiz_attempts.student_id = ? ORDER BY quiz_attempts.attempted_at DESC LIMIT 5";
                                    if ($stmt_scores = $conn->prepare($sql_scores)) {
                                        $stmt_scores->bind_param("i", $student_id);
                                        $stmt_scores->execute();
                                        $result_scores = $stmt_scores->get_result();
                                        while ($row = $result_scores->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='px-4 font-weight-bold'>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td class='text-center'><span class='badge badge-success px-3'>" . $row['score'] . "</span></td>";
                                            echo "<td class='px-4 text-right text-muted small'>" . date('M d, Y', strtotime($row['attempted_at'])) . "</td>";
                                            echo "</tr>";
                                        }
                                        $stmt_scores->close();
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <h3 class="mb-4 font-weight-bold"><i class="fas fa-chart-pie mr-2 text-muted"></i>Stats</h3>
                    <?php
                    $sql_score = "SELECT score FROM engagement_scores WHERE student_id = ?";
                    if ($stmt_score = $conn->prepare($sql_score)) {
                        $stmt_score->bind_param("i", $student_id);
                        $stmt_score->execute();
                        $result_score = $stmt_score->get_result();
                        if ($row_score = $result_score->fetch_assoc()) {
                            echo "<div class='card border-0 shadow-sm bg-light mb-3'>";
                            echo "<div class='card-body text-center'>";
                            echo "<h6 class='text-muted text-uppercase small font-weight-bold'>Overall Engagement</h6>";
                            echo "<h2 class='font-weight-bold' style='color: var(--primary-teal);'>" . number_format($row_score['score'], 1) . "%</h2>";
                            echo "</div></div>";
                        }
                        $stmt_score->close();
                    }
                    ?>
                    
                    <div class="card border-0 shadow-sm border-left-primary">
                        <div class="card-body">
                            <h6 class="font-weight-bold"><i class="fas fa-brain mr-2"></i>Status</h6>
                            <?php
                            $sql_group = "SELECT cluster_id FROM user_clusters WHERE user_id = ?";
                            if ($stmt_group = $conn->prepare($sql_group)) {
                                $stmt_group->bind_param("i", $student_id);
                                $stmt_group->execute();
                                $result_group = $stmt_group->get_result();
                                if ($row_group = $result_group->fetch_assoc()) {
                                    $cid = $row_group['cluster_id'];
                                    $avg_scores = $conn->query("SELECT cluster_id, AVG(es.score) as avg_s FROM user_clusters uc JOIN engagement_scores es ON uc.user_id = es.student_id GROUP BY cluster_id ORDER BY avg_s DESC");
                                    $rank = 0;
                                    while($r = $avg_scores->fetch_assoc()) {
                                        if($r['cluster_id'] == $cid) {
                                            $labels = [
                                                0 => ['L' => 'High', 'B' => 'badge-engagement-high', 'I' => 'fa-rocket'],
                                                1 => ['L' => 'Medium', 'B' => 'badge-engagement-medium', 'I' => 'fa-hiking'],
                                                2 => ['L' => 'Low', 'B' => 'badge-engagement-low', 'I' => 'fa-exclamation-triangle']
                                            ];
                                            $curr = $labels[$rank];
                                            echo "<span class='badge " . $curr['B'] . " py-2 px-3'><i class='fas " . $curr['I'] . " mr-2'></i>" . $curr['L'] . " Engagement</span>";
                                            break;
                                        }
                                        $rank++;
                                    }
                                }
                                $stmt_group->close();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mt-5 mb-4 font-weight-bold"><i class="fas fa-magic mr-2 text-muted"></i>Recommended for You</h3>
            <div class="row">
                <div class="col-12 mb-2"><h6 class="text-muted font-weight-bold small uppercase">Personalized Resources</h6></div>
                <?php
                $sql_res_rec = "SELECT r.id, r.title, r.resource_type, c.title as course_title 
                                FROM recommendations rec 
                                JOIN resources r ON rec.resource_id = r.id 
                                JOIN courses c ON r.course_id = c.id 
                                WHERE rec.user_id = $student_id 
                                ORDER BY rec.rank ASC LIMIT 3";
                $res_res_rec = $conn->query($sql_res_rec);
                if ($res_res_rec && $res_res_rec->num_rows > 0) {
                    while($rrec = $res_res_rec->fetch_assoc()) {
                        ?>
                        <div class="col-md-4">
                            <div class="card mb-3 border-0 shadow-sm border-left-primary">
                                <div class="card-body">
                                    <h6 class="mb-1 font-weight-bold"><?php echo htmlspecialchars($rrec['title']); ?></h6>
                                    <small class="text-muted d-block mb-3">From: <?php echo htmlspecialchars($rrec['course_title']); ?></small>
                                    <a href="view_resource.php?id=<?php echo $rrec['id']; ?>" class="btn btn-outline-primary btn-sm btn-block"><i class="fas fa-external-link-alt mr-2"></i>Explore</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>