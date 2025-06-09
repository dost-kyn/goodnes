<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Для добавления отзыва необходимо авторизоваться']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$recipeId = intval($data['recipe_id'] ?? 0);
$text = trim($data['text'] ?? '');

// Валидация
if ($recipeId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Неверный ID рецепта']);
    exit;
}

if (empty($text) || strlen($text) < 5) {
    echo json_encode(['success' => false, 'message' => 'Текст отзыва должен содержать минимум 5 символов']);
    exit;
}

// Проверяем, нужна ли модерация (например, для администраторов сразу approved)
$needsModeration = $_SESSION['user']['role'] !== 'admin';

try {
    $stmt = $connect->prepare("INSERT INTO reviews (recipe_id, user_id, text, status, created_at) VALUES (?, ?, ?, ?, NOW())");
    $status = $needsModeration ? 'pending' : 'approved';
    $stmt->bind_param("iiss", $recipeId, $_SESSION['user']['id'], $text, $status);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'message' => $needsModeration ? 'Ваш отзыв отправлен на модерацию' : 'Отзыв успешно добавлен',
        'approved' => !$needsModeration
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении отзыва: ' . $e->getMessage()]);
}