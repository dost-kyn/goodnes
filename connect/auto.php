<?php
session_start();
require_once 'connect.php';

$email = $_POST['email'];
$password = $_POST['password'];
$recipe_id = isset($_POST['recipe_id']) ? intval($_POST['recipe_id']) : 0;

// Проверяем пользователя
$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `email` = '$email'");
$user = mysqli_fetch_assoc($check_user);

if (!$user) {
    $_SESSION['message'] = 'Пользователь с такой почтой не найден';
    
    // Если это запрос с recipe_page (AJAX)
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Пользователь с такой почтой не найден']);
        exit();
    }
    
    // Обычный редирект
    header('Location: ../components/modal_auth.php');
    exit();
}

if (password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email']
    ];

    // Сохраняем рецепт, если передан recipe_id
    if ($recipe_id > 0) {
        $save_query = "INSERT INTO saved_recipes (user_id, recipe_id) VALUES ('{$user['id']}', '$recipe_id')";
        mysqli_query($connect, $save_query);
    }

    // Если это AJAX-запрос
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => true]);
        exit();
    }

    // Обычный редирект
    if ($user['id'] == 1) {
        header('Location: ../admin_panel/html/users.php');
    } else {
        header('Location: ../html/home.php');
    }
    exit();
} else {
    $_SESSION['message'] = 'Неверный пароль';
    
    // Если это AJAX-запрос
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
        exit();
    }
    
    // Обычный редирект
    header('Location: ../components/modal_auth.php');
    exit();
}