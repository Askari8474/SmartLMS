<?php
include 'includes/header.php';
require_once '../data/db.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'learner') {
    header("location: login.php");
    exit;
}

$resource_id = $_GET['id'] ?? null;
$student_id = $_SESSION['id'];

if (!$resource_id) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Resource ID missing.</div></div>";
    include 'includes/footer.php';
    exit;
}

// Get resource details
$sql_resource = "SELECT title, resource_type, path, link, course_id FROM resources WHERE id = ?";
if ($stmt_resource = $conn->prepare($sql_resource)) {
    $stmt_resource->bind_param("i", $resource_id);
    $stmt_resource->execute();
    $result_resource = $stmt_resource->get_result();
    $resource = $result_resource->fetch_assoc();
    $stmt_resource->close();
}

if (!$resource) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Resource not found.</div></div>";
    include 'includes/footer.php';
    exit;
}

$course_id = $resource['course_id'];

// Check if already completed
$sql_check = "SELECT id FROM resource_completions WHERE student_id = ? AND resource_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $student_id, $resource_id);
$stmt_check->execute();
$is_already_completed = $stmt_check->get_result()->num_rows > 0;
$stmt_check->close();

// Fetch previous time spent
$sql_time = "SELECT SUM(time_spent) as prev_time FROM resource_views WHERE student_id = ? AND resource_id = ?";
$stmt_time = $conn->prepare($sql_time);
$stmt_time->bind_param("ii", $student_id, $resource_id);
$stmt_time->execute();
$prev_time = $stmt_time->get_result()->fetch_assoc()['prev_time'] ?? 0;
$stmt_time->close();

// Helper to handle YouTube Embeds
function getYouTubeEmbedUrl($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return isset($match[1]) ? "https://www.youtube.com/embed/" . $match[1] : $url;
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?php echo htmlspecialchars($resource['title']); ?></h2>
        <a href="course_details.php?course_id=<?php echo $course_id; ?>" class="btn btn-outline-secondary btn-sm">Exit Lesson</a>
    </div>
    <hr>

    <div class="resource-viewer mb-4" style="background: #f8f9fa; border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
        <?php if ($resource['resource_type'] === 'file'): ?>
            <?php 
            $file_path = $resource['path'];
            $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
            ?>
            <?php if (in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])): ?>
                <embed src="<?php echo htmlspecialchars($file_path); ?>" width="100%" height="700px" style="border-radius: 4px;" />
            <?php else: ?>
                <div class="p-5 text-center">
                    <i class="fas fa-file-download fa-4x mb-3 text-primary"></i>
                    <h4>Download Required</h4>
                    <p>This file type (<?php echo $extension; ?>) cannot be previewed. Please download it to complete the lesson.</p>
                    <a href="<?php echo htmlspecialchars($file_path); ?>" download class="btn btn-primary btn-lg">Download <?php echo htmlspecialchars(basename($file_path)); ?></a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php 
            $link_url = $resource['link'];
            $is_youtube = strpos($link_url, 'youtube.com') !== false || strpos($link_url, 'youtu.be') !== false;
            ?>
            <?php if ($is_youtube): ?>
                <div class="ratio ratio-16x9" style="--bs-aspect-ratio: 56.25%;">
                    <iframe src="<?php echo getYouTubeEmbedUrl($link_url); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width: 100%; height: 500px; border-radius: 4px;"></iframe>
                </div>
            <?php else: ?>
                <div class="text-center p-3">
                    <div class="alert alert-info">
                        Some external sites might block direct embedding for security. If the window below is blank, use the button instead.
                    </div>
                    <a href="<?php echo htmlspecialchars($link_url); ?>" target="_blank" class="btn btn-sm btn-info mb-3">Open in New Tab <i class="fas fa-external-link-alt"></i></a>
                    <iframe src="<?php echo htmlspecialchars($link_url); ?>" width="100%" height="600px" style="border: 1px solid #ccc; background: white;"></iframe>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body d-flex justify-content-between align-items-center bg-light">
            <div class="time-stats">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <span class="text-muted small text-uppercase">Previous Time Spent:</span>
                        <span class="fw-bold ms-2"><?php echo $prev_time; ?>s</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-muted small text-uppercase">Current Session:</span>
                        <span id="session-time" class="fw-bold text-primary ms-2">0s</span>
                    </li>
                    <li class="border-top pt-2">
                        <span class="text-muted small text-uppercase">Total Time Spent:</span>
                        <span id="total-time" class="fw-bold text-success ms-2"><?php echo $prev_time; ?>s</span>
                    </li>
                </ul>
            </div>
            <div>
                <form id="complete-form">
                    <input type="hidden" name="resource_id" value="<?php echo $resource_id; ?>">
                    <?php if ($is_already_completed): ?>
                        <button type="button" class="btn btn-success disabled">
                            <i class="fas fa-check-circle"></i> Lesson Completed
                        </button>
                    <?php else: ?>
                        <button type="submit" id="complete-btn" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-check"></i> Mark as Complete
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let startTime = Date.now();
let resourceId = <?php echo json_encode($resource_id); ?>;
let prevTime = <?php echo (int)$prev_time; ?>;

// Update time display every second
setInterval(() => {
    let sessionSeconds = Math.floor((Date.now() - startTime) / 1000);
    document.getElementById('session-time').innerText = sessionSeconds + "s";
    document.getElementById('total-time').innerText = (prevTime + sessionSeconds) + "s";
}, 1000);

// Beacon for background tracking (on tab close)
window.addEventListener('beforeunload', function() {
    let timeSpent = Math.floor((Date.now() - startTime) / 1000);
    if (timeSpent > 0) {
        let params = new URLSearchParams();
        params.append('resource_id', resourceId);
        params.append('time_spent', timeSpent);
        navigator.sendBeacon('track_resource.php', params);
    }
});

// Manual Complete Form
document.getElementById('complete-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('complete-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Marking...';

    fetch('mark_complete.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'resource_id=' + resourceId
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            btn.className = 'btn btn-success disabled';
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Lesson Completed!';
            setTimeout(() => {
                window.location.href = 'course_details.php?course_id=<?php echo $course_id; ?>';
            }, 1000);
        } else {
            alert('Error: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Mark as Complete';
        }
    });
});
</script>

<?php 
$conn->close();
include 'includes/footer.php'; 
?>
