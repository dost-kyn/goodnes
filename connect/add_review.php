<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

try {
    // Проверка авторизации
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('Требуется авторизация', 401);
    }

    // Получение данных
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Неверный формат данных', 400);
    }

    // Валидация
    if (empty($data['text'] ?? null)) {
        throw new Exception('Текст отзыва не может быть пустым', 400);
    }

    if (empty($data['recipe_id'] ?? null)) {
        throw new Exception('Не указан рецепт', 400);
    }

    $user_id = $_SESSION['user']['id'];
    $recipe_id = (int)$data['recipe_id'];
    $text = trim($data['text']);

    // Проверка длины текста
    if (mb_strlen($text) < 5) {
        throw new Exception('Отзыв должен содержать минимум 5 символов', 400);
    }

    // Проверка соединения с БД
    if (!$connect) {
        throw new Exception('Ошибка подключения к базе данных', 500);
    }

    // Проверка существования рецепта (упрощенная версия)
    $check = $connect->query("SELECT 1 FROM recipes WHERE id = $recipe_id LIMIT 1");
    if (!$check || $check->num_rows === 0) {
        throw new Exception('Рецепт не найден', 404);
    }

    // Добавление отзыва (исправленный запрос)
    $query = "INSERT INTO reviews 
              (user_id, recipe_id, text, created_at, status) 
              VALUES (?, ?, ?, NOW(), 1)";
              
    $stmt = $connect->prepare($query);
    if (!$stmt) {
        throw new Exception('Ошибка подготовки запроса: ' . $connect->error, 500);
    }

    $stmt->bind_param("iis", $user_id, $recipe_id, $text);
    
    if (!$stmt->execute()) {
        throw new Exception('Ошибка выполнения запроса: ' . $stmt->error, 500);
    }

    // Упрощенный ответ
    echo json_encode([
        'success' => true,
        'user_name' => $_SESSION['user']['name'] ?? 'Пользователь',
        'message' => 'Отзыв успешно добавлен'
    ]);

} catch (Exception $e) {
    http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
}