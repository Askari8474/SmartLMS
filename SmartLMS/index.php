<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // Redirect based on user role
    if ($_SESSION["role"] == 'administrator') {
        header("location: public/admin_dashboard.php");
    } elseif ($_SESSION["role"] == 'instructor') {
        header("location: public/instructor_dashboard.php");
    } else {
        header("location: public/learner_dashboard.php");
    }
    exit;
} else {
    // If not logged in, redirect to login page
    header("location: public/login.php");
    exit;
}
?>