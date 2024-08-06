<?php
session_start();

// Enable error reporting for debugging purposes (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection settings
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "users_db";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => "Connection failed: " . $conn->connect_error]));
}

// Check if data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (!empty($username) && !empty($password)) {
        // Prepare SQL statement
        $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                echo json_encode(['status' => 'success', 'message' => 'Login successful!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect password!']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Username not found!']);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields!']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Close the connection
$conn->close();
?>
