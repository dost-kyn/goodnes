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
</head>

<body>
    <div id="authModal" class="modal">
    <div class="modal-content">
        <form id="regForm" class="modal_form" action="../connect/registration.php" method="post" onsubmit="return validateForm()">
            <h1 class="title">Регистрация</h1>
            <input class="input" type="text" placeholder="Имя" name="name" id="name" required>
            <input class="input" type="email" placeholder="Почта" name="email" id="email" required>
            <input class="input" type="password" placeholder="Пароль" name="pass" id="pass" required>
            <input class="input" type="password" placeholder="Повторите пароль" name="repeat_pass" id="repeat_pass" required>
            <div class="approval">
                <label class="custom-checkbox">
                    <input type="checkbox" class="approval_inp" id="approval" required>
                    <span class="checkmark"></span>
                </label>
                <p class="approval_text">Нажимая на кнопку, вы даете согласие на обработку персональных данных и
                    соглашаетесь c политикой конфиденциальности.</p>
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
    
    // Проверка согласия с условиями
    if (!approval) {
        alert('Необходимо согласиться с условиями');
        return false;
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