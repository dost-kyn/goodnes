<!-- <?php

session_start();
require_once __DIR__ . '/../../connect/connect.php';

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // 1. Обработка основных данных рецепта
//     $name = $_POST['name'];
//     $cooking_time = $_POST['cooking_time'];
//     $calorie = $_POST['calorie'];
//     $portions = $_POST['portions'];
//     $caregories = $_POST['caregories'];
//     $description = $_POST['description'];
//     $ingredients = $_POST['ingredients'];
//     $created_at = date('Y-m-d H:i:s');

//     // Валидация обязательных полей
//     if (empty($name) || empty($cooking_time) || empty($caregories)) {
//         $_SESSION['error'] = "Не все обязательные поля заполнены";
//         header('Location: /admin_panel/html/more_create.php'); //
//         exit();
//     }

//     // 2. Обработка главного изображения
//     $maun_image = '';
//     if (isset($_FILES['maun_image']) && $_FILES['maun_image']['error'] === UPLOAD_ERR_OK) {
//         $uploadDir = __DIR__ . '/../../image/recipe/main/';

//         if (!file_exists($uploadDir)) {
//             mkdir($uploadDir, 0777, true);
//         }

//         $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
//         $fileType = mime_content_type($_FILES['maun_image']['tmp_name']);

//         if (!in_array($fileType, $allowedTypes)) {
//             $_SESSION['error'] = "Недопустимый тип файла. Разрешены только JPEG, PNG и GIF";
//             header('Location: /admin_panel/html/more_create.php'); //
//             exit();
//         }

//         $extension = pathinfo($_FILES['maun_image']['name'], PATHINFO_EXTENSION);
//         $fileName = uniqid() . '.' . $extension;
//         $targetPath = $uploadDir . $fileName;

//         if (move_uploaded_file($_FILES['maun_image']['tmp_name'], $targetPath)) {
//             $maun_image = '/image/recipe/main/' . $fileName;
//         } else {
//             $_SESSION['error'] = "Ошибка при загрузке главного изображения";
//             header('Location: /admin_panel/html/more_create.php'); //
//             exit();
//         }
//     }

//     // 3. Сохранение основного рецепта
//     try {
//         $stmt = $connect->prepare("INSERT INTO recipes 
//             (name, cooking_time, calorie, portions, caregories, maun_image, description, ingredients, created_at) 
//             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

//         $stmt->bind_param(
//             'ssiisssss',
//             $name,
//             $cooking_time,
//             $calorie,
//             $portions,
//             $caregories,
//             $maun_image,
//             $description,
//             $ingredients,
//             $created_at
//         );

//         if (!$stmt->execute()) {
//             throw new Exception('Ошибка при сохранении рецепта: ' . $stmt->error);
//         }

//         $recipe_id = $stmt->insert_id;

//         // 4. Обработка и сохранение этапов рецепта
//         if (!empty($_POST['step_description'])) {
//             // Если один этап (не массив)
//             if (!is_array($_POST['step_description'])) {
//                 $step_descriptions = [$_POST['step_description']];
//                 $step_images = [$_FILES['step_image']];
//             } else {
//                 $step_descriptions = $_POST['step_description'];
//                 $step_images = $_FILES['step_image'];
//             }

//             foreach ($step_descriptions as $i => $step_desc) {
//                 $step_number = $i + 1;
//                 $step_description = $step_desc;
//                 $step_image_path = '';

//                 // // Обработка изображения для этапа
//                 // if (isset($step_images['name'][$i]) && $step_images['error'][$i] === UPLOAD_ERR_OK) {
//                 //     $uploadDirSteps = __DIR__ . '/../../image/recipe/steps/';

//                 //     if (!file_exists($uploadDirSteps)) {
//                 //         mkdir($uploadDirSteps, 0777, true);
//                 //     }

//                 //     $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
//                 //     $fileType = mime_content_type($step_images['tmp_name'][$i]);

//                 //     if (!in_array($fileType, $allowedTypes)) {
//                 //         continue; // Пропускаем этот этап, но продолжаем обработку
//                 //     }

//                 //     $extension = pathinfo($step_images['name'][$i], PATHINFO_EXTENSION);
//                 //     $fileName = uniqid() . '.' . $extension;
//                 //     $targetPath = $uploadDirSteps . $fileName;

//                 //     if (move_uploaded_file($step_images['tmp_name'][$i], $targetPath)) {
//                 //         $step_image_path = '/image/recipe/steps/' . $fileName;
//                 //     }
//                 // }

//                 // Сохранение этапа
//                 $stmt_step = $connect->prepare("INSERT INTO recipe_steps 
//                     (recipe_id, step_number, description, image_path, created_at) 
//                     VALUES (?, ?, ?, ?, ?)");

//                 $stmt_step->bind_param(
//                     'iisss',
//                     $recipe_id,
//                     $step_number,
//                     $step_description,
//                     $step_image_path,
//                     $created_at
//                 );

//                 if (!$stmt_step->execute()) {
//                     error_log("Ошибка при сохранении шага: " . $stmt_step->error);
//                     continue; // Пропускаем ошибку шага, но продолжаем обработку
//                 }
//             }
//         }

//         $_SESSION['success'] = "Рецепт успешно сохранен!";
//         header('Location: /admin_panel/html/more_create.php');//
//         exit();

//     } catch (Exception $e) {
//         // Удаляем загруженные файлы, если запрос не удался
//         if (!empty($maun_image)) {
//             $filePath = __DIR__ . '/../../' . ltrim($maun_image, '/');
//             if (file_exists($filePath)) {
//                 unlink($filePath);
//             }
//         }

//         $_SESSION['error'] = 'Ошибка: ' . $e->getMessage();
//         header('Location: /admin_panel/html/more_create.php'); //
//         exit();
//     }
// }




















$name = $_POST['name'];
$cooking_time = $_POST['cooking_time'];
$calorie = $_POST['calorie'];
$portions = $_POST['portions'];
$caregories = $_POST['caregories'];
$maun_image = $_FILES['maun_image'];
$description = $_POST['description'];
$ingredients = $_POST['ingredients'];

$step_description = $_POST['step_description'];
$step_image = $_FILES['step_image'];

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


// if (isset($_FILES['step_image']) && $_FILES['step_image']['error'] === UPLOAD_ERR_OK) {
//     $uploadDir2 = __DIR__ . '/../../image/recipe/steps/';

//     // Проверяем и создаем директорию
//     if (!file_exists($uploadDir2)) {
//         mkdir($uploadDir2, 0777, true);
//     }

//     // Проверяем тип файла
//     $allowedTypes2 = ['image/jpeg', 'image/png', 'image/gif'];
//     $fileType2 = mime_content_type($_FILES['step_image']['tmp_name']);

//     if (!in_array($fileType2, $allowedTypes2)) {
//         die('Недопустимый тип файла. Разрешены только JPEG, PNG и GIF');
//     }

//     // Генерируем уникальное имя файла
//     $extension2 = pathinfo($_FILES['step_image']['name'], PATHINFO_EXTENSION);
//     $fileName2 = uniqid() . '.' . $extension2;
//     $targetPath2 = $uploadDir2 . $fileName2;

//     if (move_uploaded_file($_FILES['step_image']['tmp_name'], $targetPath2)) {
//         $step_image = '/image/recipe/steps/' . $fileName2;
//     } else {
//         die('Ошибка при загрузке файла');
//     }
// }

$recipe_id = $stmt->insert_id;

if (!empty($_POST['step_description'])) {
    // Если один этап (не массив)
    if (!is_array($_POST['step_description'])) {
        $step_descriptions = [$_POST['step_description']];
        // $step_images = [$_FILES['step_image']];
    } else {
        $step_descriptions = $_POST['step_description'];
        // $step_images = $_FILES['step_image'];
    }

    foreach ($step_descriptions as $i => $step_desc) {
        $step_number = $i + 1;
        $step_description = $step_desc;
        $step_image = '';


        if (isset($_FILES['step_image']) && $_FILES['step_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir2 = __DIR__ . '/../../image/recipe/steps/';

            // Проверяем и создаем директорию
            if (!file_exists($uploadDir2)) {
                mkdir($uploadDir2, 0777, true);
            }

            // Проверяем тип файла
            $allowedTypes2 = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType2 = mime_content_type($_FILES['step_image']['tmp_name']);

            if (!in_array($fileType2, $allowedTypes2)) {
                die('Недопустимый тип файла. Разрешены только JPEG, PNG и GIF');
            }

            // Генерируем уникальное имя файла
            $extension2 = pathinfo($_FILES['step_image']['name'], PATHINFO_EXTENSION);
            $fileName2 = uniqid() . '.' . $extension2;
            $targetPath2 = $uploadDir2 . $fileName2;

            if (move_uploaded_file($_FILES['step_image']['tmp_name'], $targetPath2)) {
                $step_image = '/image/recipe/steps/' . $fileName2;
            } else {
                die('Ошибка при загрузке файла');
            }
        }


        $stmt_step = $connect->prepare("INSERT INTO recipe_steps 
                    (recipe_id, step_number, description, image_path, created_at) 
                    VALUES (?, ?, ?, ?, ?)");

        $stmt_step->bind_param(
            'iisss',
            $recipe_id,
            $step_number,
            $step_description,
            $step_image,
            $created_at
        );

        if (!$stmt_step->execute()) {
            error_log("Ошибка при сохранении шага: " . $stmt_step->error);
            continue; // Пропускаем ошибку шага, но продолжаем обработку
        }
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


    $stmt2 = $connect->prepare("INSERT INTO recipe_steps 
        (recipe_id, step_number, description, maun_image, created_at) 
        VALUES (?, ?, ?, ?, ?)");

    $stmt2->bind_param(
        'iisss',
        $recipe_id,
        $step_number,
        $description,
        $maun_image,
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
//  VALUES (NULL, '$name', '$cooking_time', '$calorie', $portions, '$caregories', '$maun_image', '$description', '$ingredients')"; -->
