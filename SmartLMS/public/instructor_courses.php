<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
require_once '../data/db.php';

if (!isset($_GET['instructor_id'])) {
    header("location: browse_courses.php");
    exit;
}

$instructor_id = $_GET['instructor_id'];
$student_id = $_SESSION['id'];

// Get instructor details and their aggregate rating
$stmt_inst = $conn->prepare("SELECT u.first_name, u.last_name, u.email, AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
                             FROM users u 
                             LEFT JOIN courses c ON u.id = c.instructor_id 
                             LEFT JOIN course_ratings r ON c.id = r.course_id 
                             WHERE u.id = ? AND u.role = 'instructor'
                             GROUP BY u.id");
$stmt_inst->bind_param("i", $instructor_id);
$stmt_inst->execute();
$instructor = $stmt_inst->get_result()->fetch_assoc();
$stmt_inst->close();

if (!$instructor) {
    header("location: browse_courses.php");
    exit;
}

// Get enrolled courses for the student to show status
$enrolled_courses = [];
$stmt_enrolled = $conn->prepare("SELECT course_id FROM enrollments WHERE student_id = ?");
$stmt_enrolled->bind_param("i", $student_id);
$stmt_enrolled->execute();
$res_enrolled = $stmt_enrolled->get_result();
while ($row = $res_enrolled->fetch_assoc()) {
    $enrolled_courses[] = $row['course_id'];
}
$stmt_enrolled->close();
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0 bg-dark text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1"><?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?></h2>
                            <p class="mb-0 text-light"><?php echo htmlspecialchars($instructor['email']); ?></p>
                        </div>
                        <div class="text-right text-center">
                            <h5 class="mb-1 text-warning">Instructor Rating</h5>
                            <?php if ($instructor['review_count'] > 0): ?>
                                <div style="font-size: 1.5rem;" class="text-warning">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= round($instructor['avg_rating'])) ? "★" : "☆"; ?>
                                </div>
                                <small><?php echo number_format($instructor['avg_rating'], 1); ?> average (<?php echo $instructor['review_count']; ?> reviews)</small>
                            <?php else: ?>
                                <p class="text-muted small mb-0">No ratings yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Courses by this Instructor</h3>
            <a href="browse_courses.php" class="btn btn-outline-secondary btn-sm">Back to Browse All</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4">Course Title</th>
                            <th class="border-0">Description</th>
                            <th class="border-0">Rating</th>
                            <th class="border-0 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt_courses = $conn->prepare("SELECT c.id, c.title, c.description, AVG(r.rating) as avg_r, COUNT(r.id) as count_r 
                                                       FROM courses c 
                                                       LEFT JOIN course_ratings r ON c.id = r.course_id 
                                                       WHERE c.instructor_id = ? 
                                                       GROUP BY c.id");
                        $stmt_courses->bind_param("i", $instructor_id);
                        $stmt_courses->execute();
                        $res_courses = $stmt_courses->get_result();

                        if ($res_courses->num_rows > 0) {
                            while ($row = $res_courses->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-4 font-weight-bold'>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>";
                                if ($row['count_r'] > 0) {
                                    echo "<span class='text-warning'>";
                                    for($i=1; $i<=5; $i++) echo ($i <= round($row['avg_r'])) ? "★" : "☆";
                                    echo "</span> <small>(" . number_format($row['avg_r'], 1) . ")</small>";
                                } else {
                                    echo "<span class='text-muted small'>Not rated</span>";
                                }
                                echo "</td>";
                                echo "<td class='px-4'>";
                                if (in_array($row['id'], $enrolled_courses)) {
                                    echo "<a href='course_details.php?course_id=" . $row['id'] . "' class='btn btn-success btn-sm'>View Course</a>";
                                } else {
                                    echo "<a href='enroll.php?course_id=" . $row['id'] . "' class='btn btn-primary btn-sm px-3'>Enroll</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center p-4'>No courses found for this instructor.</td></tr>";
                        }
                        $stmt_courses->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
