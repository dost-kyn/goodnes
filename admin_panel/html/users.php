<?php

session_start();

require_once __DIR__ . '/../../connect/connect.php';


$sql_count_users = "SELECT COUNT(*) as total_users FROM users";
$result = mysqli_query($connect, $sql_count_users);
$row = mysqli_fetch_assoc($result);
$total_users = $row['total_users']; // Получаем общее количество пользователей


$sql_month_users = "SELECT COUNT(*) as month_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$result_month = mysqli_query($connect, $sql_month_users);
$row_month = mysqli_fetch_assoc($result_month);
$month_users = $row_month['month_users'];


$sql = "SELECT 
           ROW_NUMBER() OVER (ORDER BY id) AS row_num,
           id,
           name,
           numder_recipes,
           password
        FROM users";
$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/admin_panel/css/users.css">
    <title>Пользователи</title>
</head>

<body>
    <div class="container">
        <section class="sidebar">
            <a class="header_logo">
                <img src="/image/лого.svg" class="header_logo_img">
            </a>

            <div class="sidebar_nav">
                <a href="/admin_panel/html/users.html" class="sidebar_nav_link users">Пользователи</a>
                <a href="reviews.php" class="sidebar_nav_link">Отзывы</a>
                <a href="" class="sidebar_nav_link">Рецепты</a>
                <a href="" class="sidebar_nav_link">Блоги</a>
            </div>
        </section>



        <section class="contant">
            <section class="search">
                <div class="find">
                    <input class="search_inp" type="text" placeholder="Поиск..">
                    <button class="search_btn">Поиск</button>
                </div>

            </section>


            <section class="container_users">
                <div class="container_users_info">
                    <h1 class="container_title">Пользователи</h1>
                    <div class="people">
                        <p class="all_people">Всего пользователей: <?= $total_users ?></p>
                        <p class="for_month_people">Кол-во зарег-шихся за последний месяц: <?= $month_users ?></p>
                    </div>
                </div>


                <table class="table_users">
                    <tr class="table_row_titles">
                        <th class="table_row_title">№</th>
                        <th class="table_row_title">Имя</th>
                        <th class="table_row_title">Кол-во сохранненых рецептов</th>
                    </tr>


                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="table_row">
                            <td><?= $row['row_num'] ?></td>
                            <!-- <td><?= htmlspecialchars($row['name']) ?></td> -->
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['numder_recipes'] ?></td> 
                        </tr>
                    <?php endwhile; ?>

                </table>


            </section>
        </section>
    </div>
</body>

</html>