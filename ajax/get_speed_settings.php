<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}

$query = "SELECT ts.*, u.username 
          FROM test_speed_settings ts
          JOIN users u ON ts.user_id = u.id";
$result = $conn->query($query);

if ($result) {
    $settings = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'data' => $settings]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка получения данных']);
}
?>