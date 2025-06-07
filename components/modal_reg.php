<?php

session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/css/modal.css">
    <style>
        .approval input[type="checkbox"] {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        /* вертикальное центрирование флажка и надписи к нему */
        .approval input[type="checkbox"]+label {
            display: inline-flex;
            align-items: center;
            user-select: none;
        }

        /* создаем поддельный чекбокс */
        .approval input[type="checkbox"]+label::before {
            content: '';
            width: 27px;
            height: 27px;
            background-color: var(--chekbox);
            border: 0px;
            border-radius: 10px;
            display: inline-block;
            position: relative;
            margin: auto 0;
            cursor: pointer;
        }

        .approval input[type="checkbox"]:checked+label::before {
            background-image: url(../image/галочка.svg);
            background-size: 80% 80%;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
</head>

<body>
    <div id="authModal" class="modal">
        <div class="modal-content">
            <form id="regForm" class="modal_form" action="../connect/registration.php" method="post"
                onsubmit="return validateForm()">
                <h1 class="title">Регистрация</h1>
                <input class="input" type="text" placeholder="Имя" name="name" id="name" required>
                <input class="input" type="email" placeholder="Почта" name="email" id="email" required>
                <input class="input" type="password" placeholder="Пароль" name="pass" id="pass" required>
                <input class="input" type="password" placeholder="Повторите пароль" name="repeat_pass" id="repeat_pass"
                    required>
                <div class="approval">
                    <input type="checkbox" class="wr-checkbox20" id="wr20" name="wr" required>
                    <label for="wr20"></label>
                    <p class="approval_text">Нажимая на кнопку, вы даете согласие на обработку
                        персональных данных и
                        соглашаетесь c политикой конфиденциальности.</p>
                </div>
                <button class="btn" type="submit">Зарегистрироваться</button>

                <?php
                if (isset($_SESSION['message'])) {
                    echo ' <p class="msg"> ' . $_SESSION['message'] . '</p>';
                }
                unset($_SESSION['message']);
                ?>
            </form>
            <p class="akk">Есть аккаунт?<a href="modal_auth.php">Авторизируйтесь</a></p>
        </div>
    </div>
    <script>
        function validateForm() {
            // Проверка заполнения полей
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const pass = document.getElementById('pass').value;
            const repeatPass = document.getElementById('repeat_pass').value;
            const approval = document.getElementById('approval').checked;

            // Проверка на пустые поля
            if (!name || !email || !pass || !repeatPass) {
                alert('Пожалуйста, заполните все поля');
                return false;
            }

            // Проверка совпадения паролей
            if (pass !== repeatPass) {
                alert('Пароли не совпадают');
                return false;
            }

            // Проверяем чекбокс согласия
            const checkbox = document.getElementById('wr20');
            if (!checkbox.checked) {
                alert('Пожалуйста, подтвердите согласие на обработку данных');
                e.preventDefault();
                return;
            }

            // Проверка email (базовая валидация)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Пожалуйста, введите корректный email');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>