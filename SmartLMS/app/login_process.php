<?php
session_start();

require_once '../data/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, role FROM users WHERE username = ? OR email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $login, $login);

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $role);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role"] = $role;

                        // Behavioral Data Capture - Login frequency
                        $log_sql = "INSERT INTO login_log (user_id) VALUES (?)";
                        if ($log_stmt = $conn->prepare($log_sql)) {
                            $log_stmt->bind_param("i", $id);
                            $log_stmt->execute();
                            $log_stmt->close();
                        }

                        if ($role == 'administrator') {
                            header("location: ../public/admin_dashboard.php");
                        } elseif ($role == 'instructor') {
                            header("location: ../public/instructor_dashboard.php");
                        } else {
                            header("location: ../public/learner_dashboard.php");
                        }
                        exit;
                    } else {
                        // Redirect with error
                        header("location: ../public/login.php?error=invalid_password");
                        exit;
                    }
                }
            } else {
                header("location: ../public/login.php?error=user_not_found");
                exit;
            }
        } else {
            header("location: ../public/login.php?error=db_error");
            exit;
        }

        $stmt->close();
    }

    $conn->close();
}
?>