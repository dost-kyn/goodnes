<?php
session_start();
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
                <a href="" class="sidebar_nav_link recipes" style="text-decoration-line: underline;">Рецепты</a>
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

                <form class="modal_form" action="more_create.php" method="POST" enctype="multipart/form-data">
                    <table class="table_users">
                        <tr class="table_row">
                            <th class="table_column_1">Название</th>
                            <th><input type="text" class="table_inp" name="name"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Время при-ия</th>
                            <th><input type="text" class="table_inp" name="cooking_time"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Калор-ость</th>
                            <th><input type="text" class="table_inp" name="calorie"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Кол-во порций</th>
                            <th><input type="text" class="table_inp" name="portions"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Категория</th>
                            <th class="table_column_2">
                                <select name="caregories" id="">
                                    <option value="Торты">Торты</option>
                                    <option value="Печенья">Печенья</option>
                                    <option value="Пироги">Пироги</option>
                                    <option value="Кексы">Кексы</option>
                                    <option value="Конфеты">Конфеты</option>
                                    <option value="Хлеб">Хлеб</option>
                                </select>
                            </th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Главная фотография</th>
                            <th class="table_column_2"><input type="file" name="maun_image"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Описание</th>
                            <th><input type="text" class="table_inp" name="description"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Ингридиенты</th>
                            <th><input type="text" class="table_inp" name="ingredients"></th>
                        <!-- <tr class="table_row">
                            <th class="table_column_1">Инструкция</th>
                            <th class="table_column_2">
                                <p>Фото 1: <input type="file"></p>
                                <p>Описание 1: <input type="text" class="table_inp"></p>
                            </th>
                        </tr> -->
                        <tr class="table_row">
                            <th>

                            </th>
                            <th>
                                <button class="btn" id="moreButton">Добавить ещё</button>
                            </th>
                        </tr>
                        <!-- <tr class="table_row">
                        <th class="table_column_1">Кол-во отзывов</th>
                        <th><input type="text" class="table_inp"></th>
                    </tr> -->
                    </table>

                    <p class="error"></p>
                    <div class="container_review_buttons">
                        <button class="btn"><a href="recipes.html">Вернуться</a></button>
                        <button class="btn" id="saveButton">Сохранить</button>
                        <button class="btn" id="editButton" style="display: none;">Изменить</button>
                    </div>
                </form>
            </section>
        </section>
    </div>


    <script>
        const moreButton = document.querySelector('#moreButton');
        const saveButton = document.querySelector('#saveButton');
        const editButton = document.querySelector('#editButton');
        const inputs = document.querySelectorAll('.table_inp');
        let counter = 2;

        moreButton.addEventListener('click', () => {
            const newRow = document.createElement('tr');
            newRow.className = 'table_row';

            const newTh = document.createElement('th');
            newTh.className = 'table_column_2';

            newTh.innerHTML = `
            <p>Фото ${counter} : <input type="file"></p>
            <p>Описание ${counter} : <input type="text" class="table_inp"></p>`;

            newRow.appendChild(document.createElement('th')); // Пустая ячейка слева
            newRow.appendChild(newTh);
            moreButton.closest('tr').before(newRow);

            counter++;
        });

        saveButton.addEventListener('click', (e) => {
            e.preventDefault(); // Предотвращаем стандартное поведение формы

            // Проверяем, все ли обязательные поля заполнены
            let allFilled = true;
            const requiredInputs = document.querySelectorAll('.table_inp, input[type="file"]');

            requiredInputs.forEach(input => {
                if (input.type === 'file') {
                    if (!input.files || input.files.length === 0) {
                        allFilled = false;
                        input.style.border = '1px solid red'; // Подсветка ошибки
                    }
                } else if (!input.value.trim()) {
                    allFilled = false;
                    input.style.border = '1px solid red'; // Подсветка ошибки
                } else {
                    input.style.border = ''; // Убираем подсветку, если поле заполнено
                }
            });

            if (allFilled) {
                // Если все поля заполнены, отправляем форму
                document.querySelector('.modal_form').submit();
            } else {
                // Показываем сообщение об ошибке
                let error = document.querySelector('.error');
                if (!error) {
                    error = document.createElement('div');
                    error.className = 'error';
                    document.querySelector('.container_review').prepend(error);
                }
                error.textContent = "Не все обязательные поля заполнены!";
                error.style.color = 'red';
                error.style.marginBottom = '20px';
            }
        });

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


        editButton.addEventListener('click', function () {
            // Находим все сохраненные значения
            const savedValues = document.querySelectorAll('.saved-value, .saved-file');

            savedValues.forEach(savedElement => {
                // Определяем тип поля (текст или файл)
                const isFile = savedElement.classList.contains('saved-file');

                // Создаем соответствующий input
                let input;
                if (isFile) {
                    input = document.createElement('input');
                    input.type = 'file';
                    // Для файловых полей нельзя установить предыдущее значение
                } else {
                    input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'table_inp';
                    input.value = savedElement.textContent;
                }

                // Заменяем <p> на input
                savedElement.replaceWith(input);
            });

            // Переключаем кнопки
            saveButton.style.display = 'block';
            editButton.style.display = 'none';
        });



        // это доп обработчик на форму для двойной проверки на ошибки
        document.querySelector('.modal_form').addEventListener('submit', function (e) {
            let allFilled = true;
            const requiredInputs = document.querySelectorAll('.table_inp, input[type="file"]');

            requiredInputs.forEach(input => {
                if (input.type === 'file' && (!input.files || input.files.length === 0)) {
                    allFilled = false;
                } else if (!input.value.trim()) {
                    allFilled = false;
                }
            });

            if (!allFilled) {
                e.preventDefault();

            }
        });

    </script>
</body>

</html>