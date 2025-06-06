<?php

session_start();
require_once __DIR__ . '/../../connect/connect.php';

$name = $_POST['name'];
$cooking_time = $_POST['cooking_time'];
$calorie = $_POST['calorie'];
$portions = $_POST['portions'];
$caregories = $_POST['caregories'];
$maun_image = $_FILES['maun_image'];
$description = $_POST['description'];
$ingredients = $_POST['ingredients'];

if (empty($name) || empty($cooking_time) || empty($caregories)) {
    die('Не все обязательные поля заполнены');
}




if (isset($_FILES['maun_image']) && $_FILES['maun_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../image/recipe/';
    
    // Проверяем и создаем директорию
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Проверяем тип файла
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($_FILES['maun_image']['tmp_name']);
    
    if (!in_array($fileType, $allowedTypes)) {
        die('Недопустимый тип файла. Разрешены только JPEG, PNG и GIF');
    }
    
    // Генерируем уникальное имя файла
    $extension = pathinfo($_FILES['maun_image']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $extension;
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['maun_image']['tmp_name'], $targetPath)) {
        $maun_image = '/image/recipe/' . $fileName;
    } else {
        die('Ошибка при загрузке файла');
    }
}


// Подготовленный запрос для защиты от SQL-инъекций

$date = new DateTime();
$created_at = $date->format('Y-m-d');

try {
    $stmt = $connect->prepare("INSERT INTO recipes 
        (name, cooking_time, calorie, portions, caregories, maun_image, description, ingredients, number_review, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");
    
$stmt->bind_param(
    'ssiisssss', 
    $name, 
    $cooking_time, 
    $calorie, 
    $portions, 
    $caregories, 
    $maun_image, 
    $description, 
    $ingredients,
    $created_at
);
    
    if (!$stmt->execute()) {
        throw new Exception('Ошибка при сохранении рецепта: ' . $stmt->error);
    }
    
    // Успешное завершение
    header('Location: /admin_panel/html/recipes.php?success=1');
    exit();
    
} catch (Exception $e) {
    // Удаляем загруженный файл, если запрос не удался
    if (!empty($maun_image)) {
        $filePath = __DIR__ . '/../../' . ltrim($maun_image, '/');
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    die('Ошибка: ' . $e->getMessage());
}


// $sql = "INSERT INTO recipes (id, name, cooking_time, calorie, portions, caregories, maun_image, description, ingredients, number_review, created_at)
//  VALUES (NULL, '$name', '$cooking_time', '$calorie', $portions, '$caregories', '$maun_image', '$description', '$ingredients')";
