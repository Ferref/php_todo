<?php
class DatabaseSetup {
    private $host = "localhost";
    private $db_name = "todo_list";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    public function createDatabaseAndTable() {
        $sql = "CREATE DATABASE IF NOT EXISTS " . $this->db_name . ";
                USE " . $this->db_name . ";
                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    username VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );";

        try {
            $this->conn->exec($sql);
            echo "Database and table created successfully.";
        } catch(PDOException $exception) {
            echo "Error: " . $exception->getMessage();
        }
    }
}

$databaseSetup = new DatabaseSetup();
$connection = $databaseSetup->getConnection();
if ($connection) {
    $databaseSetup->createDatabaseAndTable();
}
?>
