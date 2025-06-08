<?php
session_start();
require_once '../connect/connect.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Проверка авторизации
if (empty($_SESSION['user']['id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Требуется авторизация']));
}

// Получаем JSON данные
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Валидация
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Неверный формат данных']));
}

$user_id = (int)$_SESSION['user']['id'];
$recipe_id = isset($data['recipe_id']) ? (int)$data['recipe_id'] : 0;
$action = $data['action'] ?? '';

try {
    if ($action === 'add') {
        // Проверка существования записи
        $check = $connect->prepare("SELECT id FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
        $check->bind_param("ii", $user_id, $recipe_id);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            die(json_encode(['success' => false, 'message' => 'Рецепт уже сохранен']));
        }

        // Получаем текущую дату и время
        $saved_at = date('Y-m-d');

        // 
        $stmt = $connect->prepare("INSERT INTO saved_recipes (user_id, recipe_id, saved_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $recipe_id, $saved_at);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Рецепт успешно сохранен',
            'id' => $stmt->insert_id
        ]);
        
    } elseif ($action === 'remove') {
        // Удаление
        $stmt = $connect->prepare("DELETE FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
        $stmt->bind_param("ii", $user_id, $recipe_id);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Рецепт удален',
            'deleted_rows' => $stmt->affected_rows
        ]);
        
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Неверное действие']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка базы данных',
        'error' => $e->getMessage()
    ]);
}
