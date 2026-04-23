<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php
require_once '../data/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'instructor') {
    header("location: login.php");
    exit;
}

$course_id = $_GET['course_id'];
$instructor_id = $_SESSION['id'];

// Verify instructor owns this course
$stmt = $conn->prepare("SELECT title FROM courses WHERE id = ? AND instructor_id = ?");
$stmt->bind_param("ii", $course_id, $instructor_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "<div class='main-content'><div class='container-fluid'><div class='alert alert-danger'>Access Denied or Course Not Found.</div></div></div>";
    include 'includes/footer.php';
    exit;
}
$course = $result->fetch_assoc();
$stmt->close();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Enrolled Students for: <?php echo htmlspecialchars($course['title']); ?></h2>

        <div class="mb-4">
            <a href="courses.php" class="btn btn-secondary">Back to Courses</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Enrolled At</th>
                            <th>Status</th>
                            <th>Engagement Score</th>
                            <th>Time Spent (mins)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                    u.id as student_id,
                                    u.first_name, 
                                    u.last_name, 
                                    u.email, 
                                    e.enrolled_at, 
                                    e.completed, 
                                    es.score as engagement_score,
                                    (SELECT SUM(rv.time_spent) 
                                     FROM resource_views rv 
                                     JOIN resources r ON rv.resource_id = r.id 
                                     WHERE rv.student_id = u.id AND r.course_id = ?) as total_time
                                FROM 
                                    enrollments e
                                JOIN 
                                    users u ON e.student_id = u.id
                                LEFT JOIN 
                                    engagement_scores es ON u.id = es.student_id
                                WHERE 
                                    e.course_id = ?
                                ORDER BY 
                                    e.enrolled_at DESC";
                        
                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("ii", $course_id, $course_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                                    $email = htmlspecialchars($row['email']);
                                    $enrolled_at = date('M d, Y', strtotime($row['enrolled_at']));
                                    $status = $row['completed'] ? '<span class="badge badge-success">Completed</span>' : '<span class="badge badge-warning">In Progress</span>';
                                    $engagement = $row['engagement_score'] !== null ? number_format($row['engagement_score'], 2) . '%' : 'N/A';
                                    $time_spent = $row['total_time'] !== null ? round($row['total_time'] / 60, 2) : 0;
                                    
                                    echo "<tr>";
                                    echo "<td>$full_name</td>";
                                    echo "<td>$email</td>";
                                    echo "<td>$enrolled_at</td>";
                                    echo "<td>$status</td>";
                                    echo "<td>$engagement</td>";
                                    echo "<td>$time_spent</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No students enrolled yet.</td></tr>";
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

<?php include 'includes/footer.php'; ?>
