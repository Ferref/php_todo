<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Cretae connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if($conn->connect_error){
    die("Connection failed: " . $conn -> connect_error)
}

// Create database if does not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if($conn -> query($sql) === TRUE){
    echo "Database checked/created successfully<br>";
}else{
    die("Error creating database" . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// SQL to create table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($table_sql) === TRUE){
    echo "Table 'users' checked/created successfully<br>";
}
else {
    die("Error crating table> ". $conn->error);
}








?>