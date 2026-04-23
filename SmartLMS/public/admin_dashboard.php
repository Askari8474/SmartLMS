<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
require_once '../data/db.php';

// Fetch counts
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$total_enrollments = $conn->query("SELECT COUNT(*) as count FROM enrollments")->fetch_assoc()['count'];
?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Administrator Dashboard</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Users</h5>
                            <p><?php echo $total_users; ?></p>
                        </div>
                        <i class="fas fa-users card-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Courses</h5>
                            <p><?php echo $total_courses; ?></p>
                        </div>
                        <i class="fas fa-book card-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Enrollments</h5>
                            <p><?php echo $total_enrollments; ?></p>
                        </div>
                        <i class="fas fa-user-plus card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>