<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
require_once '../data/db.php';
$instructor_id = $_SESSION['id'];

// Fetch counts
$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses WHERE instructor_id = $instructor_id")->fetch_assoc()['count'];
$total_students = $conn->query("SELECT COUNT(DISTINCT student_id) as count FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.instructor_id = $instructor_id")->fetch_assoc()['count'];
?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4 font-weight-bold" style="color: var(--primary-teal);"><i class="fas fa-chalkboard-teacher mr-3"></i>Instructor Dashboard</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small font-weight-bold">Total Courses</h6>
                            <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);"><?php echo $total_courses; ?></h2>
                        </div>
                        <div class="card-icon"><i class="fas fa-book"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small font-weight-bold">Total Learners</h6>
                            <h2 class="mb-0 font-weight-bold" style="color: var(--primary-teal);"><?php echo $total_students; ?></h2>
                        </div>
                        <div class="card-icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Course Performance</h4>
                        <a href="courses.php" class="btn btn-sm btn-light border"><i class="fas fa-cog mr-1"></i>Manage All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="px-4 border-0">Course Title</th>
                                        <th class="border-0">Students Enrolled</th>
                                        <th class="border-0">Rating</th>
                                        <th class="px-4 border-0 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_courses = "SELECT c.id, c.title, COUNT(e.id) as student_count, 
                                                    AVG(r.rating) as avg_rating, COUNT(r.id) as review_count 
                                                    FROM courses c 
                                                    LEFT JOIN enrollments e ON c.id = e.course_id 
                                                    LEFT JOIN course_ratings r ON c.id = r.course_id 
                                                    WHERE c.instructor_id = $instructor_id 
                                                    GROUP BY c.id";
                                    $result_courses = $conn->query($sql_courses);
                                    while ($row_course = $result_courses->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='px-4 font-weight-bold' style='color: var(--dark-teal);'>" . htmlspecialchars($row_course['title']) . "</td>";
                                        echo "<td><span class='badge badge-light border'><i class='fas fa-user-graduate mr-2 text-muted'></i>" . $row_course['student_count'] . "</span></td>";
                                        echo "<td>";
                                        if ($row_course['review_count'] > 0) {
                                            echo "<span class='text-warning'>";
                                            for($i=1; $i<=5; $i++) echo ($i <= round($row_course['avg_rating'])) ? "★" : "☆";
                                            echo "</span> <small class='text-muted'>(" . number_format($row_course['avg_rating'], 1) . ")</small>";
                                        } else {
                                            echo "<span class='text-muted small'>No ratings</span>";
                                        }
                                        echo "</td>";
                                        echo "<td class='px-4 text-right'><a href='course_enrollments.php?course_id=" . $row_course['id'] . "' class='btn btn-outline-primary btn-sm'><i class='fas fa-eye mr-1'></i>Details</a></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background-color: var(--dark-teal);">
                        <h4 class="mb-0"><i class="fas fa-brain mr-2"></i>Engagement Clusters (Phase 7: K-Means)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            // Fetch cluster data
                            $clusters = [
                                'High Engagement' => ['id' => -1, 'class' => 'cluster-high', 'text' => 'Performing exceptionally well.', 'badge' => 'badge-engagement-high', 'icon' => 'fa-rocket'],
                                'Medium Engagement' => ['id' => -1, 'class' => 'cluster-medium', 'text' => 'Moderately active.', 'badge' => 'badge-engagement-medium', 'icon' => 'fa-hiking'],
                                'Low Engagement/At-Risk' => ['id' => -1, 'class' => 'cluster-low', 'text' => 'Need immediate attention.', 'badge' => 'badge-engagement-low', 'icon' => 'fa-exclamation-triangle']
                            ];

                            $avg_scores = $conn->query("SELECT uc.cluster_id, AVG(es.score) as avg_s FROM user_clusters uc JOIN engagement_scores es ON uc.user_id = es.student_id GROUP BY uc.cluster_id ORDER BY avg_s DESC");
                            
                            $rank = 0;
                            $id_map = [];
                            while($row_avg = $avg_scores->fetch_assoc()) {
                                if($rank == 0) $id_map[$row_avg['cluster_id']] = 'High Engagement';
                                elseif($rank == 1) $id_map[$row_avg['cluster_id']] = 'Medium Engagement';
                                else $id_map[$row_avg['cluster_id']] = 'Low Engagement/At-Risk';
                                $rank++;
                            }

                            foreach($id_map as $cid => $label) {
                                $count = $conn->query("SELECT COUNT(*) as c FROM user_clusters WHERE cluster_id = $cid")->fetch_assoc()['c'];
                                ?>
                                <div class="col-md-4">
                                    <div class="card cluster-card <?php echo $clusters[$label]['class']; ?> mb-3">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas <?php echo $clusters[$label]['icon']; ?> mr-2" style="font-size: 1.2rem; color: var(--primary-teal);"></i>
                                                <h6 class="mb-0 font-weight-bold"><?php echo $label; ?></h6>
                                            </div>
                                            <h3 class="mb-1"><?php echo $count; ?> Students</h3>
                                            <p class="text-muted small mb-0"><?php echo $clusters[$label]['text']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="mt-4">
                            <h5 class="font-weight-bold mb-3"><i class="fas fa-layer-group mr-2 text-muted"></i>Student Segmentation</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="border-0">Student Name</th>
                                            <th class="border-0">Engagement Level</th>
                                            <th class="border-0">Score</th>
                                            <th class="border-0">Silhouette</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql_seg = "SELECT u.first_name, u.last_name, uc.cluster_id, es.score, uc.silhouette_score 
                                                    FROM user_clusters uc 
                                                    JOIN users u ON uc.user_id = u.id 
                                                    JOIN engagement_scores es ON u.id = es.student_id 
                                                    ORDER BY es.score DESC";
                                        $res_seg = $conn->query($sql_seg);
                                        while($row_seg = $res_seg->fetch_assoc()) {
                                            $label = isset($id_map[$row_seg['cluster_id']]) ? $id_map[$row_seg['cluster_id']] : 'Unknown';
                                            $badge = $clusters[$label]['badge'];
                                            echo "<tr>";
                                            echo "<td class='font-weight-bold'>" . $row_seg['first_name'] . " " . $row_seg['last_name'] . "</td>";
                                            echo "<td><span class='badge $badge'>" . $label . "</span></td>";
                                            echo "<td>" . number_format($row_seg['score'], 1) . "%</td>";
                                            echo "<td><small class='text-muted'>" . number_format($row_seg['silhouette_score'], 3) . "</small></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>