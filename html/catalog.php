<?php

require_once  '../connect/connect.php';

session_start();

$sql = "SELECT 
           id,
           name,
           cooking_time,
           calorie,
           caregories,
           maun_image
        FROM recipes";
$result = mysqli_query($connect, $sql);

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
                            <a href="recipe_page.php?id=<?= htmlspecialchars($card['id']) ?>" class="recipes_card" data-id="<?= htmlspecialchars($card['id']) ?>" 
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
                                    <p class="recipes_calory">Калорийность: <?= htmlspecialchars($card['calorie']) ?> ккал
                                    </p>
                                    <p class="recipes_category">Категория: <?= htmlspecialchars($card['caregories']) ?></p>
                                </div>
                                <button class="recipes_btn">Сохранить</button>
                            </a>
                        <?php endwhile; ?>

                    <!--<div class="recipes_card" data-id="2">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
  // Получаем параметр ?category= из URL
  const urlParams = new URLSearchParams(window.location.search);
  const categoryId = urlParams.get('category'); 

  // Если параметр есть, находим чекбокс и отмечаем его
  if (categoryId) {
    const checkbox = document.getElementById(categoryId);
    if (checkbox) {
      checkbox.checked = true; // Отмечаем галочку
      
      // Прокручиваем к этому чекбоксу (опционально)
      setTimeout(() => {
        checkbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 100);
    }
  }

  // Убираем параметр ?category=... из URL после загрузки
    if (window.location.search.includes('category=')) {
    const newUrl = window.location.pathname; // Получаем URL без параметров
    window.history.replaceState(null, null, newUrl); // Заменяем URL без перезагрузки
    }
    // Сброс всех чекбоксов категорий
    if (categoryParam) {
    document.querySelectorAll('.catalog_filter_column input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    }




     // Обработка ссылок категорий в хлебных крошках
    document.querySelectorAll('.category-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href') !== '#') {
                // Сброс всех чекбоксов перед переходом (если нужно)
                document.querySelectorAll('.catalog_filter_column input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                // Переход произойдет автоматически по ссылке
            } else {
                e.preventDefault(); // Отменяем переход для неопределенных категорий
            }
        });
    });
});


</script>

    <script src="/js/tema.js"></script>
    <script src="/js/catalog.js"></script>
</body>

</html>