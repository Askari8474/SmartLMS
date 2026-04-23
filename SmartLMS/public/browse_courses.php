<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4 font-weight-bold" style="color: var(--primary-teal);"><i class="fas fa-search-plus mr-3"></i>Browse Courses</h2>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 border-0">Course Name</th>
                                <th class="border-0">Instructor</th>
                                <th class="border-0">Rating</th>
                                <th class="px-4 border-0 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once '../data/db.php';
                            $student_id = $_SESSION['id'];

                            // Get all enrolled course IDs for the student
                            $enrolled_courses = [];
                            $sql_enrolled = "SELECT course_id FROM enrollments WHERE student_id = ?";
                            if ($stmt_enrolled = $conn->prepare($sql_enrolled)) {
                                $stmt_enrolled->bind_param("i", $student_id);
                                $stmt_enrolled->execute();
                                $result_enrolled = $stmt_enrolled->get_result();
                                while ($enrolled_row = $result_enrolled->fetch_assoc()) {
                                    $enrolled_courses[] = $enrolled_row['course_id'];
                                }
                                $stmt_enrolled->close();
                            }

                            $sql = "SELECT c.id, c.title, u.first_name, u.last_name, c.tags,
                                           AVG(r.rating) as avg_rating, COUNT(r.id) as review_count 
                                    FROM courses c 
                                    JOIN users u ON c.instructor_id = u.id 
                                    LEFT JOIN course_ratings r ON c.id = r.course_id 
                                    GROUP BY c.id";
                            $result = $conn->query($sql);

                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-4 py-3'>";
                                echo "<div class='font-weight-bold' style='color: var(--dark-teal);'>" . htmlspecialchars($row['title']) . "</div>";
                                if(!empty($row['tags'])) {
                                    echo "<div class='mt-1'>";
                                    $tags = explode(',', $row['tags']);
                                    foreach($tags as $tag) {
                                        echo "<span class='badge badge-light border text-muted mr-1 small' style='font-size: 0.7rem;'>" . trim($tag) . "</span>";
                                    }
                                    echo "</div>";
                                }
                                echo "</td>";
                                echo "<td><i class='fas fa-user-tie mr-2 text-muted'></i>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</td>";
                                echo "<td>";
                                if ($row['review_count'] > 0) {
                                    echo "<span class='text-warning'>";
                                    for($i=1; $i<=5; $i++) {
                                        echo ($i <= round($row['avg_rating'])) ? "★" : "☆";
                                    }
                                    echo "</span> <small class='text-muted'>(" . number_format($row['avg_rating'], 1) . ")</small>";
                                } else {
                                    echo "<small class='text-muted'>No ratings yet</small>";
                                }
                                echo "</td>";
                                echo "<td class='px-4 text-right'>";
                                if (in_array($row['id'], $enrolled_courses)) {
                                    echo '<a href="course_details.php?course_id=' . $row['id'] . '" class="btn btn-success btn-sm px-3 shadow-sm"><i class="fas fa-check-circle mr-2"></i>Go to Course</a>';
                                } else {
                                    echo "<a href='enroll.php?course_id=" . $row['id'] . "' class='btn btn-primary btn-sm px-4 shadow-sm'><i class='fas fa-user-plus mr-2'></i>Enroll Now</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
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