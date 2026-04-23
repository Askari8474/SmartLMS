<!DOCTYPE html>
<html>
<head>
    <title>SmartLMS - Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--dark-teal) 0%, var(--primary-teal) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 40px 0;
        }
        .register-card {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h2 {
            color: var(--primary-teal);
            font-weight: 700;
            margin-top: 10px;
        }
        .register-header i {
            font-size: 3rem;
            color: var(--primary-teal);
        }
        .btn-register {
            background-color: var(--primary-teal);
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-register:hover {
            background-color: var(--dark-teal);
            transform: translateY(-2px);
        }
        .form-control {
            border-radius: 8px;
            padding: 22px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.2rem rgba(0, 128, 128, 0.25);
        }
        .login-link {
            color: var(--primary-teal);
            font-weight: 600;
            text-decoration: none;
        }
        .login-link:hover {
            color: var(--dark-teal);
            text-decoration: underline;
        }
        label {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invalid-feedback {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h2>Create Account</h2>
            <p class="text-muted">Join the SmartLMS learning community</p>
        </div>

        <?php
        session_start();
        if (isset($_SESSION['errors'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show">';
            echo '<i class="fas fa-exclamation-triangle mr-2"></i>' . implode('<br>', $_SESSION['errors']);
            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '</div>';
            unset($_SESSION['errors']);
        }
        ?>

        <form id="registration-form" action="../app/register_process.php" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="John" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Doe" required>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="john@example.com" required>
            </div>

            <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="johndoe123" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label>Join as</label>
                <select name="role" class="form-control" style="height: auto; padding: 10px 15px;" required>
                    <option value="learner">Learner (I want to learn)</option>
                    <option value="instructor">Instructor (I want to teach)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-register shadow-sm">
                Register Now <i class="fas fa-check-circle ml-2"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted mb-0">Already have an account?</p>
            <a href="login.php" class="login-link">Sign In Here</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#registration-form").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                email: { required: true, email: true },
                username: "required",
                password: { required: true, minlength: 8 },
                confirm_password: { required: true, minlength: 8, equalto: "#password" }
            },
            errorElement: "div",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                error.insertAfter(element);
            },
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });
    </script>
</body>
</html>