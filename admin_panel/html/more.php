<?php

session_start();

require_once __DIR__ . '/../../connect/connect.php';
// Получаем ID рецепта из URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Защита от SQL-инъекций
if ($recipe_id <= 0) {
    die("Неверный ID рецепта");
}


$sql = "SELECT r.*, rs.step_number, rs.description as step_description, rs.image_path 
        FROM recipes r
        LEFT JOIN recipe_steps rs ON r.id = rs.recipe_id
        WHERE r.id = ?
        ORDER BY rs.step_number";




$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $recipe_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);




// Получаем данные
$recipe = mysqli_fetch_assoc($result);
$steps = [];

if ($recipe) {
    do {
        if (!empty($recipe['step_number'])) {
            $steps[] = [
                'number' => $recipe['step_number'],
                'description' => $recipe['step_description'],
                'image_path' => $recipe['image_path']
            ];
        }
    } while ($recipe = mysqli_fetch_assoc($result));
    
    // Возвращаем указатель на первую строку
    mysqli_data_seek($result, 0);
    $recipe = mysqli_fetch_assoc($result);
}

if (!$recipe) {
    die("Рецепт не найден");
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/new_recipes.css" />
  <title>Овсяное печенье</title>
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
          <h1 class="container_title"><?= htmlspecialchars($recipe['name']) ?></h1>
        </div>

        <table class="table_users">
          <tr class="table_row">
            <th class="table_column_1">id</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['id']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Название</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['name']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Время при-ия</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['cooking_time']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Калор-ость</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['calorie']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Кол-во порций</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['portions']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Категория</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['caregories']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Главная фотография</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['maun_image']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Описание</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['description']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Ингридиенты</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['ingredients']) ?></th>
          </tr>
          <?php if (!empty($steps)): ?>
          <tr class="table_row">
            <th class="table_column_1" style="vertical-align: top;">Инструкция</th>
            <?php foreach ($steps as $step): ?>
            <th class="table_column_2">
              <p>Фото <?= htmlspecialchars($step['number']) ?>: <?= htmlspecialchars($step['image_path']) ?></p>
              <p><?= htmlspecialchars($step['description']) ?></p>
            </th>
            <?php endforeach; ?>
          </tr>
          <?php endif; ?>

          <!-- <tr class="table_row">
            <th class="table_column_1"> </th>
            <th class="table_column_2">
              <p>Фото 2: image 43</p>
              <p>Описание 2: Соединить тщательно сливочное масло комнатной температуры, сахар и соль
                силиконовой лопаткой.</p>
            </th>
          </tr> -->

          <tr class="table_row">
            <th class="table_column_1">Кол-во отзывов</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['number_review']) ?></th>
          </tr>

          <tr class="table_row">
            <th class="table_column_1">Дата создания</th>
            <th class="table_column_2"><?= htmlspecialchars($recipe['created_at']) ?></th>
          </tr>
        </table>

        <div class="container_review_buttons">
          <button class="btn"><a href="recipes.html">Вернуться</a></button>
          <button class="btn" id="editButton">Изменить</button>
          <button class="btn" id="saveButton" style="display: none;">Сохранить</button>
          <button class="btn">Удалить</button>
        </div>
      </section>
    </section>
  </div>
  <script src="/js/catalog.js"></script>
  <script>
    const editButton = document.querySelector('#editButton');
    const saveButton = document.querySelector('#saveButton');
    const editableCells = document.querySelectorAll('.table_column_2');


    function extractTextFromHTML(html) {
      const temp = document.createElement('div');
      temp.innerHTML = html;
      return temp.textContent || temp.innerText || '';
    }

    editButton.addEventListener('click', function () {
      editableCells.forEach(cell => {
        const content = cell.innerHTML;

        // Извлекаем только текст без HTML-тегов
        const textContent = extractTextFromHTML(content);

        if (content.includes('<p>') || content.includes('<br>') || textContent.length > 50) {
          const textarea = document.createElement('textarea');
          textarea.className = 'editable editable-textarea';
          textarea.value = textContent; 
          cell.innerHTML = '';
          cell.appendChild(textarea);
        }
        else {
          const input = document.createElement('input');
          input.className = 'editable';
          input.type = 'text';
          input.value = textContent;  
          cell.innerHTML = '';
          cell.appendChild(input);
        }
      });

      saveButton.style.display = "block";
      editButton.style.display = "none";
    })

    saveButton.addEventListener('click', function () {
      editableCells.forEach(cell => {
        const input = cell.querySelector('input, textarea');
        if (input) {
          // Для ячеек, которые изначально содержали абзацы, сохраняем с тегами <p>
          if (cell.dataset.hasParagraphs) {
            const paragraphs = input.value.split('\n').filter(p => p.trim() !== '');
            let newContent = '';
            paragraphs.forEach(p => {
              newContent += `<p>${p}</p>`;
            });
            cell.innerHTML = newContent;
          } else {
            cell.innerHTML = input.value;
          }
        }
      });

      saveButton.style.display = 'none';
      editButton.style.display = 'block';
      alert('Изменения сохранены!');
    });

    // При загрузке страницы отмечаем ячейки, которые содержат абзацы
    editableCells.forEach(cell => {
      if (cell.innerHTML.includes('<p>')) {
        cell.dataset.hasParagraphs = 'true';
      }
    });
  </script>
</body>

</html>