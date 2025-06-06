<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../connect/connect.php';

// Включим отображение ошибок для отладки (на время разработки)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Функция для загрузки файла
function uploadFile($file, $targetDir, $isStep = false) {
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $extension;
    $targetPath = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Для шагов добавляем 'steps/' в путь
        return $isStep ? '/image/recipe/steps/' . $fileName : '/image/recipe/' . $fileName;
    }
    return false;
}

try {
    // Проверяем метод запроса
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Получаем ID рецепта
    $recipe_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($recipe_id <= 0) {
        throw new Exception('Invalid recipe ID');
    }

    mysqli_begin_transaction($connect);

    // 1. Обновление основных полей рецепта
    $fields = [
        'name', 'cooking_time', 'calorie', 'portions', 
        'caregories', 'description', 'ingredients'
    ];
    
    $updates = [];
    $params = [];
    $types = '';
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $updates[] = "$field = ?";
            $params[] = $_POST[$field];
            $types .= 's';
        }
    }
    
    if (!empty($updates)) {
        $sql = "UPDATE recipes SET " . implode(', ', $updates) . " WHERE id = ?";
        $params[] = $recipe_id;
        $types .= 'i';
        
        $stmt = $connect->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
    }

    // 2. Обработка главного изображения
    if (!empty($_FILES['maun_image']['tmp_name'])) {
        $uploadDir = __DIR__ . '/../../image/recipe/';
        $imagePath = uploadFile($_FILES['maun_image'], $uploadDir);
        
        if ($imagePath) {
            $stmt = $connect->prepare("UPDATE recipes SET maun_image = ? WHERE id = ?");
            $stmt->bind_param('si', $imagePath, $recipe_id);
            $stmt->execute();
        } else {
            throw new Exception('Failed to upload main image');
        }
    }

    // 3. Обработка шагов рецепта
    foreach ($_FILES as $key => $file) {
        if (strpos($key, 'step_image_') === 0 && !empty($file['tmp_name'])) {
            $step_number = str_replace('step_image_', '', $key);
            $uploadDir = __DIR__ . '/../../image/recipe/steps/';
            $imagePath = uploadFile($file, $uploadDir, true);
            
            if ($imagePath) {
                // Проверяем существование шага
                $stmt = $connect->prepare("SELECT id FROM recipe_steps WHERE recipe_id = ? AND step_number = ?");
                $stmt->bind_param('ii', $recipe_id, $step_number);
                $stmt->execute();
                $exists = $stmt->get_result()->num_rows > 0;
                
                if ($exists) {
                    $stmt = $connect->prepare("UPDATE recipe_steps SET image_path = ? WHERE recipe_id = ? AND step_number = ?");
                } else {
                    $stmt = $connect->prepare("INSERT INTO recipe_steps (image_path, recipe_id, step_number) VALUES (?, ?, ?)");
                }
                $stmt->bind_param('sii', $imagePath, $recipe_id, $step_number);
                $stmt->execute();
            }
        }
    }

    // 4. Обновление описаний шагов
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'step_description_') === 0) {
            $step_number = str_replace('step_description_', '', $key);
            
            $stmt = $connect->prepare("SELECT id FROM recipe_steps WHERE recipe_id = ? AND step_number = ?");
            $stmt->bind_param('ii', $recipe_id, $step_number);
            $stmt->execute();
            $exists = $stmt->get_result()->num_rows > 0;
            
            if ($exists) {
                $stmt = $connect->prepare("UPDATE recipe_steps SET description = ? WHERE recipe_id = ? AND step_number = ?");
            } else {
                $stmt = $connect->prepare("INSERT INTO recipe_steps (description, recipe_id, step_number) VALUES (?, ?, ?)");
            }
            $stmt->bind_param('sii', $value, $recipe_id, $step_number);
            $stmt->execute();
        }
    }

    mysqli_commit($connect);
    echo json_encode(['success' => true, 'message' => 'Рецепт успешно обновлен']);

} catch (Exception $e) {
    if (isset($connect)) {
        mysqli_rollback($connect);
    }
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}