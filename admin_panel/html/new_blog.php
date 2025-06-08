
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
                <a href="users.html" class="sidebar_nav_link users">Пользователи</a>
                <a href="reviews.php" class="sidebar_nav_link">Отзывы</a>
                <a href="recipes.php" class="sidebar_nav_link">Рецепты</a>
                <a href="blog.php" class="sidebar_nav_link" style="text-decoration-line: underline;">Блоги</a>
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

                <table class="table_users">
                    <tr class="table_row">
                        <th class="table_column_1">Название</th>
                        <th><input type="text" class="table_inp"></th>
                    </tr>
                    


                    <tr class="table_row">
                        <th class="table_column_1" style="vertical-align: top;">Основа</th>
                        <th class="table_column_2">
                            <p>Заголовок 1: <input type="text" class="table_inp"></p>
                            <p>Фото 1: <input type="file"></p>
                            <p>Описание 1: <input type="text" class="table_inp"></p>
                        </th>
                    </tr>
                    <tr class="table_row">
                        <th>

                        </th>
                        <th>
                            <button class="btn" id="moreButton">Добавить ещё</button>
                        </th>
                    </tr>
                </table>

                <p class="error"></p>
                <div class="container_review_buttons">
                    <button class="btn"><a href="blog.html">Вернуться</a></button>
                    <button class="btn" id="saveButton">Сохранить</button>
                    <button class="btn" id="editButton" style="display: none;">Изменить</button>
                </div>
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
            <p>Заголовок ${counter} : <input type="text" class="table_inp"></p>
            <p>Фото ${counter} : <input type="file"></p>
            <p>Описание ${counter} : <input type="text" class="table_inp"></p>`;

            newRow.appendChild(document.createElement('th')); // Пустая ячейка слева
            newRow.appendChild(newTh);
            moreButton.closest('tr').before(newRow);

            counter++;
        });


    saveButton.addEventListener('click', () => {
    // Проверяем, все ли обязательные поля заполнены
    let allFilled = true;
    const textInputs = document.querySelectorAll('.table_inp');
    
    textInputs.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });

    if (allFilled) {
        const tableRows = document.querySelectorAll('.table_row:not(:last-child)');

        tableRows.forEach(row => {
            const inputs = row.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                const p = document.createElement('p');
                p.className = 'saved-value';
                p.textContent = input.value;
                input.replaceWith(p);
            });

            const fileInputs = row.querySelectorAll('input[type="file"]');
            fileInputs.forEach(fileInput => {
                const fileName = document.createElement('p');
                fileName.className = 'saved-file';
                fileName.textContent = fileInput.files[0] ? fileInput.files[0].name : 'Файл не выбран';
                fileInput.replaceWith(fileName);
            });
        });

        moreButton.style.display = 'none';
        saveButton.style.display = 'none';
        editButton.style.display = "block";
        alert('Данные успешно сохранены!');
    } else {
        // Создаем или находим элемент для ошибки
        let error = document.querySelector('.error');
        if (!error) {
            error = document.createElement('div');
            error.className = 'error';
            document.querySelector('.container_review').prepend(error);
        }
        error.textContent = "Не все поля заполнены!";
        error.style.color = 'red';
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

            moreButton.style.display = 'block';
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

    </script>
</body>

</html>