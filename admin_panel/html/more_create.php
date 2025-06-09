<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Сохраняем основную информацию о рецепте
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $cooking_time = mysqli_real_escape_string($connect, $_POST['cooking_time']);
    $calorie = mysqli_real_escape_string($connect, $_POST['calorie']);
    $portions = mysqli_real_escape_string($connect, $_POST['portions']);
    $caregories = mysqli_real_escape_string($connect, $_POST['caregories']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $ingredients = mysqli_real_escape_string($connect, $_POST['ingredients']);

    $maun_image = '';
    if (isset($_FILES['maun_image']) && $_FILES['maun_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '/image/recipe/main/';
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $upload_dir)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $upload_dir, 0777, true);
        }

        $file_ext = pathinfo($_FILES['maun_image']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_ext;
        $maun_image = $upload_dir . $new_filename;
        $upload_path = $_SERVER['DOCUMENT_ROOT'] . $maun_image;

        if (!move_uploaded_file($_FILES['maun_image']['tmp_name'], $upload_path)) {
            $_SESSION['error'] = "Ошибка при загрузке главного изображения";
            header("Location: new_recipes.php");
            exit();
        }
    }

    $created_at = date('Y-m-d H:i:s');
$bons=0;
    $sql_recipe = "INSERT INTO recipes (name, cooking_time, calorie, portions, caregories, maun_image, description, ingredients,number_review, created_at) 
                   VALUES ('$name', '$cooking_time', '$calorie', '$portions', '$caregories', '$maun_image', '$description', '$ingredients','$bons', '$created_at')";

    if (!mysqli_query($connect, $sql_recipe)) {
        $_SESSION['error'] = "Ошибка при сохранении рецепта: " . mysqli_error($connect);
        header("Location: new_recipes.php");
        exit();
    }
    
    $recipe_id = mysqli_insert_id($connect);

    // 2. Сохраняем шаги рецепта
    if (isset($_POST['step_description']) && is_array($_POST['step_description'])) {
    foreach ($_POST['step_description'] as $i => $step_desc) {
        $step_number = $i + 1;
        $step_description = mysqli_real_escape_string($connect, $step_desc);

        $image_name = '';
        if (isset($_FILES['step_image']['error'][$i]) && $_FILES['step_image']['error'][$i] == UPLOAD_ERR_OK) {
            $upload_dir = 'image/recipe/steps/'; 
            $full_dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $upload_dir;
            
            if (!file_exists($full_dir)) {
                mkdir($full_dir, 0777, true);
            }

            $file_ext = pathinfo($_FILES['step_image']['name'][$i], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_ext;
            $image_name = $upload_dir . $new_filename;
            $upload_path = $full_dir . $new_filename;

            if (!move_uploaded_file($_FILES['step_image']['tmp_name'][$i], $upload_path)) {
                error_log("Ошибка загрузки файла шага $step_number");
                $_SESSION['error'] = "Ошибка при загрузке изображения для шага $step_number";
                continue;
            }
        }

        $sql_step = "INSERT INTO recipe_steps (recipe_id, step_number, description, image_path) 
                    VALUES ('$recipe_id', '$step_number', '$step_description', '$image_name')";
        
        if (!mysqli_query($connect, $sql_step)) {
            error_log("Ошибка SQL при сохранении шага: " . mysqli_error($connect));
            $_SESSION['error'] = "Ошибка при сохранении шага $step_number: " . mysqli_error($connect);
        } else {
            error_log("Успешно сохранён шаг $step_number для рецепта $recipe_id");
        }
    }
}

    $_SESSION['success'] = "Рецепт успешно сохранен!";
    header("Location: recipes.php");
    exit();
} else {
    header("Location: new_recipes.php");
    exit();
}