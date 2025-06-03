<?php

session_start();
require_once 'connect.php';

$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$repeat_pass = $_POST['repeat_pass'];



if(!$name || !$email){
    $_SESSION['message'] = 'Не все поля заполнены';
    header('Location: ../components/modal_reg.php');
}else{

if ($pass === $repeat_pass && $name && $email){

    $pass = password_hash($pass, PASSWORD_DEFAULT); //это хэширование пароля оно более надежгое чем md5()



$date = new DateTime();
$created_at = $date->format('Y-m-d');

$sql = "INSERT INTO users (id, name, email, password, numder_recipes, created_at, role) VALUES (NULL, '$name', '$email', '$pass', 0, '$created_at', 'user')";



    mysqli_query($connect, $sql);
$_SESSION['user'] = [
    'id' => mysqli_insert_id($connect), // ID нового пользователя
    'name' => $name,
    'email' => $email,
    'role' => 'user'
];

header('Location: ../html/home.php');
} else{
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: ../components/modal_reg.php');
}}