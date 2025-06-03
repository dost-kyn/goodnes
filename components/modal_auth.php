
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="/css/modal.css">
</head>

<body>
    <div id="authModal" class="modal">
        <div class="modal-content">
            <form class="modal_form" action="../connect/auto.php" method="POST">
                <h1 class="title">Авторизация</h1>
                <input class="input" type="email" placeholder="Почта" name="email">
                <input class="input" type="password" placeholder="Пароль" name="password">
                <button class="btn" >Войти</button>

                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['message'] . '</div>';
                    unset($_SESSION['message']); // Удаляем сообщение после вывода, чтобы оно не появлялось снова
                }
                ?>

            </form>
            <p class="akk">Нет аккаунта?<a href="modal_reg.php">Зарегистрируйтесь</a></p>
        </div>
    </div>
</body>

</html>