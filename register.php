<?php
class Database {
    private $host = "localhost";
    private $db_name = "todo_list";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);
    $username = sanitizeInput($_POST['username']);
    $password1 = sanitizeInput($_POST['password1']);
    $password2 = sanitizeInput($_POST['password2']);

    if ($password1 !== $password2) {
        echo "Passwords do not match.";
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();

    $query = "INSERT INTO users (email, username, password) VALUES (:email, :username, :password)";
    $stmt = $db->prepare($query);

    $hashed_password = password_hash($password1, PASSWORD_BCRYPT);

    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error registering user.";
    }
}
?>
