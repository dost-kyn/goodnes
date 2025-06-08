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


// функция для подсчёта и сохранения сохранненых рецептов пользователями
function updateSavedRecipesCount($connect)
{
    // Получаем количество сохранённых рецептов для каждого пользователя
    $sql = "SELECT user_id, COUNT(*) as saved_count 
            FROM saved_recipes 
            GROUP BY user_id";

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        die("Ошибка при подсчёте сохранённых рецептов: " . mysqli_error($connect));
    }

    // Обновляем количество сохранённых рецептов для каждого пользователя
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $saved_count = $row['saved_count'];

        $update_sql = "UPDATE users 
                      SET numder_recipes = $saved_count 
                      WHERE id = $user_id";

        if (!mysqli_query($connect, $update_sql)) {
            die("Ошибка при обновлении количества рецептов для пользователя $user_id: " . mysqli_error($connect));
        }
    }

    return true;
}
// Пример использования:
updateSavedRecipesCount($connect);


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
                <a href="users.php" class="sidebar_nav_link users">Пользователи</a>
                <a href="reviews.php" class="sidebar_nav_link reviews">Отзывы</a>
                <a href="recipes.php" class="sidebar_nav_link">Рецепты</a>
                <a href="blog.php" class="sidebar_nav_link">Блоги</a>
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
                            <td class="table_cell"><?= $row['row_num'] ?></td>
                            <td class="table_cell name_user"><?= $row['name'] ?></td>
                            <td class="table_cell"><?= $row['numder_recipes'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>


                <!-- <table class="table_users">
                    <tr class="table_row_titles">
                        <th class="table_row_title">№</th>
                        <th class="table_row_title">Имя</th>
                        <th class="table_row_title">Кол-во сохранненых рецептов</th>
                    </tr>


                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="table_row">
                            <td><?= $row['row_num'] ?></td>
                            <td class="name_user"><?= $row['name'] ?></td>
                            <td><?= $row['numder_recipes'] ?></td> 
                        </tr>
                    <?php endwhile; ?>

                </table> -->


            </section>
        </section>
    </div>
</body>
<script>
    //  ПОИСК 
    document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search_inp');
    const searchBtn = document.querySelector('.search_btn');
    const tableRows = document.querySelectorAll('.table_row:not(.table_row_titles)');
    const originalDisplay = [];

    // Сохраняем оригинальное состояние строк
    tableRows.forEach(row => {
        originalDisplay.push(row.style.display);
    });

    // Функция поиска
    function performSearch() {
        const query = searchInput.value.trim().toLowerCase();
        
        tableRows.forEach(row => {
            const nameCell = row.querySelector('.name_user');
            const name = nameCell.textContent.toLowerCase();
            
            if (name.includes(query)) {
                row.style.display = ''; // Возвращаем оригинальное значение
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Функция сброса поиска
    function resetSearch() {
        searchInput.value = '';
        tableRows.forEach((row, index) => {
            row.style.display = originalDisplay[index] || '';
        });
    }

    // Обработчики событий
    searchBtn.addEventListener('click', performSearch);
    
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    searchInput.addEventListener('input', function () {
        if (searchInput.value.trim() === '') {
            resetSearch();
        }
    });
});

</script>

</html>