
<?php
session_start();
require_once '../connect/connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/policy.css">
    <title>Политика конфиденциальности</title>
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

    <div class="container">
        <div class="links">
            <a href="home.php" class="link">Главная страница -> </a><a href="#" class="link">Политика конфиденциальности</a>
        </div>
        <div class="policy_container">
            <h1 class="policy_title">Политика конфиденциальности</h1>

            <div class="container_whatData">
                <h1 class="container_pounds_title">1. Какие данные мы собираем</h1>

                <div class="point_1-1">

                    <h3 class="privacy-section__title">1.1. Данные, которые вы добровольно предоставляете</h3>
                    <p class="privacy-section__description">Мы собираем информацию, которую вы сознательно указываете на
                        нашем сайте:</p>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Имя и контакты:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">При подписке на рассылку (email).</li>
                                <li class="privacy-list__item">При регистрации аккаунта (имя, email, пароль*).</li>
                                <li class="privacy-list__item">При комментировании рецептов (имя, email, текст
                                    сообщения).
                                </li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Обратная связь:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Вопросы через форму «Свяжитесь с нами» (имя, email, текст
                                    сообщения).</li>
                            </ul>
                        </ul>
                    </div>
                </div>
                <div class="point_1-2">

                    <h3 class="privacy-section__title">1.2. Данные, собираемые автоматически</h3>
                    <p class="privacy-section__description">При посещении сайта фиксируется</p>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Техническая информация:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">IP-адрес, тип браузера и ОС.</li>
                                <li class="privacy-list__item">Данные cookie (для запоминания предпочтений, например,
                                    выбранного языка).</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Статистика поведения:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Какие рецепты вы просматривали, время на странице
                                    (через Google Analytics или Яндекс.Метрику).</li>
                                <li class="privacy-list__item">Клики по кнопкам (чтобы улучшать удобство сайта).</li>
                            </ul>
                        </ul>
                    </div>
                </div>
                <div class="point_1-3">

                    <h3 class="privacy-section__title">1.3. Данные от сторонних сервисов</h3>
                    <p class="privacy-section__description">Если вы входите через соцсети (Google, Facebook), мы
                        получаем:</p>

                    <div class="privacy-list">
                        <ul class="privacy-list__items">
                            <li class="privacy-list__item">Базовый профиль: имя, email (с вашего согласия).</li>
                            <li class="privacy-list__item">Аватар (если вы разрешаете доступ).</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="container_howUse">
                <h1 class="container_pounds_title">2. Как мы используем ваши данные</h1>
                <p style="margin: 0;">Мы применяем собранную информацию исключительно для улучшения вашего опыта на
                    сайте и обеспечения его
                    работы. Все данные обрабатываются в соответствии с законодательством о защите персональных данных.
                </p>

                <div class="point_1-1">

                    <h3 class="privacy-section__title">2.1. Основные цели использования</h3>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Предоставление услуг:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Отправка рецептов, кулинарных подборок и уведомлений
                                    (если вы подписались на рассылку).</li>
                                <li class="privacy-list__item">Регистрация и обслуживание аккаунта (если есть личный
                                    кабинет).</li>
                                <li class="privacy-list__item">Обратная связь (ответы на ваши вопросы через формы связи
                                    или комментарии).</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Персонализация контента:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Рекомендации рецептов на основе ваших предпочтений
                                    (анализ просмотренных страниц).</li>
                                <li class="privacy-list__item">Запоминание настроек (например, выбранной темы сайта или
                                    языка).</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Аналитика и развитие сайта:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Оценка популярности рецептов (через статистику
                                    посещений).</li>
                                <li class="privacy-list__item">Улучшение удобства интерфейса (анализ кликов, времени на
                                    странице).</li>
                                <li class="privacy-list__item">Обнаружение и предотвращение технических сбоев.</li>
                            </ul>
                        </ul>
                    </div>
                </div>
                <div class="point_1-2">

                    <h3 class="privacy-section__title">2.2. Коммуникация с пользователями</h3>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Email-рассылка</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Только для подписчиков. Каждое письмо содержит ссылку для
                                    отписки.</li>
                                <li class="privacy-list__item">Типы писем: новые рецепты, акции, кулинарные советы.</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Уведомления на сайте:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Оповещения о новых комментариях к вашим рецептам (если
                                    есть функция профиля).</li>
                                <li class="privacy-list__item">Информация о технических обновлениях (редко, только при
                                    необходимости).</li>
                            </ul>
                        </ul>
                    </div>
                </div>
                <div class="point_1-3">

                    <h3 class="privacy-section__title">Чего мы НЕ делаем</h3>


                    <div class="privacy-list">
                        <ul class="privacy-list__items">
                            <li class="privacy-list__item">Не передаем ваши данные третьим лицам (кроме анонимной
                                статистики для Google Analytics).</li>
                            <li class="privacy-list__item">Не используем информацию для спама или назойливой рекламы.
                            </li>
                            <li class="privacy-list__item">Не продаем email-адреса или историю просмотров.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="container_howUse">
                <h1 class="container_pounds_title">3. Защита данных</h1>
                <p style="margin: 0;">Мы применяем комплексные меры для обеспечения безопасности ваших персональных
                    данных, предотвращения утечек и несанкционированного доступа.
                </p>

                <div class="point_1-1">

                    <h3 class="privacy-section__title">3.1. Технические меры защиты</h3>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Шифрование данных:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Все передаваемые данные (включая формы обратной связи,
                                    регистрацию и платежи) защищены протоколом HTTPS (TLS 1.2+).</li>
                                <li class="privacy-list__item">Пароли хранятся в зашифрованном виде (алгоритмы
                                    хеширования, например, bcrypt).</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Защита серверов:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Регулярные обновления ПО и патчи для устранения
                                    уязвимостей.</li>
                                <li class="privacy-list__item">Использование брандмауэров (WAF) и систем обнаружения
                                    вторжений (IDS).</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Резервное копирование:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Ежедневные бэкапы данных на отдельные защищенные серверы.
                                </li>
                            </ul>
                        </ul>
                    </div>
                </div>
                <div class="point_1-2">

                    <h3 class="privacy-section__title">3.2. Организационные меры</h3>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Ограничение доступа:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Только уполномоченные сотрудники (например,
                                    администраторы) имеют доступ к персональным данным.</li>
                                <li class="privacy-list__item">Для доступа требуется двухфакторная аутентификация (2FA).
                                </li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Обучение персонала:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Инструктаж по обработке данных и действиям при утечках
                                    (по стандарту GDPR или 152-ФЗ).</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Договоры с подрядчиками:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Если данные передаются сторонним сервисам (например,
                                    хостингу), они обязаны соблюдать конфиденциальность.</li>
                            </ul>
                        </ul>
                    </div>
                </div>

                <div class="point_1-2">

                    <h3 class="privacy-section__title">3.3. Работа с cookie и трекерами</h3>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Типы cookie:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Необходимые: для работы сайта (например, сохранение
                                    авторизации).</li>
                                <li class="privacy-list__item">Аналитические: сбор анонимной статистики (Google
                                    Analytics).
                                </li>
                                <li class="privacy-list__item">Маркетинговые: показ релевантной рекламы (если есть, с
                                    вашего согласия).
                                </li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Управление:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">При первом посещении вы можете отказаться от
                                    необязательных cookie через всплывающее окно.</li>
                                <li class="privacy-list__item">В настройках браузера можно полностью отключить cookie
                                    (но некоторые функции сайта перестанут работать).</li>
                            </ul>
                        </ul>
                    </div>
                </div>
                <div class="point_1-2">

                    <h3 class="privacy-section__title">3.4. Действия при утечке данных</h3>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Уведомление:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Если произошла утечка, мы сообщим вам в течение 72
                                    часов (по email или на сайте).</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Меры:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Сброс паролей затронутых аккаунтов.</li>
                                <li class="privacy-list__item">Временная блокировка подозрительных действий.
                                </li>
                                <li class="privacy-list__item">
                                    Расследование с привлечением кибербезопасности.</li>
                            </ul>
                        </ul>
                    </div>
                </div>
                <div class="point_1-2">

                    <h3 class="privacy-section__title">3.5. Ваши возможности</h3>

                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Вы можете:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item">Отозвать согласие на обработку данных (написать на
                                    privacy@вашсайт.ru).</li>
                                <li class="privacy-list__item">
                                    Удалить аккаунт (в настройках профиля или по запросу).
                                </li>
                                <li class="privacy-list__item">
                                    Запросить отчет: какие ваши данные мы храним.</li>
                            </ul>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="container_howUse">
                <h1 class="container_pounds_title">4. Ваши права</h1>

                <div class="point_1-1">
                    <div class="privacy-list">
                        <ul class="privacy-list__title">
                            <li>Вы можете:</li>
                            <ul class="privacy-list__items">
                                <li class="privacy-list__item"> Запросить удаление ваших данных.</li>
                                <li class="privacy-list__item">Отключить cookie в настройках браузера.</li>
                            </ul>
                        </ul>
                    </div>
                </div>
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
</body>

</html>