<?php

session_start();

require_once '../connect/connect.php';
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/recipe_page.css">
    <title>*название рецепта*</title>
    <style>

.main_info_image {
    max-width: 60vw;
    background-color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 150px;
    box-sizing: border-box;
}
.main_info_img {
    min-width: 145%;
    object-fit: contain; /* Сохраняет пропорции */
    display: block;
}
@media (max-width: 1000px){
    .main_info_image {
    padding: 20px 130px;
}
.main_info_img {
    min-width: 125%;
}
}

@media (max-width: 900px){
    .main_info_image {
    max-width: 80vw;
}
.main_info_img {
    min-width: 115%;
}
}

@media (max-width: 800px){
    .main_info_image {
    padding: 30px 160px;
    width: 65vw;
}
.main_info_img {
    min-width: 130%;
}
}

@media (max-width: 700px){
    .main_info_image {
    width: 70vw;
}
.main_info_img {
    min-width: 130%;
}
}

@media (max-width: 600px){
.main_info_img {
    min-width: 170%;
}
}

@media (max-width: 550px){
    .main_info_image {
    padding: 25px 140px;
}
.main_info_img {
    min-width: 180%;
}
}

@media (max-width: 500px){
.main_info_img {
    min-width: 210%;
}
}

@media (max-width: 460px){
    .main_info_image {
    padding: 15px 110px;
}
.main_info_img {
    min-width: 130%;
}
}

@media (max-width: 410px){
.main_info_img {
    min-width: 180%;
}
}

@media (max-width: 370px){
.main_info_img {
    min-width: 260%;
}
}
    </style>
</head>

<body>
    <header class="header">
        <div class="header_content">
            <a class="header_logo" href="home.php">
                <img src="/image/лого.svg" data-theme-image data-light="/image/лого.svg"
                    data-dark="/image/лого-dark.svg" class="header_logo_img">
            </a>

            <div class="header_nav">
                <button class="header_nav_tema">
                    <img src="/image/tema.svg" data-theme-image data-light="/image/tema.svg"
                        data-dark="/image/tema-dark.svg" class="header_nav_tema_img">
                </button>
                <a href="catalog.php" class="header_nav_catalog">Каталог</a>
                <a href="home.php" class="header_nav_blog">Блог</a>

                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/connect/logout.php" class="header_nav_exit">Выйти</a>
                <?php else: ?>
                    <a href="/components/modal_auth.php" class="header_nav_exit">Войти</a>
                <?php endif; ?>

                <button class="header_nav_btn">
                    <div class="header_nav_span">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>

            </div>
        </div>
    </header>

    <section class="menu">
        <div class="menu_buttons">


            <button class="menu_btn">
                <div class="menu_btn_icon">
                    <img src="/image/cake_icon.svg" alt="Торты" class="menu_btn_icon_img" data-theme-image
                        data-light="/image/cake_icon.svg" data-dark="/image/cake_icon-dark.svg">
                </div>
                <p class="menu_btn_name one_or_hour">торты</p>
            </button>

            <button class="menu_btn">
                <div class="menu_btn_icon">
                    <img src="/image/cookie_icon.svg" alt="Торты" class="menu_btn_icon_img" data-theme-image
                        data-light="/image/cookie_icon.svg" data-dark="/image/cookie_icon-dark.svg">
                </div>
                <p class="menu_btn_name two_or_five">печенье</p>
            </button>

            <button class="menu_btn">
                <div class="menu_btn_icon">
                    <img src="/image/pie_icon.svg" alt="Торты" class="menu_btn_icon_img" data-theme-image
                        data-light="/image/pie_icon.svg" data-dark="/image/pie_icon-dark.svg">
                </div>
                <p class="menu_btn_name three_or_six">пироги</p>
            </button>

            <button class="menu_btn">
                <div class="menu_btn_icon">
                    <img src="/image/cupcakes_icon.svg" alt="Торты" class="menu_btn_icon_img" data-theme-image
                        data-light="/image/cupcakes_icon.svg" data-dark="/image/cupcakes_icon-dark.svg">
                </div>
                <p class="menu_btn_name one_or_hour">кексы</p>
            </button>

            <button class="menu_btn">
                <div class="menu_btn_icon">
                    <img src="/image/candy_icon.svg" alt="Торты" class="menu_btn_icon_img" data-theme-image
                        data-light="/image/candy_icon.svg" data-dark="/image/candy_icon-dark.svg">
                </div>
                <p class="menu_btn_name two_or_five">конфеты</p>
            </button>

            <button class="menu_btn">
                <div class="menu_btn_icon">
                    <img src="/image/bread_icon.svg" alt="Торты" class="menu_btn_icon_img" data-theme-image
                        data-light="/image/bread_icon.svg" data-dark="/image/bread_icon-dark.svg">
                </div>
                <p class="menu_btn_name three_or_six">хлеб</p>
            </button>
        </div>
    </section>

    <section class="crumds">
        <div class="crumbs_content">
            <a href="home.php">Главная страница -> </a><a href="catalog.php">Каталог -> </a>
            <a href="">Печенье -> </a>
            <a href="#"><?= htmlspecialchars($recipe['name']) ?></a>
        </div>
    </section>

    <section class="main_info">
        <div class="main_info_content" data-id="<?= htmlspecialchars($recipe['id']) ?>">
                <div class="main_info_image">
                    <img src="<?= htmlspecialchars($recipe['maun_image']) ?>" alt="" class="main_info_img">
                </div>
       
            <div class="main_info_conteiner">
                <h1 class="main_info_name"><?= htmlspecialchars($recipe['name']) ?></h1>
                <div class="main_info_mini">
                    <div class="main_info_m">
                        <span>Время приготовления: </span>
                        <span class="time"><?= htmlspecialchars($recipe['cooking_time']) ?> мин</span>
                    </div>
                    <div class="main_info_m">
                        <span>Всего порций: </span>
                        <span class="portion"><?= htmlspecialchars($recipe['portions']) ?></span>
                    </div>
                    <div class="main_info_m">
                        <span>Калорийность: </span>
                        <span class="calories"><?= htmlspecialchars($recipe['calorie']) ?> Ккал</span>
                    </div>
                    <div class="main_info_m">
                        <span>Категория: </span>
                        <span class="category"><?= htmlspecialchars($recipe['caregories']) ?></span>
                    </div>
                </div>

                <div class="main_info_save">
                    <button class="main_info_btn">Сохранить</button>
                </div>
            </div>
        </div>
    </section>

    <section class="description">
        <h1 class="description_title">Описание</h1>
        <p class="desc_text"><?= htmlspecialchars($recipe['description']) ?></p>
    </section>

    <section class="ingredients">
    <h1 class="ingre_title">Ингредиенты</h1>
    <div class="ingre_content">
        <?php 
        // Разбиваем строку ингредиентов по точке
        $ingredients = explode('.', $recipe['ingredients']);
        
        // Удаляем пустые элементы (если есть)
        $ingredients = array_filter(array_map('trim', $ingredients));
        
        // Для каждого ингредиента создаем отдельный блок
        foreach ($ingredients as $index => $ingredient): 
            if (!empty($ingredient)): ?>
                <div class="ingre_box">
                    <input type="checkbox" class="wr-checkbox" id="ingredient_<?= $index ?>" name="ingredient_<?= $index ?>">
                    <label for="ingredient_<?= $index ?>"></label>
                    <p class="ingre_text"><?= htmlspecialchars($ingredient) ?></p>
                </div>
            <?php endif;
        endforeach; ?>

            <!-- <div class="ingre_box">
                <input type="checkbox" class="wr-checkbox1" id="wr1" name="wr">
                <label for="wr1"></label>
                <p class="ingre_text">Масло сливочное – 110 г</p>
            </div> -->
        </div>
    </section>

    <section class="instruction">
        <h1 class="instru_title">Инструкция</h1>
        <div class="instru_content">
            <div class="instru_item">
                <div class="instru_item_image">
                    <img src="/image/recipe/item1.jpg" class="instru_item_img" alt="">
                </div>
                <div class="instru_item_text">
                    <p>Подготовить все необходимые ингредиенты для приготовления овсяного печенья с шоколадом. Удобно
                        заранее отвесить все необходимое. Все ингредиенты должны быть комнатной температуры, поэтому
                        сливочное масло и яйцо необходимо вынуть из холодильника заранее, примерно за час до
                        приготовления.</p>
                    <p>Сливочное масло выбирайте качественное с жирностью 72.5% или 82.5%. Идеальные овсяные хлопья –
                        это долгой варки, 15- или 20-минутные.</p>
                    <p>Шоколадные капли можно купить в любом магазине для кондитеров или на маркетплейсах. Если вдруг вы
                        все же не нашли такие капли, то купите плитку темного шоколада и просто порубите ее ножом на
                        кусочки.</p>
                </div>
            </div>
            <div class="instru_item">
                <div class="instru_item_image">
                    <img src="/image/recipe/item2.jpg" class="instru_item_img" alt="">
                </div>
                <div class="instru_item_text">
                    <p>Соединить тщательно сливочное масло комнатной температуры, сахар и соль силиконовой лопаткой.</p>
                    <p>Добавить яйцо комнатной температуры в масляно-сахарную смесь и очень тщательно его размешать.</p>
                </div>
            </div>
            <div class="instru_item">
                <div class="instru_item_image">
                    <img src="/image/recipe/item3.jpg" class="instru_item_img" alt="">
                </div>
                <div class="instru_item_text">
                    <p>Всыпать в миску к яично-масляной смеси просеянную муку с разрыхлителем, добавить овсяные хлопья и
                        шоколадные капли.</p>
                    <p>Вымесить тесто силиконовой лопаткой очень тщательно. Все ингредиенты должны хорошо соединиться
                        друг с другом. Тесто становится очень послушным и не слишком липким. Если вдруг на вашей кухне
                        очень жарко, то уберите тесто в холодильник минут на 20. Я не убирала в холод и сразу приступила
                        к формовке печенья.</p>
                    <p>Включить духовой шкаф и установить температуру нагрева 170°С.</p>
                </div>
            </div>
            <div class="instru_item">
                <div class="instru_item_image">
                    <img src="/image/recipe/item4.jpg" class="instru_item_img" alt="">
                </div>
                <div class="instru_item_text">
                    <p>Отщипните небольшое количество теста и сформируйте шарик. Тесто практически не липнет к рукам, и
                        шарики формируются легко. Разложить их нужно на хорошем расстоянии друг от друга, так как при
                        выпечке они немного расползутся. Шарики из теста лишь слегка приплюснуть рукой. Если хотите
                        печенье потоньше, то шарики теста делайте поменьше и расплющите посильнее.</p>
                    <p>Поставить противень с заготовками овсяного печенья в хорошо разогретую духовку и выпекать в
                        течение 20-25 минут. Если ваши печеньки будут тоньше, то сократите время их приготовления. Сразу
                        после выпечки горячее овсяное печенье с шоколадом переложить на решетку или деревянную доску для
                        остывания.</p>
                </div>
            </div>
            <div class="instru_item">
                <div class="instru_item_image">
                    <img src="/image/recipe/item5.jpg" class="instru_item_img" alt="">
                </div>
                <div class="instru_item_text">
                    <p>Хранить остывшее овсяное печенье с шоколадом, приготовленное в домашних условиях, нужно в
                        герметичном контейнере или плотном пакете. Приятного аппетита!</p>
                </div>
            </div>
        </div>
    </section>

    <section class="save_auto">
        <h1 class="save_auto_title">Хотите сохранить этот рецепт?</h1>
        <p class="save_auto_undertitle">Зайдите в свой аккаунт и каждую неделю получайте проверенные рецепты выпечки!
        </p>
        <p class="save_auto_akk">Нет аккаунта? <a href="">Зарегистрируйтесь</a></p>
        <div class="save_auto_content">
            <input class="save_auto_inp" type="text" placeholder="Email"><!--  Email -->
            <input class="save_auto_inp" type="text" placeholder="Имя">
        </div>
        <div class="save_auto_auto">
            <button class="save_auto_btn">Войти</button>
        </div>
    </section>
    <div class="notification-container">
        <div class="notification notification-success" id="login-success">
            Вы успешно вошли в систему!
        </div>
        <div class="notification notification-info" id="already-logged-in">
            Вы уже вошли в систему.
        </div>
        <div class="notification notification-error" id="login-error">
            Неверные данные для входа. Пожалуйста, попробуйте снова.
        </div>
    </div>

    <section class="review">
        <div class="review_info">
            <div class="review_quantity">
                <span class="review_quan_num">2</span>
                <span class=""> Отзыва(ов)</span>
            </div>
            <div class="review_leave">
                <button class="review_leave_btn">Оставить отзыв</button>
            </div>
        </div>
        <div class="review_content">
            <div class="review_box">
                <div class="review_box_info">
                    <span class="review_name">Федор Достоевский</span>
                    <span class="review_date">09.03.2025</span>
                </div>
                <p class="review_box_text">Вымесить тесто силиконовой лопаткой очень тщательно. Все ингредиенты должны
                    хорошо соединиться друг с другом. Тесто становится очень послушным и не слишком липким.</p>
            </div>
            <div class="review_box">
                <div class="review_box_info">
                    <span class="review_name">Иосиф Сталин</span>
                    <span class="review_date">09.03.2025</span>
                </div>
                <p class="review_box_text">Вымесить тесто силиконовой лопаткой очень тщательно. Все ингредиенты должны
                    хорошо соединиться друг с другом. Тесто становится очень послушным и не слишком липким.</p>
            </div>
            <div class="review_box">
                <div class="review_box_info">
                    <span class="review_name">Федор Достоевский</span>
                    <span class="review_date">09.03.2025</span>
                </div>
                <p class="review_box_text">Вымесить тесто силиконовой лопаткой очень тщательно. Все ингредиенты должны
                    хорошо соединиться друг с другом. Тесто становится очень послушным и не слишком липким.</p>
            </div>
            <div class="review_box">
                <div class="review_box_info">
                    <span class="review_name">Иосиф Сталин</span>
                    <span class="review_date">09.03.2025</span>
                </div>
                <p class="review_box_text">Вымесить тесто силиконовой лопаткой очень тщательно. Все ингредиенты должны
                    хорошо соединиться друг с другом. Тесто становится очень послушным и не слишком липким.</p>
            </div>
        </div>

        <div class="review_more">
            <button class="review_more_btn">Показать ещё+</button>
        </div>
    </section>

    <div class="modal-new_review">
        <div class="new_review_box">
            <h1 class="new_review_title">Создать отзыв</h1>
            <textarea class="new_review_inp" type="text" placeholder="Начните отзыв..."></textarea>
            <div class="new_review_button">
                <button class="new_review_btn back">Отмена</button>
                <button class="new_review_btn start">Опубликовать</button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer_content">
            <div class="footer_logo">
                <img src="/image/лого.svg" data-theme-image data-light="/image/лого.svg"
                    data-dark="/image/лого-dark.svg" class="footer_logo_img">
            </div>
            <div class="footer_cataloge">
                <a href="" class="footer_cataloge_link">Кексы</a>
                <a href="" class="footer_cataloge_link">Пироги</a>
                <a href="" class="footer_cataloge_link">Хлеб</a>
                <a href="" class="footer_cataloge_link">Торты</a>
                <a href="" class="footer_cataloge_link">Конфеты</a>
                <a href="" class="footer_cataloge_link">Печенье</a>
            </div>
            <div class="footer_info">
                <p class="footer_phone">+8 999 035 6471</p>
                <p class="footer_email">Ovenly_Goodness@gmail</p>
                <div class="footer_social">
                    <a href=""><img src="/image/footer_social_tg.svg" data-theme-image
                            data-light="/image/footer_social_tg.svg" data-dark="/image/footer_social_tg-dark.svg"
                            class="footer_social_img"></a>
                    <a href=""><img src="/image/footer_social_ok.svg" data-theme-image
                            data-light="/image/footer_social_ok.svg" data-dark="/image/footer_social_ok-dark.svg"
                            class="footer_social_img"></a>
                    <a href=""><img src="/image/footer_social_vk.svg" data-theme-image
                            data-light="/image/footer_social_vk.svg" data-dark="/image/footer_social_vk-dark.svg"
                            class="footer_social_img"></a>
                </div>
            </div>
        </div>
        <div class="footer_confi">
            <a href="">Политика конфиденциальности</a>
        </div>
    </footer>

    <script src="/js/tema.js"></script>
    <script src="/js/recipe_page.js"></script>
</body>

</html>