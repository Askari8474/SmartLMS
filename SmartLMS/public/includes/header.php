<?php 
session_start(); 

// Check if user is logged in
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if ($current_page !== 'login.php' && $current_page !== 'register.php') {
        header("location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SmartLMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>