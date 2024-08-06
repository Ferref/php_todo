<?php
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
$conn = new mysqli($servername, $dbusername, $dbpassword);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
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
    die("Error creating table: " . $conn->error);
}

// Check if data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: Log request method
    error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
    
    // Get form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Validate input
    if (!empty($email) && !empty($username) && !empty($password1) && !empty($password2)) {
        // Check if passwords match
        if ($password1 === $password2) {
            // Hash the password
            $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

            // Prepare SQL statement
            $stmt = $conn->prepare('INSERT INTO users (email, username, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $email, $username, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo 'Error: ' . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo 'Passwords do not match!';
        }
    } else {
        echo 'Please fill in all fields!';
    }
} else {
    // Debugging: Log wrong method access
    error_log("Wrong request method: " . $_SERVER['REQUEST_METHOD']);
    echo 'Invalid request method. Please use the registration form.';
}

// Close the connection
$conn->close();
?>
