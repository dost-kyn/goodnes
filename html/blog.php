<?php
session_start();
require_once '../connect/connect.php';

// Исправленный запрос - убрал несуществующий столбец number и добавил image
$sql_blogs = "SELECT id, name FROM blogs ORDER BY created_at DESC";
$result_blogs = mysqli_query($connect, $sql_blogs);

if (!$result_blogs) {
    die("Ошибка при получении блогов: " . mysqli_error($connect));
}

$blogs = mysqli_fetch_all($result_blogs, MYSQLI_ASSOC);

// Для каждого блога получаем первый шаг (step_id = 1) из таблицы blog_steps
foreach ($blogs as &$blog) {
    $blog_id = $blog['id'];
    $sql_step = "SELECT image, description FROM blog_steps 
                WHERE blog_id = $blog_id AND step_number = 1 
                LIMIT 1";
    $result_step = mysqli_query($connect, $sql_step);

    if ($result_step && mysqli_num_rows($result_step) > 0) {
        $step = mysqli_fetch_assoc($result_step);
        $blog['image'] = $step['image'];
        $blog['description'] = $step['description'];
    } else {
        // Значения по умолчанию, если шаг не найден
        $blog['image'] = '/image/blog/default.jpg';
        $blog['description'] = 'Описание отсутствует';
    }
}
unset($blog); // Разрываем ссылку на последний элемент
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/blog.css">
    <title>Блоги</title>
    <style>
        .blogs_card_link {
    text-decoration: none;
    color: inherit;
    display: block;
}
  .blogs_card_image{
    max-width: 280px;
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

    <section class="crumds">
        <div class="crumbs_content">
            <a href="home.php">Главная страница -> </a><a href="blog.php">Блоги</a>
        </div>
    </section>

    <section class="blogs">
        <h1 class="blogs_title">Блоги</h1>
        <div class="blogs_cards">
            <?php foreach ($blogs as $blog): ?>
                <a href="blog_page.php?id=<?= $blog['id'] ?>" class="blogs_card_link">
                <div class="blogs_card">
                    <div class="blogs_card_image">
                        <img src="<?= htmlspecialchars($blog['image']) ?>" class="blogs_card_img">
                    </div>
                    <div class="blogs_card_infa">
                        <p class="blogs_card_title"><?= htmlspecialchars($blog['name']) ?></p>
                        <p class="blogs_card_text">
                            <?=
                                // Обрезаем описание до 100 символов, если оно длинное
                                mb_strlen($blog['description']) > 100
                                ? htmlspecialchars(mb_substr($blog['description'], 0, 100) . '...')
                                : htmlspecialchars($blog['description'])
                                ?>
                        </p>
                    </div>
                </div>
                </a>
            <?php endforeach; ?>
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
</body>

</html>