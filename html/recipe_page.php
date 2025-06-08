<?php
session_start();
require_once '../connect/connect.php';

// Получаем ID рецепта из URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Защита от SQL-инъекций
if ($recipe_id <= 0) {
    die("Неверный ID рецепта");
}

// Запрос для получения основной информации о рецепте
$sql_recipe = "SELECT * FROM recipes WHERE id = ?";
$stmt_recipe = mysqli_prepare($connect, $sql_recipe);
mysqli_stmt_bind_param($stmt_recipe, "i", $recipe_id);
mysqli_stmt_execute($stmt_recipe);
$result_recipe = mysqli_stmt_get_result($stmt_recipe);
$recipe = mysqli_fetch_assoc($result_recipe);

if (!$recipe) {
    die("Рецепт не найден");
}

// Запрос для получения шагов рецепта
$sql_steps = "SELECT * FROM recipe_steps WHERE recipe_id = ? ORDER BY step_number";
$stmt_steps = mysqli_prepare($connect, $sql_steps);
mysqli_stmt_bind_param($stmt_steps, "i", $recipe_id);
mysqli_stmt_execute($stmt_steps);
$result_steps = mysqli_stmt_get_result($stmt_steps);
$steps = mysqli_fetch_all($result_steps, MYSQLI_ASSOC);

// Запрос для получения отзывов к рецепту с именем пользователя
$sql_reviews = "SELECT r.*, u.name as user_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.recipe_id = ? AND r.status = 'approved' 
                ORDER BY r.created_at DESC";
$stmt_reviews = mysqli_prepare($connect, $sql_reviews);
mysqli_stmt_bind_param($stmt_reviews, "i", $recipe_id);
mysqli_stmt_execute($stmt_reviews);
$result_reviews = mysqli_stmt_get_result($stmt_reviews);
$reviews = mysqli_fetch_all($result_reviews, MYSQLI_ASSOC);

// Создаем соответствие между значениями caregories и ID чекбоксов
$categoryMapping = [
    'торты' => 'wr1',
    'печенье' => 'wr2',
    'пироги' => 'wr3',
    'кексы' => 'wr4',
    'конфеты' => 'wr5',
    'хлеб' => 'wr6'
];

// Получаем ID чекбокса для текущей категории
$checkboxId = $categoryMapping[strtolower($recipe['caregories'])] ?? '';
$categoryLink = $checkboxId ? 'catalog.php?category=' . urlencode($checkboxId) : '#';


// После выполнения запроса на отзывы
if (empty($reviews)) {
    echo "Нет отзывов для рецепта ID: $recipe_id";
    var_dump($sql_reviews); // Посмотрите какой запрос формируется
    var_dump(mysqli_error($connect)); // Проверьте ошибки SQL
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/recipe_page.css">
    <title><?= htmlspecialchars($recipe['name']) ?></title>
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
            object-fit: contain;
            /* Сохраняет пропорции */
            display: block;
        }

        @media (max-width: 1000px) {
            .main_info_image {
                padding: 20px 130px;
            }

            .main_info_img {
                min-width: 125%;
            }
        }

        @media (max-width: 900px) {
            .main_info_image {
                max-width: 80vw;
            }

            .main_info_img {
                min-width: 115%;
            }
        }

        @media (max-width: 800px) {
            .main_info_image {
                padding: 30px 160px;
                width: 65vw;
            }

            .main_info_img {
                min-width: 130%;
            }
        }

        @media (max-width: 700px) {
            .main_info_image {
                width: 70vw;
            }

            .main_info_img {
                min-width: 130%;
            }
        }

        @media (max-width: 600px) {
            .main_info_img {
                min-width: 170%;
            }
        }

        @media (max-width: 550px) {
            .main_info_image {
                padding: 25px 140px;
            }

            .main_info_img {
                min-width: 180%;
            }
        }

        @media (max-width: 500px) {
            .main_info_img {
                min-width: 210%;
            }
        }

        @media (max-width: 460px) {
            .main_info_image {
                padding: 15px 110px;
            }

            .main_info_img {
                min-width: 130%;
            }
        }

        @media (max-width: 410px) {
            .main_info_img {
                min-width: 180%;
            }
        }

        @media (max-width: 370px) {
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
            <a href="<?= htmlspecialchars($categoryLink) ?>" class="category-link">
                <?= htmlspecialchars($recipe['caregories']) ?> ->
            </a>
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
                        <span class="calories"><?= htmlspecialchars($recipe['calorie']) ?>Ккал</span>
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
    <h2 class="ingre_title">Ингредиенты</h2>
    
    <div class="ingre_content">
        <?php 
        // Разбиваем строку ингредиентов по точкам
        $ingredients = explode('.', $recipe['ingredients']);
        $counter = 1;
        
        foreach ($ingredients as $ingredient):
            // Удаляем лишние пробелы и пропускаем пустые элементы
            $ingredient = trim($ingredient);
            if (!empty($ingredient)):
        ?>
                <div class="ingre_box">
                    <input type="checkbox" class="wr-checkbox<?= $counter ?>" id="wr<?= $counter ?>" name="wr">
                    <label for="wr<?= $counter ?>"></label>
                    <p class="ingre_text"><?= htmlspecialchars($ingredient) ?></p>
                </div>
        <?php 
                $counter++;
            endif;
        endforeach; 
        ?>
    </div>
</section>


    <section class="instruction">
        <h1 class="instru_title">Инструкция</h1>
        <div class="instru_content">
            <?php foreach ($steps as $step): ?>
                <div class="instru_item">
                    <?php if (!empty($step['image_path'])): ?>
                        <div class="instru_item_image">
                            <img src="<?= htmlspecialchars($step['image_path']) ?>" class="instru_item_img"
                                alt="Шаг <?= $step['step_number'] ?>">
                        </div>
                    <?php endif; ?>
                    <div class="instru_item_text">
                        <?php
                        // Разбиваем описание шага по переносам строки
                        $descriptions = explode("\n", $step['description']);
                        foreach ($descriptions as $description):
                            if (!empty(trim($description))): ?>
                                <p><?= htmlspecialchars(trim($description)) ?></p>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <?php if (!isset($_SESSION['user'])): ?>
        <section class="save_auto">
            <form class="modal_form" id="loginForm" action="../connect/auto.php" method="POST">
                <h1 class="save_auto_title">Хотите сохранить этот рецепт?</h1>
                <p class="save_auto_undertitle">Зайдите в свой аккаунт и каждую неделю получайте проверенные рецепты
                    выпечки!</p>
                <p class="save_auto_akk">Нет аккаунта? <a href="../components/modal_reg.php">Зарегистрируйтесь</a></p>
                <div class="save_auto_content">
                    <input class="save_auto_inp" type="email" placeholder="Почта" name="email" required>
                    <input class="save_auto_inp" type="password" placeholder="Пароль" name="password" required>
                    <input type="hidden" name="recipe_id" value="<?= htmlspecialchars($recipe['id']) ?>">
                </div>

                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['message'] . '</div>';
                    unset($_SESSION['message']);
                }
                ?>

                <div class="save_auto_auto">
                    <button type="submit" class="save_auto_btn">Войти</button>
                </div>
            </form>
        </section>
    <?php endif; ?>


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
                <span class="review_quan_num"><?= count($reviews) ?></span>
                <span class=""> Отзыва(ов)</span>
            </div>
            <?php if (isset($_SESSION['user'])): ?>
                <div class="review_leave">
                    <button class="review_leave_btn">Оставить отзыв</button>
                </div>
            <?php endif; ?>
        </div>

        <div class="review_content">
            <?php if (empty($reviews)): ?>
                <p class="no-reviews">Пока нет отзывов к этому рецепту. Будьте первым!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <?php
                    // Получаем информацию о пользователе, оставившем отзыв
                    $user_stmt = $connect->prepare("SELECT name FROM users WHERE id = ?");
                    $user_stmt->bind_param("i", $review['user_id']);
                    $user_stmt->execute();
                    $user_result = $user_stmt->get_result();
                    $user = $user_result->fetch_assoc();
                    ?>

                    <div class="review_box">
                        <div class="review_box_info">
                            <span class="review_name"><?= htmlspecialchars($user['name'] ?? 'Аноним') ?></span>
                            <span class="review_date"><?= date('d.m.Y', strtotime($review['created_at'])) ?></span>
                        </div>
                        <p class="review_box_text"><?= nl2br(htmlspecialchars($review['text'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (count($reviews) > 2): ?>
            <div class="review_more">
                <button class="review_more_btn">Показать ещё+</button>
            </div>
        <?php endif; ?>
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
    <script src="/js/recipe_page.js"></script>
    <script>

        // Проверка статуса входа
        function checkLoginStatus() {
            // Проверяем наличие элемента формы
            const loginForm = document.querySelector('.save_auto');
            if (!loginForm) return;

            // Проверяем, авторизован ли пользователь (через PHP-сессию)
            const isLoggedIn = <?= isset($_SESSION['user']) ? 'true' : 'false' ?>;

            if (isLoggedIn) {
                loginForm.style.display = 'none';
            }
        }


        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.querySelector('.modal_form');

            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(loginForm);

                    fetch(loginForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Добавляем заголовок для идентификации AJAX
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Показываем уведомление об успешном входе
                                document.getElementById('login-success').style.display = 'block';
                                setTimeout(() => {
                                    window.location.reload(); // Перезагружаем страницу
                                }, 1500);
                            } else {
                                // Показываем сообщение об ошибке
                                document.getElementById('login-error').textContent = data.message || 'Ошибка входа';
                                document.getElementById('login-error').style.display = 'block';
                                setTimeout(() => {
                                    document.getElementById('login-error').style.display = 'none';
                                }, 3000);
                            }
                        })
                        .catch(error => {
                            console.error('Ошибка:', error);
                            document.getElementById('login-error').textContent = 'Ошибка соединения';
                            document.getElementById('login-error').style.display = 'block';
                        });
                });
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            // Обработка кнопки "Показать ещё"
            const moreBtn = document.querySelector('.review_more_btn');
            if (moreBtn) {
                moreBtn.addEventListener('click', function () {
                    const hiddenReviews = document.querySelectorAll('.review_box:nth-child(n+5)');
                    hiddenReviews.forEach(review => {
                        review.style.display = 'block';
                    });
                    moreBtn.style.display = 'none';
                });
            }

            // Изначально скрываем отзывы, начиная с 5-го
            document.querySelectorAll('.review_box:nth-child(n+5)').forEach(review => {
                review.style.display = 'none';
            });
        });
    </script>
</body>

</html>