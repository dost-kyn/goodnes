<?php

session_start();
require_once '../connect/connect.php';

// Получаем ID рецепта из URL
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Защита от SQL-инъекций
if ($blog_id <= 0) {
    die("Неверный ID рецепта");
}

// Запрос для получения основной информации о рецепте
$sql_blog = "SELECT * FROM blogs WHERE id = ?";
$stmt_blog = mysqli_prepare($connect, $sql_blog);
mysqli_stmt_bind_param($stmt_blog, "i", $blog_id);
mysqli_stmt_execute($stmt_blog);
$result_blog = mysqli_stmt_get_result($stmt_blog);
$blog = mysqli_fetch_assoc($result_blog);

if (!$blog) {
    die("Блог не найден");
}

// Получаем все шаги блога с сортировкой по step_number
$sql_steps = "SELECT step_number, image, description, title 
              FROM blog_steps 
              WHERE blog_id = ? 
              ORDER BY step_number ASC";
$stmt_steps = mysqli_prepare($connect, $sql_steps);
mysqli_stmt_bind_param($stmt_steps, "i", $blog_id);
mysqli_stmt_execute($stmt_steps);
$result_steps = mysqli_stmt_get_result($stmt_steps);
$steps = mysqli_fetch_all($result_steps, MYSQLI_ASSOC);

// Получаем первый шаг для главного изображения
$main_image = '/image/blog/default.jpg';
if (count($steps) > 0 && !empty($steps[0]['image'])) {
    $main_image = $steps[0]['image'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/blog_page.css">
    <title><?= htmlspecialchars($blog['name']) ?></title>
</head>

<body>
    <header class="header">
        <div class="header_content">
            <a class="header_logo" href="home.html">
                <img src="/image/лого.svg" data-theme-image data-light="/image/лого.svg"
                    data-dark="/image/лого-dark.svg" class="header_logo_img">
            </a>

            <div class="header_nav">
                <button class="header_nav_tema">
                    <img src="/image/tema.svg" data-theme-image data-light="/image/tema.svg"
                        data-dark="/image/tema-dark.svg" class="header_nav_tema_img">
                </button>
                <a href="/html/catalog.html" class="header_nav_catalog">Каталог</a>
                <a href="" class="header_nav_blog">Блог</a>
                <a href="" class="header_nav_exit">Вход</a>

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
            <a href="home.php">Главная страница -> </a><a href="blog.php">Блоги -> </a><a
                href="blog_page.php?id=<?= $blog['id'] ?>"><?= htmlspecialchars($blog['name']) ?></a>
        </div>
        <div class="back_content">
            <a href="blog.php">Назад -></a>
        </div>
    </section>

    <section class="main">

        <h1 class="main_name"><?= htmlspecialchars($blog['name']) ?></h1>
        <div class="main_content">
            <?php if (count($steps) > 0): ?>
                <?php foreach ($steps as $index => $step): ?>
                    <?php if ($index === 0 && !empty($step['image'])): ?>
                        <div class="main_image">
                            <img src="<?= htmlspecialchars($step['image']) ?>" alt="<?= htmlspecialchars($blog['name']) ?>"
                                class="main_img">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($step['title'])): ?>
                        <p class="main_title"><?= htmlspecialchars($step['title']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($step['image']) && $index > 0): ?>
                        <div class="main_image">
                            <img src="<?= htmlspecialchars($step['image']) ?>" alt="<?= htmlspecialchars($step['title'] ?? '') ?>"
                                class="main_img">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($step['description'])): ?>
                        <div class="desc">
                            <?= nl2br(htmlspecialchars($step['description'])) ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="desc">
                    <p>Содержание блога пока недоступно.</p>
                </div>
            <?php endif; ?>
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
</body>
<script src="/js/tema.js"></script>

</html>