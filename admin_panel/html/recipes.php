<?php

session_start();

require_once __DIR__ . '/../../connect/connect.php';



$sql_count_recipes = "SELECT COUNT(*) as total_recipes FROM recipes";
$result = mysqli_query($connect, $sql_count_recipes);
$row = mysqli_fetch_assoc($result);
$total_recipes = $row['total_recipes']; // Получаем общее количество пользователей


$sql_month_recipes = "SELECT COUNT(*) as month_recipes FROM recipes WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$result_month = mysqli_query($connect, $sql_month_recipes);
$row_month = mysqli_fetch_assoc($result_month);
$month_recipes = $row_month['month_recipes'];


$sql = "SELECT 
           ROW_NUMBER() OVER (ORDER BY id) AS row_num,
           id,
           name,
           cooking_time,
           calorie,
           portions,
           caregories,
           maun_image
        FROM recipes";
$result = mysqli_query($connect, $sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/recipes.css" />
  <title>Пользователи</title>
</head>

<body>
  <div class="container">
    <section class="sidebar">
      <a class="header_logo">
        <img src="/image/лого.svg" class="header_logo_img" />
      </a>

      <div class="sidebar_nav">
        <a href="/admin_panel/html/users.html" class="sidebar_nav_link users">Пользователи</a>
        <a href="" class="sidebar_nav_link reviews">Отзывы</a>
        <a href="" class="sidebar_nav_link" style="text-decoration-line: underline;">Рецепты</a>
        <a href="" class="sidebar_nav_link">Блоги</a>

        <buttom class="nav_btn"><a href="new_recipes.html">Создать рецепт</a></buttom>
      </div>
    </section>

    <section class="contant">
      <section class="search">
        <div class="find">
          <input class="search_inp" type="text" placeholder="Поиск.." />
          <button class="search_btn">Поиск</button>
        </div>
      </section>

      <section class="container_review">
        <div class="container_reviews_info">
          <h1 class="container_title">Рецепты</h1>
          <div class="review">
            <p class="all_review">Общее кол-во: <?= $total_recipes ?></p>
            <p class="for_month_review">Кол-во добавленных за посл-ий месяц: <?= $month_recipes ?></p>

            <button class="filter-mobile-toggle">Фильтры</button>
            <div class="catalog_filter">
              <div class="catalog_filter_content">
                <button class="close-filters">×</button>
                <h1 class="catalog_filter_title">Фильтр</h1>

                <div class="catalog_filter_column"> <!-- ˅ -->
                  <p class="calalog_fil_col_title">⮟ Категории</p>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox1" id="wr10" name="wr">
                    <label for="wr10"></label>
                    <p class="paragraph_text">Торты</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox11" id="wr11" name="wr">
                    <label for="wr11"></label>
                    <p class="paragraph_text">Печенье</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox12" id="wr12" name="wr">
                    <label for="wr12"></label>
                    <p class="paragraph_text">Пироги</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox13" id="wr13" name="wr">
                    <label for="wr13"></label>
                    <p class="paragraph_text">Конфеты</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox14" id="wr14" name="wr">
                    <label for="wr14"></label>
                    <p class="paragraph_text">Хлеб</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox15" id="wr15" name="wr">
                    <label for="wr15"></label>
                    <p class="paragraph_text">Кексы</p>
                  </div>
                </div>

                <div class="catalog_filter_column">
                  <p class="calalog_fil_col_title">⮟ По дате</p>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox16" id="wr16" name="wr">
                    <label for="wr16"></label>
                    <p class="paragraph_text">Недание</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox17" id="wr17" name="wr">
                    <label for="wr17"></label>
                    <p class="paragraph_text">Старые</p>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <table class="table_users">
          <tr class="table_row_titles">
            <th>№</th>
            <th>Название</th>
            <th>Время при-ия</th>
            <th>Калор-ость</th>
            <th>Кол-во порций</th>
            <th>Категория</th>
            <th>Фотография</th>
            <th> </th>
          </tr>

          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row">

              <td><?= $row['row_num'] ?></td>
              <td><?= $row['name'] ?></td>
              <td><?= $row['cooking_time'] ?></td>
              <td><?= $row['calorie'] ?></td>
              <td><?= $row['portions'] ?></td>
              <td><?= $row['caregories'] ?></td>
              <td><?= basename($row['maun_image']) ?></td>
              <td class="more"><button class="more_btn"><a href="more.php?id=<?= $row['id'] ?>">Подробнее</a></button></td>
            </tr>
          <?php endwhile; ?>



          <!-- <tr class="table_row">
            <td>1</td>
            <td>Овсяное печенье</td>
            <td>2:20 ч</td>
            <td>451 ккал</td>
            <td>4</td>
            <td>Печенье</td>
            <td>
              <p>image 43</p>
            </td>
            
          </tr> -->

          <!-- Другие строки таблицы -->
        </table>


      </section>
    </section>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Получаем все чекбоксы с классами, начинающимися на "wr-checkbox"
      const checkboxes = document.querySelectorAll('input[type="checkbox"][class^="wr-checkbox"]');

      // Загружаем сохраненные состояния или создаем новый объект
      const savedStates = JSON.parse(localStorage.getItem('checkboxStates')) || {};

      // Применяем сохраненные состояния
      checkboxes.forEach(checkbox => {
        // Используем комбинацию class + id как уникальный ключ
        const storageKey = `${checkbox.className}_${checkbox.id}`;

        if (savedStates[storageKey] !== undefined) {
          checkbox.checked = savedStates[storageKey];
        }

        // Добавляем обработчик изменений
        checkbox.addEventListener('change', function () {
          // Обновляем состояние для этого конкретного чекбокса
          savedStates[storageKey] = this.checked;

          // Сохраняем обновленные состояния
          localStorage.setItem('checkboxStates', JSON.stringify(savedStates));

          console.log(`Сохранено состояние для ${storageKey}:`, this.checked);
        });
      });
    });
  </script>
  <script src="/js/catalog.js"></script>
</body>

</html>