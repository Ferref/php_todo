<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - To-Do List</title>
    <link href="/assets/css/styles.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Register to To-Do List</h1>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
                <form id="register-form" action="registration.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password1">Password</label>
                        <input type="password" class="form-control" id="password1" name="password1" required>
                    </div>
                    <div class="form-group">
                        <label for="password2">Password Again</label>
                        <input type="password" class="form-control" id="password2" name="password2" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a href="login.html">Do you already have an account? Login here!</a>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/scripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
