<?php
// Enable error reporting for debugging purposes (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Start session to store form data
session_start();

// Database connection settings
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "users_db";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => "Connection failed: " . $conn->connect_error]));
}

// Create database if it does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die(json_encode(['status' => 'error', 'message' => "Error creating database: " . $conn->error]));
}

// Select the database
$conn->select_db($dbname);

// SQL to create table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($table_sql) !== TRUE) {
    die(json_encode(['status' => 'error', 'message' => "Error creating table: " . $conn->error]));
}

// Check if data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Validate input
    if (!empty($email) && !empty($username) && !empty($password1) && !empty($password2)) {
        // Check if passwords match
        if ($password1 === $password2) {
            // Check if email or username already exists
            $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
            $stmt->bind_param('ss', $email, $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Email or username already exists!']);
            } else {
                // Hash the password
                $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

                // Prepare SQL statement
                $stmt = $conn->prepare('INSERT INTO users (email, username, password) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $email, $username, $hashed_password);

                // Execute the statement
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
                }
            }

            // Close the statement
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields!']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Please use the registration form.']);
}

// Close the connection
$conn->close();
?>
