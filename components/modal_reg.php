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
            <form id="regBtn" class="modal_form" action="../connect/registration.php" method="post">
                <h1 class="title">Регистрация</h1>
                <input class="input" type="text" placeholder="Имя" name="name">
                <input class="input" type="email" placeholder="Почта" name="email">
                <input class="input" type="password" placeholder="Пароль" name="pass">
                <input class="input" type="password" placeholder="Повторите пароль" name="repeat_pass">
                <div class="approval">
                    <label class="custom-checkbox">
                        <input type="checkbox" class="approval_inp">
                    </label>
                    <p class="approval_text">Нажимая на кнопку, вы даете согласие на обработку персональных данных и
                        соглашаетесь c политикой конфиденциальности.</p>
                </div>
                <button class="btn" id="regBtn">Зарегистрироваться</button>


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

</script>
</body>

</html>