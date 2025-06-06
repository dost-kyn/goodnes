<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/reviews.css" />
  <title>Пользователи</title>
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
            <p class="all_review">Общее кол-во: 2 058</p>
            <p class="for_month_review">Кол-во за последний месяц: 251</p>
            <p class="review_time">Кол-во отзывов в ожидании: 16</p>
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

          <tr class="table_row">
            <td>1</td>
            <td>Федор Достоевский</td>
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
          </tr>
          <tr class="table_row">
            <td>2</td>
            <td>Федор Достоевский</td>
            <td>Печеньки</td>
            <td class="review-text">
              Мне не понравилось, все слишком сложно
            </td>
            <td>12.04.2025</td>
            <td class="select_status">
              <select name="status">
                <option value="1">В ожидании</option>
                <option value="2">Одобрено</option>
                <option value="3">Удалить</option>
              </select>
            </td>
          </tr>
          <tr class="table_row">
            <td>3</td>
            <td>Омар Рудберг</td>
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
          </tr>
          <tr class="table_row">
            <td>4</td>
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
          </tr>
          <tr class="table_row">
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
          </tr>
          <!-- Другие строки таблицы -->
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
      const rows = document.querySelectorAll('.table_row');

      rows.forEach(row => {
        row.addEventListener('click', function (e) {
          // Игнорируем клики по select
          if (e.target.tagName === 'SELECT') return;

          this.classList.toggle('expanded');
        });
      });
    });



    let deleteModal = document.querySelector("#deleteModal");

    let radio = document.querySelectorAll(
      '#deleteModal input[name="reason"]'
    );
    let button_yes = document.querySelector(".modal-btn-yes");
    let button_no = document.querySelector(".modal-btn-no");
    let answer = document.querySelector(".answer");

    let select = document.querySelector('select[name="status"]');

    select.addEventListener("click", function () {
      if (select.value === "3") {
        deleteModal.style.display = "flex";

        button_yes.addEventListener("click", function () {
          let check = document.querySelector(
            '#deleteModal input[name="reason"]:checked'
          );

          if (!check) {
            answer.style.display = "block";
            return;
          }
          if (check) {
            answer.style.display = "none";
            // select.value = "3";
            deleteModal.style.display = "none";
          }
        });
        button_no.addEventListener("click", function () {
          deleteModal.style.display = "none";
          select.value = "1";
        });
      }
    });
  </script>
</body>

</html>