<?php

session_start();
require_once 'connect.php';


$email = $_POST['email'];
$password = $_POST['password']; // Не хешируем
$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `email` = '$email'");
$user = mysqli_fetch_assoc($check_user);




if (!$user) {
    $_SESSION['message'] = 'Пользователь с такой почтой не найден';
    header('Location: ../components/modal_auth.php');
    exit();
}

if (password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email']
    ];

    if ($user['id'] == 1) {
        header('Location: ../admin/admin.php');
    } else {
        header('Location: ../html/home.php');
    }
    exit();
} else {
    $_SESSION['message'] = 'Неверный пароль';
    header('Location: ../components/modal_auth.php');
    exit();
}