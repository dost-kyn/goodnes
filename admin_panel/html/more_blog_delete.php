<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';

header('Content-Type: application/json');

// Получаем ID блога
$blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($blog_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Неверный ID блога']);
    exit;
}

// Начинаем транзакцию
mysqli_begin_transaction($connect);

try {
    // 1. Удаляем шаги блога
    $sql = "DELETE FROM blog_steps WHERE blog_id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $blog_id);
    mysqli_stmt_execute($stmt);
    
    // 2. Удаляем сам блог
    $sql = "DELETE FROM blogs WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $blog_id);
    mysqli_stmt_execute($stmt);
    
    // Если все успешно - коммитим транзакцию
    mysqli_commit($connect);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Откатываем в случае ошибки
    mysqli_rollback($connect);
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка при удалении: ' . $e->getMessage()
    ]);
}
?>