<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID не указан']);
    exit;
}

$query = "DELETE FROM test_speed_settings WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка удаления']);
}
?>