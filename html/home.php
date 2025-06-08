<?php
session_start();
require_once '../connect/connect.php';

// $sql_popular = "SELECT 
//            id,
//            name,
//            cooking_time,
//            calorie,
//            caregories,
//            maun_image,
//            created_at 
//            
//         FROM recipes 
//         ORDER BY created_at DESC 
//         LIMIT 8";

$sql_popular = "SELECT 
    r.id,
    r.name,
    r.cooking_time,
    r.calorie,
    r.caregories,
    r.maun_image,
    r.created_at,
    IFNULL((SELECT 1 FROM saved_recipes sr WHERE sr.user_id = ? AND sr.recipe_id = r.id LIMIT 1), 0) AS is_saved
FROM 
    recipes r
ORDER BY 
    r.created_at DESC
LIMIT 8";


// $result_popular = mysqli_query($connect, $sql_popular);


$stmt = mysqli_prepare($connect, $sql_popular);
mysqli_stmt_bind_param($stmt, "i", $user_id); // Привязываем параметр user_id
mysqli_stmt_execute($stmt);
$popular_result = mysqli_stmt_get_result($stmt);


if (!$popular_result) {
    die("Ошибка запроса: " . mysqli_error($connect));
}



$stmt = mysqli_prepare($connect, $sql_popular);
if (!$stmt) {
    die("Ошибка подготовки запроса: " . mysqli_error($connect));
}

// Убедитесь что $user_id определен
$user_id = $_SESSION['user']['id'] ?? 0; // Добавьте эту строку

mysqli_stmt_bind_param($stmt, "i", $user_id);

if (!mysqli_stmt_execute($stmt)) {
    die("Ошибка выполнения запроса: " . mysqli_stmt_error($stmt));
}

$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    die("Ошибка получения результата: " . mysqli_error($connect));
}




$test_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
error_log(print_r($test_data, true));
mysqli_data_seek($result, 0); // Возвращаем указатель на начало

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/home.css">
    <title>OVENLY GOODNESS</title>
    <style>
        .recipes_btn.save-recipe.saved {
            color: #555555;
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
                <a href="blog.php" class="header_nav_blog">Блог</a>

                <?php if (isset($_SESSION['user'])): ?>
                    <a href="profile.php" class="header_nav_profile">Профиль</a>
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

    <div class="mobile-menu-overlay"></div>
    <nav class="mobile-menu">
        <button class="mobile-menu-close">
            <span></span>
            <span></span>
        </button>

        <div class="mobile-menu-content">
            <button class="mobile-menu-theme">
                <img src="/image/tema.svg" data-theme-image data-light="/image/tema.svg"
                    data-dark="/image/tema-dark.svg" class="mobile-menu-theme-img">
                <span>Сменить тему</span>
            </button>

            <a href="catalog.php" class="mobile-menu-link">Каталог</a>
            <a href="blog.php" class="mobile-menu-link">Блог</a>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="profile.php" class="mobile-menu-link">Профиль</a>
                <a href="/connect/logout.php" class="mobile-menu-link">Выйти</a>
            <?php else: ?>
                <a href="/components/modal_auth.php" class="mobile-menu-link">Войти</a>
            <?php endif; ?>
        </div>
    </nav>


    <section class="banner">
        <div class="banner_image">
            <img src="/image/banner_image.jpg" alt="" class="banner_image_img">
        </div>
        <p class="banner_text">Добро пожаловать в <br>
            OVENLY GOODNESS — мир <br> ароматной выпечки</p>
    </section>
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

    <section class="popular_recipes">
        <h1 class="popular_recipes_title">Популярные рецепты</h1>


        <div class="container_card">
            <button class="pre">⭠</button>
            <div class="recipes_cards">

                <?php while ($card = mysqli_fetch_assoc($result)): ?>
                    <a href="recipe_page.php?id=<?= htmlspecialchars($card['id']) ?>" class="recipes_card"
                        data-id="<?= htmlspecialchars($card['id']) ?>"
                        data-cooking-time="<?= htmlspecialchars($card['cooking_time']) ?>">
                        <h1 class="recipes_title"><?= htmlspecialchars($card['name']) ?></h1>
                        <div class="recipes_image">
                            <?php if (!empty($card['maun_image'])): ?>
                                <img src="<?= htmlspecialchars($card['maun_image']) ?>"
                                    alt="<?= htmlspecialchars($card['name']) ?>" class="recipes_image_img">
                            <?php else: ?>
                                <img src="/path/to/default/image.jpg" alt="No image" class="recipes_image_img">
                            <?php endif; ?>
                        </div>
                        <div class="recipes_info">
                            <p class="recipes_calory">Калорийность: <?= htmlspecialchars($card['calorie']) ?> ккал</p>
                            <p class="recipes_category">Категория: <?= htmlspecialchars($card['caregories']) ?></p>

                        </div>
                        <?php if (isset($_SESSION['user']['id'])): ?>
                            <button class="recipes_btn save-recipe <?= ($card['is_saved'] == 1) ? 'saved' : '' ?>"
                                data-recipe-id="<?= $card['id'] ?>">
                                <?= ($card['is_saved'] == 1) ? 'Сохранено' : 'Сохранить' ?>
                            </button>
                        <?php endif; ?>
                    </a>
                <?php endwhile; ?>


                <!-- <div class="recipes_card">
                    <h1 class="recipes_title">Клубничый пирог</h1>
                    <div class="recipes_image">
                        <img src="/image/home/Клубничый пирог.png" alt="" class="recipes_image_img">
                    </div>
                    <div class="recipes_info">
                        <p class="recipes_calory">Калорийность: 189,6 ккал</p>
                        <p class="recipes_category">Категория: пироги</p>
                    </div>
                    <button class="recipes_btn">Cохранить</button>
                </div> -->

            </div>
            <button class="next">⭢</button>
        </div>
    </section>

    <section class="for_us">
        <h1 class="popular_recipes_title">О нас</h1>
        <div class="for_us_cards">

            <div class="for_us_card">
                <div class="for_us_image"><img src="/image/home/for_us_1.svg" data-theme-image
                        data-light="/image//home/for_us_1.svg" data-dark="/image/home/for_us_1-dark.svg"
                        class="for_us_img"></div>
                <p class="for_us_text">Для всех уровней</p>
            </div>
            <div class="for_us_card">
                <div class="for_us_image"><img src="/image/home/for_us_2.svg" data-theme-image
                        data-light="/image//home/for_us_2.svg" data-dark="/image/home/for_us_2-dark.svg"
                        class="for_us_img"></div>
                <p class="for_us_text">1200 <br> рецептов</p>
            </div>
            <div class="for_us_card">
                <div class="for_us_image"><img src="/image/home/for_us_3.svg" data-theme-image
                        data-light="/image//home/for_us_3.svg" data-dark="/image/home/for_us_3-dark.svg"
                        class="for_us_img"></div>
                <p class="for_us_text">Обширный выбор</p>
            </div>

        </div>
    </section>

    <section class="baking_for">
        <h1 class="popular_recipes_title">Выпечка подойдет для...</h1>
        <div class="baking_for_cards">

            <div class="baking_for_card">
                <div class="baking_for_image"><img src="/image/home/baking_for_img_1.jpg" alt="" class="baking_for_img">
                </div>
                <div class="baking_for_card_info">
                    <p class="baking_for_card_title">каждого дня</p>
                    <hr>
                    <p class="baking_for_text">Выпечка легко может стать прекрасной частью повседневного рациона и
                        подходить для любого дня</p>
                </div>
            </div>

            <div class="baking_for_card">
                <div class="baking_for_image"><img src="/image/home/baking_for_img_2.jpg" alt="" class="baking_for_img">
                </div>
                <div class="baking_for_card_info">
                    <p class="baking_for_card_title">праздников</p>
                    <hr>
                    <p class="baking_for_text">Зайдите на кухню и устройте вечеринку.
                        что вы заботитесь, нет лучшего способа сделать это, чем с помощью торта.</p>
                </div>
            </div>

            <div class="baking_for_card">
                <div class="baking_for_image"><img src="/image/home/baking_for_img_3.jpg" alt="" class="baking_for_img">
                </div>
                <div class="baking_for_card_info">
                    <p class="baking_for_card_title">диеты</p>
                    <hr>
                    <p class="baking_for_text">Выпечка может быть адаптирована для диетического питания. Вы можете
                        выбирать более полезные и натуральные продукты</p>
                </div>
            </div>


        </div>
    </section>

    <section class="subscription">
        <h1 class="popular_recipes_title">Подписка на наши новости</h1>
        <form id="subscriptionForm" class="subscription_conteniner">
            <div class="subscription_content">
                <input type="text" class="subscription_inp" placeholder="Ваш email">
                <button class="subscription_btn">Подписаться</button>
            </div>
            <div class="subscription_approval">
                <input type="checkbox" class="wr-checkbox21" id="wr21" name="wr">
                <label for="wr21"></label>
                <a href="/html/polite.html" class="subscription_approval_text">Я согласен(а) с политикой персональных
                    данных</a>
            </div>
        </form>
    </section>

    <footer class="footer">
        <div class="footer_content">
            <div class="footer_logo">
                <img src="/image/лого.svg" data-theme-image data-light="/image/лого.svg"
                    data-dark="/image/лого-dark.svg" class="footer_logo_img">
            </div>
            <div class="footer_cataloge">
                <a href="catalog.php" class="footer_cataloge_link">Кексы</a>
                <a href="catalog.php" class="footer_cataloge_link">Пироги</a>
                <a href="catalog.php" class="footer_cataloge_link">Хлеб</a>
                <a href="catalog.php" class="footer_cataloge_link">Торты</a>
                <a href="catalog.php" class="footer_cataloge_link">Конфеты</a>
                <a href="catalog.php" class="footer_cataloge_link">Печенье</a>
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
            <a href="policy.php">Политика конфиденциальности</a>
        </div>
    </footer>
</body>
<script>
    // кнопка сохранить
    document.querySelectorAll('.save-recipe').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            const recipeId = this.dataset.recipeId;
            const isSaved = this.classList.contains('saved');
            // const action = isSaved ? 'remove' : 'add';

            // window.location.href = `save_recipe.php?recipe_id=${recipeId}&action=${action}`;

            if (!recipeId || isNaN(recipeId)) {
                console.error('Invalid recipeId:', recipeId);
                alert('Ошибка: некорректный ID рецепта');
                return;
            }

            try {
                const response = await fetch('save_recipe.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        recipe_id: recipeId,
                        action: isSaved ? 'remove' : 'add'
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Ошибка сервера');
                }

                if (data.success) {
                    this.classList.toggle('saved');
                    this.textContent = isSaved ? 'Сохранить' : 'Сохранено';
                    this.style.backgroundColor = isSaved ? '' : '#4CAF50';
                } else {
                    alert(data.message || 'Ошибка операции');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ошибка: ' + error.message);
            }
        });
    });

</script>
<script src="/js/tema.js"></script>
<script src="/js/home.js"></script>

</html>