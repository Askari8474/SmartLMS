<!DOCTYPE html>
<html>
<head>
    <title>SmartLMS - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--dark-teal) 0%, var(--primary-teal) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: var(--primary-teal);
            font-weight: 700;
            margin-top: 10px;
        }
        .login-header i {
            font-size: 3rem;
            color: var(--primary-teal);
        }
        .btn-login {
            background-color: var(--primary-teal);
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-login:hover {
            background-color: var(--dark-teal);
            transform: translateY(-2px);
        }
        .form-control {
            border-radius: 8px;
            padding: 25px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.2rem rgba(0, 128, 128, 0.25);
        }
        .register-link {
            color: var(--primary-teal);
            font-weight: 600;
            text-decoration: none;
        }
        .register-link:hover {
            color: var(--dark-teal);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-user-graduate"></i>
            <h2>SmartLMS Login</h2>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php 
                    if ($_GET['error'] == 'invalid_password') echo "Incorrect password. Please try again.";
                    elseif ($_GET['error'] == 'user_not_found') echo "No account found with that username/email.";
                    else echo "Something went wrong. Please try again later.";
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="../app/login_process.php" method="post">
            <div class="form-group mb-4">
                <label class="text-muted small font-weight-bold">USERNAME OR EMAIL</label>
                <input type="text" name="login" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="form-group mb-4">
                <label class="text-muted small font-weight-bold">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login shadow-sm">
                Sign In <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted mb-0">New to SmartLMS?</p>
            <a href="register.php" class="register-link">Create an Account</a>
        </div>
    </div>

    <!-- Bootstrap JS for Alert dismissal -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>