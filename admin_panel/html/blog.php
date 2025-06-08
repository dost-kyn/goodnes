<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';


// Проверка соединения
if (!$connect) {
  die("Ошибка подключения: " . mysqli_connect_error());
}

$sql = "SELECT 
           ROW_NUMBER() OVER (ORDER BY id) AS row_num,
           id,
           name,
           number_image,
           created_at	

        FROM blogs";
$result = mysqli_query($connect, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/recipes.css" />
  <title>Блоги</title>
</head>

<body>
  <div class="container">
    <section class="sidebar">
      <a class="header_logo">
        <img src="/image/лого.svg" class="header_logo_img" />
      </a>

      <div class="sidebar_nav">
        <a href="users.php" class="sidebar_nav_link users">Пользователи</a>
        <a href="reviews.php" class="sidebar_nav_link reviews">Отзывы</a>
        <a href="recipes.php" class="sidebar_nav_link">Рецепты</a>
        <a href="blog.php" class="sidebar_nav_link" style="text-decoration-line: underline;">Блоги</a>


        <buttom class="nav_btn"><a href="new_blog.php">Создать блог</a></buttom>
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
          <h1 class="container_title">Блоги</h1>
        </div>

        <table class="table_users">
          <tr class="table_row_titles">
            <th>№</th>
            <th>Название</th>
            <th>Кол-во фотографий</th>
            <th>Дата публикации</th>
            <th> </th>
          </tr>


          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row">
              <td><?= $row['row_num'] ?></td>
              <td><?= $row['name'] ?></td>
              <td><?= $row['number_image'] ?></td>
              <td><?= $row['created_at'] ?></td>
              <td class="more"><button class="more_btn"><a href="more_blog.php?id=<?= $row['id'] ?>">Подробнее</a></button></td>
            </tr>
          <?php endwhile; ?>

          <!-- <tr class="table_row">
            <td>2</td>
            <td>Мука: какая подходит для какой выпечки</td>
            <td>8</td>
            <td>12.04.2025</td>
            <td class="more"><button class="more_btn"><a href="more_blog.html">Подробнее</a></button></td>
          </tr> -->


        </table>


      </section>
    </section>
  </div>
  <script src="/js/catalog.js"></script>
</body>

</html>