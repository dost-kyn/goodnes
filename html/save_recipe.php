<?php
session_start();
require_once '../connect/connect.php';

// Разрешаем CORS (для разработки)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Включаем отладку
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Проверяем авторизацию
if (empty($_SESSION['user']['id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Требуется авторизация']));
}

// Получаем данные из POST или GET
$requestData = array_merge($_GET, $_POST);
$user_id = (int)$_SESSION['user']['id'];
$recipe_id = isset($requestData['recipe_id']) ? (int)$requestData['recipe_id'] : 0;
$action = isset($requestData['action']) ? $requestData['action'] : '';

// Логирование для отладки
error_log("Request data: " . print_r($requestData, true));
error_log("User ID: $user_id, Recipe ID: $recipe_id, Action: $action");

// Проверяем ID рецепта
if ($recipe_id <= 0) {
    http_response_code(400);
    die(json_encode([
        'success' => false, 
        'message' => 'Неверный ID рецепта',
        'received_data' => $requestData
    ]));
}

try {
    if ($action === 'add') {
        // Проверяем, не сохранен ли уже рецепт
        $check = $connect->prepare("SELECT 1 FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
        $check->bind_param("ii", $user_id, $recipe_id);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            die(json_encode(['success' => false, 'message' => 'Рецепт уже сохранен']));
        }

        // Получаем текущую дату и время
        $saved_at = date('Y-m-d'); // Формат: Год-Месяц-День Часы:Минуты:Секунды

        // Сохраняем рецепт с датой
        $stmt = $connect->prepare("INSERT INTO saved_recipes (user_id, recipe_id, saved_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $recipe_id, $saved_at);
        $stmt->execute();
        $connect->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Рецепт успешно сохранен',
            'debug' => [
                'user_id' => $user_id,
                'recipe_id' => $recipe_id,
                'saved_at' => $saved_at
            ]
        ]);
        
    } elseif ($action === 'remove') {
        // Удаляем рецепт из сохраненных
        $stmt = $connect->prepare("DELETE FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
        $stmt->bind_param("ii", $user_id, $recipe_id);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Рецепт успешно удален'
        ]);
        
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Неверное действие']);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Ошибка базы данных',
        'error' => $e->getMessage()
    ]);
}





// echo 'id пользователя = ' . $user_id . '. id рецепта = ' . $recipe_id . '. активность кнопки = ' .$action;

// echo "<pre>";
// echo "GET данные:\n";
// print_r($_GET);
// echo "\nPOST данные:\n";
// print_r($_POST);
// echo "\nСессия:\n";
// print_r($_SESSION);
// echo "</pre>";
// exit();
// 
// ?>