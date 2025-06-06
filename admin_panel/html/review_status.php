<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';

header('Content-Type: application/json');

// Настройка отображения ошибок
ini_set('display_errors', 0);
error_reporting(E_ALL);
function log_error($message) {
    file_put_contents(__DIR__ . '/review_errors.log', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

try {
    // Проверка метода запроса
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Разрешены только POST-запросы');
    }

    // Валидация параметров
    $reviewId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

    if (!$reviewId || !$status || $status < 1 || $status > 3) {
        throw new Exception('Неверные параметры запроса');
    }

    // Проверка существования отзыва
    $check = mysqli_query($connect, "SELECT id FROM reviews WHERE id = $reviewId");
    if (mysqli_num_rows($check) === 0) {
        throw new Exception('Отзыв не найден');
    }

    // Обновление статуса
    $result = mysqli_query($connect, "UPDATE reviews SET status = $status WHERE id = $reviewId");
    
    if (!$result) {
        throw new Exception('Ошибка базы данных: ' . mysqli_error($connect));
    }

    // Проверка, что запись действительно обновилась
    if (mysqli_affected_rows($connect) === 0) {
        throw new Exception('Статус не был изменен');
    }

    // Получаем обновленные данные
    $updated = mysqli_query($connect, "SELECT status FROM reviews WHERE id = $reviewId");
    $statusData = mysqli_fetch_assoc($updated);

    // Успешный ответ
    echo json_encode([
        'success' => true,
        'new_status' => $statusData['status'],
        'affected_rows' => mysqli_affected_rows($connect)
    ]);

} catch (Exception $e) {
    log_error($e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Произошла ошибка при обновлении статуса',
        'error' => $e->getMessage()
    ]);
}