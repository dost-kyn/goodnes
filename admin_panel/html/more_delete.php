<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../connect/connect.php';

try {
    // Получаем данные
    $inputData = json_decode(file_get_contents('php://input'), true);
    $recipe_id = intval($inputData['id'] ?? 0);

    if ($recipe_id <= 0) {
        throw new Exception('Неверный ID рецепта');
    }

    // Начинаем транзакцию для безопасного удаления
    mysqli_begin_transaction($connect);

    // 1. Сначала получаем информацию о файлах для удаления
    $sql = "SELECT maun_image FROM recipes WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $recipe_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $recipe = mysqli_fetch_assoc($result);

    // 2. Удаляем шаги рецепта
    $sql = "DELETE FROM recipe_steps WHERE recipe_id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $recipe_id);
    mysqli_stmt_execute($stmt);

    // 3. Удаляем сам рецепт
    $sql = "DELETE FROM recipes WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $recipe_id);
    mysqli_stmt_execute($stmt);

    // 4. Удаляем связанные файлы
    if (!empty($recipe['maun_image'])) {
        $projectRoot = realpath(__DIR__ . '/../../');
        $filePath = $projectRoot . str_replace('/', DIRECTORY_SEPARATOR, $recipe['maun_image']);
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Если всё прошло успешно - коммитим транзакцию
    mysqli_commit($connect);
    
    echo json_encode(['success' => true, 'message' => 'Рецепт успешно удален']);
    
} catch (Exception $e) {
    // В случае ошибки откатываем транзакцию
    if (isset($connect)) {
        mysqli_rollback($connect);
    }
    
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}