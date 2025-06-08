<?php
session_start();
require_once '../connect/connect.php';

// Проверка авторизации
if (!isset($_SESSION['user']['id'])) {
    header("Location: /login.php");
    exit();
}

$user_id = (int) $_SESSION['user']['id'];

// Запрос для получения информации о пользователе
$user_sql = "SELECT id, name FROM users WHERE id = ?";
$user_stmt = mysqli_prepare($connect, $user_sql);
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

// Запрос для получения сохраненных рецептов пользователя
$recipes_sql = "SELECT 
                r.id AS recipe_id, 
                r.name AS recipe_name,
                r.cooking_time, 
                r.calorie, 
                r.caregories, 
                r.maun_image,
                sr.saved_at
            FROM 
                saved_recipes sr
            JOIN 
                recipes r ON sr.recipe_id = r.id
            WHERE 
                sr.user_id = ?
            ORDER BY
                sr.saved_at DESC";

$recipes_stmt = mysqli_prepare($connect, $recipes_sql);
mysqli_stmt_bind_param($recipes_stmt, "i", $user_id);
mysqli_stmt_execute($recipes_stmt);
$recipes_result = mysqli_stmt_get_result($recipes_stmt);



// Новый запрос только для отзывов пользователя
$reviews_sql = "SELECT 
            rev.id AS review_id,
            rev.text AS review_text,
            rev.created_at AS review_date,
            rev.status AS review_status,
            r.id AS recipe_id,
            r.name AS recipe_name,
            r.maun_image
        FROM 
            reviews rev
        JOIN 
            recipes r ON rev.recipe_id = r.id
        WHERE 
            rev.user_id = ?
        ORDER BY
            rev.created_at DESC";

$reviews_stmt = mysqli_prepare($connect, $reviews_sql);
mysqli_stmt_bind_param($reviews_stmt, "i", $user_id);
mysqli_stmt_execute($reviews_stmt);
$reviews_result = mysqli_stmt_get_result($reviews_stmt);

// echo "<pre>ID пользователя из сессии: " . htmlspecialchars($user_id) . "</pre>";


// Обработчик удаления рецепта
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recipe'])) {
    $recipe_id = (int) $_POST['recipe_id'];
    $user_id = (int) $_SESSION['user']['id'];

    $delete_sql = "DELETE FROM saved_recipes WHERE user_id = ? AND recipe_id = ?";
    $stmt = mysqli_prepare($connect, $delete_sql);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $recipe_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: " . $_SERVER['PHP_SELF']); // Перезагружаем страницу
        exit();
    } else {
        echo "<script>alert('Ошибка при удалении рецепта');</script>";
    }
}


//  Обработчик удаления отзыва -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
    $review_id = (int) $_POST['review_id'];

    $delete_sql = "DELETE FROM reviews WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($connect, $delete_sql);
    mysqli_stmt_bind_param($stmt, "ii", $review_id, $user_id);
    mysqli_stmt_execute($stmt);

    header("Location: " . $_SERVER['PHP_SELF']); // Перезагрузка страницы
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/profile.css">
    <title>Профиль</title>
    <style>
        .recipes_card form {
            margin: 5px 0 0 auto;
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

    <div class="name_user">
        <h1 class="name_user_h1"><?php echo htmlspecialchars($user['name'] ?? 'Пользователь') ?></h1>
    </div>

    <section class="search">
        <div class="find">
            <input class="search_inp" type="text" placeholder="Поиск..">
            <button class="search_btn">Найти</button>
        </div>

        <div class="blog">
            <button class="blog_btn" onclick="window.location.href='blog.php'">Блоги</button>
        </div>
    </section>

    <section class="catalog">
        <div class="recipes_cards">


            <?php if ($recipes_result && mysqli_num_rows($recipes_result) > 0): ?>
                <?php while ($recipe = mysqli_fetch_assoc($recipes_result)): ?>
                    <a href="recipe_page.php?id=<?= htmlspecialchars($recipe['recipe_id']) ?>" class="recipes_card">
                        <h1 class="recipes_title"><?= htmlspecialchars($recipe['recipe_name']) ?></h1>
                        <div class="recipes_image">
                            <img src="<?= htmlspecialchars($recipe['maun_image']) ?>" alt="" class="recipes_image_img">
                        </div>
                        <div class="recipes_info">
                            <p class="recipes_calory">Калорийность: <?= htmlspecialchars($recipe['calorie']) ?> ккал</p>
                            <p class="recipes_category">Категория: <?= htmlspecialchars($recipe['caregories']) ?></p>
                        </div>
                        <form method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот рецепт?')">
                            <input type="hidden" name="recipe_id" value="<?= $recipe['recipe_id'] ?>">
                            <button type="submit" name="delete_recipe" class="recipes_btn">Удалить</button>
                        </form>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Нет сохранённых рецептов.</p>
            <?php endif; ?>


            <!-- <div class="recipes_card">
                <h1 class="recipes_title">Печенье "Орео"</h1>
                <div class="recipes_image">
                    <img src="/image/home/Печенье орео.png" alt="" class="recipes_image_img">
                </div>
                <div class="recipes_info">
                    <p class="recipes_calory">Калорийность: 439 ккал</p>
                    <p class="recipes_category">Категория: печенье</p>
                </div>
                <button class="recipes_btn">Удалить</button>
            </div> -->

        </div>
        <div class="recipes_more">
            <button class="recipes_more_btn">Показать ещё+</button>
        </div>
    </section>

    <section class="review">
        <div class="review_info">
            <div class="review_title">
                <h1 class="catalog_title">Ваши отзывы</h1>
            </div>
            <div class="review_quantity">
                <span class="review_quan_num">2</span>
                <span class=""> Отзыва(ов)</span>
            </div>
        </div>
        <div class="review_content">

            <?php
            $has_approved_reviews = false;
            if ($reviews_result && mysqli_num_rows($reviews_result) > 0):
                while ($review = mysqli_fetch_assoc($reviews_result)):
                    // Проверяем, что статус отзыва "approved"
                    if ($review['review_status'] === 'approved'):
                        $has_approved_reviews = true;
                        ?>
                        <div class="review_box">
                            <div class="review_box_info">
                                <span class="review_name"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Вы') ?></span>
                                <a href="/recipe.php?id=<?= $review['recipe_id'] ?>" class="review_recipe">
                                    <?= htmlspecialchars($review['recipe_name']) ?>
                                </a>
                                <span class="review_date"><?= date('d.m.Y', strtotime($review['review_date'])) ?></span>
                            </div>
                            <p class="review_box_text"><?= htmlspecialchars($review['review_text']) ?></p>
                            <!-- <div class="review_status approved">Статус: Одобрено</div> -->
                            <form method="POST">
                                <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                                <button type="submit" name="delete_review" class="review_btn">Удалить</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>

                <?php if (!$has_approved_reviews): ?>
                    <p>У вас нет одобренных отзывов.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>У вас пока нет отзывов.</p>
            <?php endif; ?>









            <!-- <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php if ($row['review_id']): ?>  
                        <div class="review_box">
                            <div class="review_box_info">
                                <span class="review_name">Альбер Камю</span>
                                <a class="review_recipe">Овсяное печенье</a>
                                <span class="review_date"><?= date('d.m.Y', strtotime($row['review_date'])) ?></span>
                            </div>
                            <p class="review_box_text"><?= htmlspecialchars($row['review_text']) ?></p>
                            <button class="review_btn">Удалить</button>    
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Нет отзывов.</p>
            <?php endif; ?> -->

            <!-- <div class="review_box">
                <div class="review_box_info">
                    <span class="review_name">Альбер Камю</span>
                    <a class="review_recipe">Овсяное печенье</a>
                    <span class="review_date">09.03.2025</span>
                </div>
                <p class="review_box_text">Вымесить тесто силиконовой лопаткой очень тщательно. Все ингредиенты должны
                    хорошо соединиться друг с другом. Тесто становится очень послушным и не слишком липким.</p>
                <button class="review_btn">Удалить</button>    
            </div> -->
            <!-- <div class="review_box ban">
                <p class="review_box_text_ban">Ваш отзыв заблокирован. Удаление через: 2:59 ч</br>Причина: Спам</p>
                <div class="review_box_info">
                    <span class="review_name">Альбер Камю</span>
                    <a class="review_recipe">Ржаной хлеб</a>
                    <span class="review_date">28.03.2025</span>
                </div>
                <p class="review_box_text">Вымесить тесто силиконовой лопаткой очень тщательно. Все ингредиенты должны
                    хорошо соединиться друг с другом. Тесто становится очень послушным и не слишком липким.</p>
            </div> -->
        </div>

        <div class="review_more">
            <button class="review_more_btn">Показать ещё+</button>
        </div>
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
    <script src="/js/tema.js"></script>
    <script src="/js/profile.js"></script>
</body>

</html>