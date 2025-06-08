<?php

require_once '../connect/connect.php';

session_start();

$user_id = isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : 0;

$sql = "SELECT 
           r.id,
           r.name,
           r.cooking_time,
           r.calorie,
           r.caregories,
           r.maun_image,
           IFNULL((SELECT 1 FROM saved_recipes sr WHERE sr.user_id = ? AND sr.recipe_id = r.id LIMIT 1), 0) AS is_saved
        FROM 
           recipes r
        ORDER BY r.id DESC"; // Добавлен порядок сортировки

$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);


// Добавляем отладочный вывод для проверки запроса (удалите в продакшене)
error_log("Executing query: " . $sql);
error_log("With user_id: " . $user_id);

$stmt = mysqli_prepare($connect, $sql);
if (!$stmt) {
    die("Ошибка подготовки запроса: " . mysqli_error($connect));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);

if (!mysqli_stmt_execute($stmt)) {
    die("Ошибка выполнения запроса: " . mysqli_stmt_error($stmt));
}

$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    die("Ошибка получения результата: " . mysqli_error($connect));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/catalog.css">
    <title>Каталог</title>
    <style>

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
                <!-- <a href="profile.php" class="mobile-menu-link">Профиль</a> -->
                <a href="profile.php?user_id=<?php echo $_SESSION['user']['id']; ?>" class="mobile-menu-link">Профиль</a>
                <a href="/connect/logout.php" class="mobile-menu-link">Выйти</a>
            <?php else: ?>
                <a href="/components/modal_auth.php" class="mobile-menu-link">Войти</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="crumds">
        <div class="crumbs_content">
            <a href="home.php">Главная страница -> </a><a href="#">Каталог</a>
        </div>
    </section>

    <section class="catalog">
        <h1 class="catalog_content_title">Каталог</h1>

        <div class="catalog_content">

            <div class="catalog_filter">
                <div class="catalog_filter_content">
                    <button class="close-filters">×</button>
                    <h1 class="catalog_filter_title">Фильтр</h1>

                    <div class="catalog_filter_column"> <!-- ˅ -->
                        <p class="calalog_fil_col_title">⮟ Категории</p>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox1" id="wr1" name="wr">
                            <label for="wr1"></label>
                            <p class="paragraph_text">Торты</p>
                        </div>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox2" id="wr2" name="wr">
                            <label for="wr2"></label>
                            <p class="paragraph_text">Печенье</p>
                        </div>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox3" id="wr3" name="wr">
                            <label for="wr3"></label>
                            <p class="paragraph_text">Пироги</p>
                        </div>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox4" id="wr4" name="wr">
                            <label for="wr4"></label>
                            <p class="paragraph_text">Кексы</p>
                            <!-- Конфеты -->
                        </div>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox5" id="wr5" name="wr">
                            <label for="wr5"></label>
                            <p class="paragraph_text">Конфеты</p>
                            <!-- Хлеб -->
                        </div>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox6" id="wr6" name="wr">
                            <label for="wr6"></label>
                            <p class="paragraph_text">Хлеб</p>
                            <!-- Кексы -->
                        </div>

                        <!-- <div class="paragraph">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" class="paragraph_inp" >
                                      </label>
                                    <p class="paragraph_text">Торты</p>
                                </div> -->
                    </div>

                    <div class="catalog_filter_column">
                        <p class="calalog_fil_col_title">⮟ По времени</p>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox9" id="wr9" name="wr">
                            <label for="wr9"></label>
                            <p class="paragraph_text">
                                < 30 мин</p>
                        </div>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox7" id="wr7" name="wr">
                            <label for="wr7"></label>
                            <p class="paragraph_text">30-60 мин</p>
                        </div>
                        <div class="paragraph">
                            <input type="checkbox" class="wr-checkbox8" id="wr8" name="wr">
                            <label for="wr8"></label>
                            <p class="paragraph_text">> 60 мин</p>
                        </div>
                        <!-- <div class="paragraph">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" class="paragraph_inp" >
                                      </label>
                                    <p class="paragraph_text">< 30 мин</p>
                                </div> -->
                    </div>

                    <div class="catalog_filter_column">
                        <p class="calalog_fil_col_title">⮟ Калорийность</p>
                        <div class="paragraph_number">
                            <p class="paragraph_text_number">От:</p>
                            <label class="custom-number">
                                <input type="number" class="paragraph_inp_num" value="0">
                            </label>
                        </div>

                        <div class="paragraph_number">
                            <p class="paragraph_text_number">До:</p>
                            <label class="custom-number">
                                <input type="number" class="paragraph_inp_num" value="0">
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="catalog_recipes">
                <div class="search">
                    <input class="search_inp" type="text" placeholder="Поиск..">
                    <button class="search_btn">Найти</button>
                    <button class="filter-mobile-toggle">Фильтры ☰</button>
                </div>

                <!-- 1 -->
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
                                <button class="recipes_btn save-recipe <?= $card['is_saved'] ? 'saved' : '' ?>"
                                    data-recipe-id="<?= $card['id'] ?>">
                                    <?= $card['is_saved'] ? 'Сохранено' : 'Сохранить' ?>
                                </button>
                            <?php endif; ?>
                        </a>
                    <?php endwhile; ?>

                    <!--<div class="recipes_card" data-id="2" data-recipe-id="<?= htmlspecialchars($card['id']) ?>">
                        <h1 class="recipes_title">Батон</h1>
                        <div class="recipes_image">
                            <img src="/image/home/Батон.png" alt="" class="recipes_image_img">
                        </div>
                        <div class="recipes_info">
                            <p class="recipes_calory">Калорийность: 266 ккал</p>
                            <p class="recipes_category">Категория: хлеб</p>
                        </div>
                        <button class="recipes_btn">Cохранить</button>
                    </div> -->
                </div>
                <div class="recipes_more">
                    <button class="recipes_more_btn">Показать ещё+</button>
                </div>
            </div>

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

    <script>
       document.querySelectorAll('.save-recipe').forEach(button => {
    button.addEventListener('click', async function(e) {
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



        // document.querySelectorAll('.save-recipe').forEach(button => {
        //     button.addEventListener('click', async function (e) {
        //         e.preventDefault();
        //         e.stopPropagation();

        //         const recipeId = this.dataset.recipeId;
        //         const isSaved = this.classList.contains('saved');
        //         const action = isSaved ? 'remove' : 'add';

        //         // // Временный код для проверки - перенаправление на save_recipe.php с параметрами
        //         // window.location.href = `save_recipe.php?recipe_id=${recipeId}&action=${action}`;

        //         if (!recipeId || isNaN(recipeId)) {
        //             console.error('Invalid recipeId:', recipeId);
        //             alert('Ошибка: некорректный ID рецепта');
        //             return;
        //         }

        //         const isSaved = this.classList.contains('saved');
        //         const button = this;

        //         try {
        //             const response = await fetch('save_recipe.php', {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/x-www-form-urlencoded',
        //                 },
        //                 body: `recipe_id=${recipeId}&action=${isSaved ? 'remove' : 'add'}`
        //             });

        //             if (!response.ok) throw new Error('Network error');

        //             const data = await response.json();

        //             if (data.success) {
        //                 button.classList.toggle('saved');
        //                 button.textContent = isSaved ? 'Сохранить' : 'Сохранено';
        //                 button.style.backgroundColor = isSaved ? '' : '#4CAF50';
        //             } else {
        //                 alert(data.message || 'Ошибка операции');
        //             }
        //         } catch (error) {
        //             console.error('Error:', error);
        //             alert('Ошибка соединения с сервером');
        //         }
        //     });
        // });
    </script>
    <script src="/js/tema.js"></script>
    <script src="/js/catalog.js"></script>
</body>

</html>