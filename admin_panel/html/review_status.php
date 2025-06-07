<?php
header('Content-Type: text/plain');
require_once __DIR__ . '/../../connect/connect.php';

// Проверка соединения
if (!$connect) {
    die("Ошибка подключения к базе данных");
}

try {
    $reviewId = (int)($_POST['review_id'] ?? 0);
    $newStatus = (int)($_POST['status'] ?? 0);
    $reason = $_POST['reason'] ?? null;

    // Валидация
    if ($reviewId <= 0 || !in_array($newStatus, [1, 2, 3])) {
        throw new Exception("Неверные параметры запроса");
    }

    // Для статуса "Удалить" требуется причина
    if ($newStatus === 3) {
        if (empty($reason)) {
            die("reason_required");
        }
        
        if (strlen(trim($reason)) < 5) {
            // throw new Exception("Причина должна содержать минимум 5 символов");
        }
    }

    // Определяем текстовый статус
    $statusText = match($newStatus) {
        1 => 'pending',
        2 => 'approved',
        3 => 'rejected',
        default => 'pending'
    };

    // Подготовка запроса
    $query = "UPDATE reviews SET 
                status = ?,
                reason_deletion = ?,
                deletion_time = CASE 
                    WHEN ? = 3 THEN DATE_ADD(NOW(), INTERVAL 24 HOUR)
                    ELSE NULL
                END
              WHERE id = ?";
    
    $stmt = $connect->prepare($query);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . $connect->error);
    }

    // Привязываем параметры (s - string, i - integer)
    $stmt->bind_param("ssii", $statusText, $reason, $newStatus, $reviewId);
    
    if (!$stmt->execute()) {
        throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
    }

    echo "success";

} catch (Exception $e) {
    error_log("Error in review_status.php: " . $e->getMessage());
    echo $e->getMessage();
}




// header('Content-Type: text/plain');
// require_once __DIR__ . '/../../connect/connect.php';

// $reviewId = (int)($_POST['review_id'] ?? 0);
// $newStatus = (int)($_POST['status'] ?? 0);

// // Проверка данных
// if ($reviewId <= 0 || !in_array($newStatus, [1, 2, 3])) {
//     die('error: Неверные параметры');
// }

// // Получаем текущий статус из БД
// $stmt = $connect->prepare("SELECT status FROM reviews WHERE id = ?");
// $stmt->bind_param("i", $reviewId);
// $stmt->execute();
// $currentStatus = $stmt->get_result()->fetch_assoc()['status'];

// // Если уже approved - запрещаем изменения
// if ($currentStatus == 2 && $newStatus != 2) {
//     die('error: Отзыв уже одобрен');
// }

// // Обновляем статус
// $update = $connect->prepare("UPDATE reviews SET status = ? WHERE id = ?");
// $update->bind_param("ii", $newStatus, $reviewId);

// if ($update->execute()) {
//     echo "success";
// } else {
//     echo "error: Ошибка базы данных";
// }

















// header('Content-Type: application/json; charset=utf-8');

// // Отключаем вывод ошибок PHP на экран
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);

// // Подключаемся к БД
// require_once '/../../connect/connect.php';

// try {
//     // Получаем и проверяем входные данные
//     $input = json_decode(file_get_contents('php://input'), true);
    
//     if (!$input) {
//         throw new Exception('Неверный формат данных');
//     }
    
//     if (empty($input['review_id']) || empty($input['status'])) {
//         throw new Exception('Отсутствуют обязательные параметры');
//     }
    
//     // Проверяем текущий статус
//     $stmt = $conn->prepare("SELECT status FROM reviews WHERE id = ?");
//     $stmt->bind_param("i", $input['review_id']);
//     $stmt->execute();
//     $result = $stmt->get_result();
    
//     if ($result->num_rows === 0) {
//         throw new Exception('Отзыв не найден');
//     }
    
//     $currentStatus = $result->fetch_assoc()['status'];
//     if ($currentStatus === 'approved') {
//         throw new Exception('Отзыв уже одобрен');
//     }
    
//     // Обновляем статус
//     $stmt = $conn->prepare("UPDATE reviews SET status = ? WHERE id = ?");
//     $stmt->bind_param("si", $input['status'], $input['review_id']);
//     $stmt->execute();
    
//     // Успешный ответ
//     echo json_encode([
//         'success' => true,
//         'message' => 'Статус обновлен'
//     ]);
    
// } catch (Exception $e) {
//     // Обработка ошибок
//     http_response_code(400);
//     echo json_encode([
//         'success' => false,
//         'message' => $e->getMessage()
//     ]);
// }

// exit; // Завершаем выполнение



















// header('Content-Type: application/json');

// // Настройка отображения ошибок
// ini_set('display_errors', 0);
// error_reporting(E_ALL);
// function log_error($message) {
//     file_put_contents(__DIR__ . '/review_errors.log', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
// }

// try {
//     // Проверка метода запроса
//     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//         throw new Exception('Разрешены только POST-запросы');
//     }

//     // Валидация параметров
//     $reviewId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
//     $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

//     if (!$reviewId || !$status || $status < 1 || $status > 3) {
//         throw new Exception('Неверные параметры запроса');
//     }

//     // Проверка существования отзыва
//     $check = mysqli_query($connect, "SELECT id FROM reviews WHERE id = $reviewId");
//     if (mysqli_num_rows($check) === 0) {
//         throw new Exception('Отзыв не найден');
//     }

//     // Обновление статуса
//     $result = mysqli_query($connect, "UPDATE reviews SET status = $status WHERE id = $reviewId");
    
//     if (!$result) {
//         throw new Exception('Ошибка базы данных: ' . mysqli_error($connect));
//     }

//     // Проверка, что запись действительно обновилась
//     if (mysqli_affected_rows($connect) === 0) {
//         throw new Exception('Статус не был изменен');
//     }

//     // Получаем обновленные данные
//     $updated = mysqli_query($connect, "SELECT status FROM reviews WHERE id = $reviewId");
//     $statusData = mysqli_fetch_assoc($updated);

//     // Успешный ответ
//     echo json_encode([
//         'success' => true,
//         'new_status' => $statusData['status'],
//         'affected_rows' => mysqli_affected_rows($connect)
//     ]);

// } catch (Exception $e) {
//     log_error($e->getMessage());
//     http_response_code(500);
//     echo json_encode([
//         'success' => false,
//         'message' => 'Произошла ошибка при обновлении статуса',
//         'error' => $e->getMessage()
//     ]);
// }