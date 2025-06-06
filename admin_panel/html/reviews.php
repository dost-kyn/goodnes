<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';

// Проверка соединения
if (!$connect) {
  die("Ошибка подключения: " . mysqli_connect_error());
}

// Запросы для статистики
$sql_count_reviews = "SELECT COUNT(*) as total_reviews FROM reviews";
$result = mysqli_query($connect, $sql_count_reviews);
$row = mysqli_fetch_assoc($result);
$total_reviews = $row['total_reviews'];

$sql_month_reviews = "SELECT COUNT(*) as month_reviews FROM reviews WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$result_month = mysqli_query($connect, $sql_month_reviews);
$row_month = mysqli_fetch_assoc($result_month);
$month_reviews = $row_month['month_reviews'];

$sql_pending_reviews = "SELECT COUNT(*) as pending_reviews FROM reviews WHERE status = 1";
$result_pending = mysqli_query($connect, $sql_pending_reviews);
$row_pending = mysqli_fetch_assoc($result_pending);
$pending_reviews = $row_pending['pending_reviews'];



// // пользователи
// $sql = "SELECT 
//        ROW_NUMBER() OVER (ORDER BY r.id) AS row_num,
//        r.id,
//        r.user_id,
//        u.name AS user_name,
//        r.recipe_id,
//        IFNULL(rec.name, 'Рецепт удалён') AS recipe_name,
//        r.text,
//        r.created_at,
//        r.status
//     FROM reviews r
//     LEFT JOIN users u ON r.user_id = u.id
//     LEFT JOIN recipes rec ON r.recipe_id = rec.id
//     ORDER BY r.id";


$sql = "SELECT 
       ROW_NUMBER() OVER (ORDER BY r.status ASC, r.id) AS row_num,
       r.id,
       r.user_id,
       u.name AS user_name,
       r.recipe_id,
       IFNULL(rec.name, 'Рецепт удалён') AS recipe_name,
       r.text,
       r.created_at,
       CASE 
         WHEN r.status = 1 THEN 'pending'
         WHEN r.status = 2 THEN 'approved' 
         WHEN r.status = 3 THEN 'rejected'
       END as status
    FROM reviews r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN recipes rec ON r.recipe_id = rec.id
    ORDER BY r.status ASC, r.id";

$result = mysqli_query($connect, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/reviews.css" />
  <title>Отзывы</title>

  <style>
    select.loading {
      background-color: #f8f8f8;
      opacity: 0.8;
      cursor: wait;
    }

    select:disabled {
      opacity: 1;
      /* Сохраняем видимость при блокировке */
    }
  </style>
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
          <h1 class="container_title">Отзывы</h1>
          <div class="review">
            <p class="all_review">Общее кол-во: <?= $total_reviews ?></p>
            <p class="for_month_review">Кол-во за последний месяц: <?= $month_reviews ?></p>
            <p class="review_time">Кол-во отзывов в ожидании: <?= $pending_reviews ?></p>
          </div>
        </div>

        <table class="table_users">


          <tr class="table_row_titles">
            <th>№</th>
            <th>Имя пользователя</th>
            <th>Название рецепта</th>
            <th>Текст отзыва</th>
            <th>Дата создания</th>
            <th>Статус</th>
          </tr>

          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row">
              <td><?= $row['row_num'] ?></td>
              <td><?= htmlspecialchars($row['user_name']) ?></td>
              <td><?= htmlspecialchars($row['recipe_name']) ?></td>
              <td class="review-text">
                <?= htmlspecialchars($row['text']) ?>
              </td>
              <td><?= date('d.m.Y', strtotime($row['created_at'])) ?></td>
              <td class="select_status">
                <select name="status" class="status-select" data-review-id="<?= $row['id'] ?>"
                    <?= $row['status'] == 'approved' ? 'disabled' : '' ?>>
                    <option value="1" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>В ожидании</option>
                    <option value="2" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Одобрено</option>
                    <option value="3" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Удалить</option>
                </select>
                <?php if ($row['status'] == 'approved'): ?>
                    <input type="hidden" name="status" value="2">
                <?php endif; ?>
            </td>
            </tr>
          <?php endwhile; ?>



          <!-- <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row">
              <td><?= $row['row_num'] ?></td>
              <td><?= htmlspecialchars($row['user_name']) ?></td>
              <td><?= htmlspecialchars($row['recipe_name']) ?></td>
              <td class="review-text">
                <?= htmlspecialchars($row['text']) ?>
              </td>
              <td><?= date('d.m.Y', strtotime($row['created_at'])) ?></td>
              <td class="select_status">
                <select name="status" class="status-select" data-review-id="<?= $row['id'] ?>">
                    <option value="1" <?= $row['status'] == 1 ? 'selected' : '' ?>>В ожидании</option>
                    <option value="2" <?= $row['status'] == 2 ? 'selected' : '' ?>>Одобрено</option>
                    <option value="3" <?= $row['status'] == 3 ? 'selected' : '' ?>>Удалить</option>
                </select>
              </td>
            </tr>
          <?php endwhile; ?> -->



          <!-- <tr class="table_row">
            <td>5</td>
            <td>Эдвин Рюдинг</td>
            <td>Печеньки</td>
            <td class="review-text">
              Я пробую этот рецепт первый раз, и знаете, я думаю что это очень
              вкусно. Тесто получилось нежным и воздушным, хотя я добавил
              немного меньше сахара, чем указано в рецепте. Мои дети были в
              восторге!
            </td>
            <td>12.04.2025</td>
            <td class="select_status">
              <select name="status">
                <option value="1">В ожидании</option>
                <option value="2">Одобрено</option>
                <option value="3">Удалить</option>
              </select>
            </td>
          </tr> -->

        </table>

        <div class="modal_overlay" id="deleteModal">
          <div class="content">
            <h3 class="modal_title">
              Вы действительно хотите удалить этот отзыв?
            </h3>
            <p>Выберите причину отказа:</p>

            <div class="modal_items">
              <div class="item">
                <input type="radio" id="reason1" name="reason" value="Ненормативная лексика" />
                <label for="reason1">Ненормативная лексика</label>
              </div>
              <div class="item">
                <input type="radio" id="reason2" name="reason" value="Спам" />
                <label for="reason2">Спам</label>
              </div>
              <div class="item">
                <input type="radio" id="reason3" name="reason" value="Клевета" />
                <label for="reason3">Клевета</label>
              </div>
            </div>

            <p class="answer" style="font-size: 14px">
              Пожалуйста, выберите причину удаления
            </p>

            <div class="modal_buttons">
              <button class="modal_btn modal-btn-no" id="cancelDelete">
                Нет
              </button>
              <button class="modal_btn modal-btn-yes" id="confirmDelete">
                Да
              </button>
            </div>
          </div>
        </div>
      </section>
    </section>
  </div>
  <script>









    document.addEventListener('DOMContentLoaded', function () {

      // Функция обновления счетчика ожидающих отзывов
      function updatePendingCount() {
        fetch('get_pending_count.php')
          .then(response => {
            // Сначала проверяем статус ответа
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            // Проверяем Content-Type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              throw new Error("Ожидался JSON, но получен " + contentType);
            }
            return response.json();
          })
          .then(data => {
            // Усиленная проверка структуры ответа
            if (!data || typeof data !== 'object') {
              throw new Error("Некорректный формат данных");
            }
            if (data.success && typeof data.count !== 'undefined') {
              const counterElement = document.querySelector('.review_time');
              if (counterElement) {
                counterElement.textContent = `Кол-во отзывов в ожидании: ${data.count}`;
              } else {
                console.error('Элемент .review_time не найден');
              }
            } else {
              throw new Error(data.message || "Неизвестная ошибка сервера");
            }
          })
          .catch(error => {
            console.error('Ошибка обновления счетчика:', error);
            // Можно добавить отображение ошибки пользователю
            const counterElement = document.querySelector('.review_time');
            if (counterElement) {
              counterElement.textContent = 'Ошибка загрузки данных';
              counterElement.style.color = 'red';
            }
          });
      }
      // Вызываем функцию при загрузке страницы
      document.addEventListener('DOMContentLoaded', updatePendingCount);
      // И обновляем каждые 30 секунд
      setInterval(updatePendingCount, 30000);




      // 4. Функция показа модального окна для удаления
      function showDeleteModal(reviewId, selectElement) {
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'flex';

        // Сбрасываем выбор причины
        document.querySelectorAll('input[name="reason"]').forEach(radio => {
          radio.checked = false;
        });
        document.querySelector('.answer').style.display = 'none';

        // Обработка отмены
        document.getElementById('cancelDelete').onclick = function () {
          modal.style.display = 'none';
          selectElement.value = selectElement.getAttribute('data-previous-value');
        };

        // Обработка подтверждения
        document.getElementById('confirmDelete').onclick = function () {
          const reason = document.querySelector('input[name="reason"]:checked');
          if (!reason) {
            document.querySelector('.answer').style.display = 'block';
            return;
          }

          updateReviewStatus(reviewId, 3, selectElement);
          modal.style.display = 'none';
        };
      }

      // 6. Функция показа уведомлений
      function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
          toast.style.opacity = '0';
          setTimeout(() => toast.remove(), 300);
        }, 3000);
      }
    });    
    
    
    
    


    
    document.querySelectorAll('.status-select').forEach(select => {
      select.addEventListener('change', async function () {
        const reviewId = this.dataset.reviewId;
        const newStatus = this.value; // 1, 2 или 3
        const originalValue = this.dataset.originalStatus || '1';

        // Показываем состояние загрузки
        this.classList.add('loading');

        const formData = new FormData();
        formData.append('review_id', reviewId);
        formData.append('status', newStatus);

        try {
          const response = await fetch('review_status.php', {
            method: 'POST',
            body: formData
          });

          const text = await response.text();
          console.log("Server response:", text);

          if (text.trim() === "success") {
            if (newStatus === '2') { // Если статус "approved"
              this.disabled = true;
              this.dataset.originalStatus = newStatus;
            }
            alert("Статус успешно обновлен!");
          } else {
            throw new Error(text.startsWith("error:") ?
              text.substring(6).trim() :
              "Сервер вернул неожиданный ответ");
          }
        } catch (error) {
          console.error("Update failed:", error);
          alert("Ошибка: " + error.message);
          this.value = originalValue;
        } finally {
          this.classList.remove('loading');
        }
      });
    });
  </script>
</body>

</html>