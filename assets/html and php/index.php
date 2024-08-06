<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Retrieve user information from the database (optional)
// Database connection settings
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "users_db";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT username, email FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Welcome - To-Do List</title>
    <link href='/assets/css/styles.css' rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6 text-center">
                <p>Your email: <?php echo htmlspecialchars($email); ?></p>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <h3>Your To-Do List</h3>
                <form id="add-task-form">
                    <div class="form-group">
                        <input type="text" class="form-control" id="task" name="task" placeholder="New Task" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Add Task</button>
                </form>
                <ul class="list-group mt-3" id="task-list">
                    <!-- Tasks will be dynamically loaded here -->
                </ul>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/task_manager.js"></script>
</body>
</html>
