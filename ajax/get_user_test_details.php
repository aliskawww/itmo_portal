<?php
require_once '../../php/db.php';
header('Content-Type: application/json');

if (empty($_GET['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Не указан ID пользователя'
    ]);
    exit;
}

try {
    $userId = $_GET['user_id'];
    
    // 1. Получаем данные пользователя
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'Пользователь не найден'
        ]);
        exit;
    }
    
    // 2. Получаем список тестов пользователя
    $stmt = $pdo->prepare("
        SELECT 
            test_name,
            1 as completed, -- Считаем все записи завершенными
            created_at as completed_at,
            CONCAT('Время: ', reaction_time, ' сек, Правильно: ', correct_answers, '/', total_questions) as result
        FROM test_results
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$userId]);
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'user' => $user,
        'tests' => $tests,
        'completed_all' => count($tests) > 0 // Простая логика завершения
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка базы данных: ' . $e->getMessage()
    ]);
}
?>