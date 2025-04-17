<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}

$userId = $_POST['user_id'] ?? null;
$baseSpeed = $_POST['base_speed'] ?? null;
$interval = $_POST['speed_increase_interval'] ?? null;
$factor = $_POST['speed_increase_factor'] ?? null;

if (!$userId || !$baseSpeed || !$interval || !$factor) {
    echo json_encode(['success' => false, 'message' => 'Не все параметры переданы']);
    exit;
}

// Проверяем, есть ли уже настройки для этого пользователя
$checkQuery = "SELECT id FROM test_speed_settings WHERE user_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc();

if ($exists) {
    // Обновляем существующие настройки
    $query = "UPDATE test_speed_settings 
              SET base_speed = ?, speed_increase_interval = ?, speed_increase_factor = ?
              WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("didi", $baseSpeed, $interval, $factor, $userId);
} else {
    // Создаем новые настройки
    $query = "INSERT INTO test_speed_settings 
              (user_id, base_speed, speed_increase_interval, speed_increase_factor)
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("idid", $userId, $baseSpeed, $interval, $factor);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения настроек']);
}
?>