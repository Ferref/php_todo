<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "users_db";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Create tasks table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    task VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($table_sql) !== TRUE) {
    echo json_encode(['status' => 'error', 'message' => 'Error creating tasks table: ' . $conn->error]);
    exit;
}

$user_id = $_SESSION['user_id'];

$action = $_REQUEST['action'];

if ($action == 'add') {
    $task = $_POST['task'];
    $stmt = $conn->prepare('INSERT INTO tasks (user_id, task) VALUES (?, ?)');
    $stmt->bind_param('is', $user_id, $task);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding task']);
    }
    $stmt->close();
} elseif ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting task']);
    }
    $stmt->close();
} elseif ($action == 'list') {
    $stmt = $conn->prepare('SELECT id, task FROM tasks WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($id, $task);
    $tasks = [];
    while ($stmt->fetch()) {
        $tasks[] = ['id' => $id, 'task' => $task];
    }
    $stmt->close();
    echo json_encode(['status' => 'success', 'tasks' => $tasks]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

$conn->close();
?>
