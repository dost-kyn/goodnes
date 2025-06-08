<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';

// Проверка ID блога
$blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($blog_id <= 0) {
    die(json_encode(['success' => false, 'message' => 'Неверный ID блога']));
}

// Обработка загруженных файлов
foreach ($_FILES as $key => $file) {
    if (strpos($key, 'step_image_') === 0 && $file['error'] === UPLOAD_ERR_OK) {
        $step_number = str_replace('step_image_', '', $key);
        
        // Проверка типа файла
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $file['tmp_name']);
        finfo_close($file_info);
        
        if (!in_array($mime_type, $allowed_types)) {
            continue;
        }
        
        // Создаем папку для загрузки, если её нет
        $upload_dir = __DIR__ . '/../../image/blog/steps/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Генерируем уникальное имя файла
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = 'blog_' . $blog_id . '_step_' . $step_number . '_' . uniqid() . '.' . $file_ext;
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // Обновляем путь в базе данных
            $image_path = '/image/blog/steps/' . $file_name;
            $sql = "UPDATE blog_steps SET image = ? WHERE blog_id = ? AND step_number = ?";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $image_path, $blog_id, $step_number);
            mysqli_stmt_execute($stmt);
        }
    }
}

echo json_encode(['success' => true]);
?>