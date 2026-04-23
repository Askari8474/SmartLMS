<div class="sidebar">
    <a href="#" class="brand">SmartLMS</a>
    <ul class="nav-links">
        <?php if ($_SESSION["role"] == 'administrator'): ?>
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
        <?php elseif ($_SESSION["role"] == 'instructor'): ?>
            <li><a href="instructor_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
        <?php else: ?>
            <li><a href="learner_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="browse_courses.php"><i class="fas fa-search"></i> Browse Courses</a></li>
        <?php endif; ?>
        <li><a href="../app/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>