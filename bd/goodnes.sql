-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 07 2025 г., 13:45
-- Версия сервера: 8.0.30
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `goodnes`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blogs`
--

CREATE TABLE `blogs` (
  `id` int NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_image` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `blog_steps`
--

CREATE TABLE `blog_steps` (
  `id` int NOT NULL,
  `blog_id` int NOT NULL,
  `step_number` int NOT NULL COMMENT 'Порядковый номер этапа',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `recipes`
--

CREATE TABLE `recipes` (
  `id` int NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooking_time` int NOT NULL,
  `calorie` int NOT NULL,
  `portions` int NOT NULL,
  `caregories` enum('торты','печенье','пироги','кексы','конфеты','хлеб') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `maun_image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ingredients` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_review` int NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `cooking_time`, `calorie`, `portions`, `caregories`, `maun_image`, `description`, `ingredients`, `number_review`, `created_at`) VALUES
(1, 'Purple velvet', 90, 320, 12, 'кексы', '/image/recipe/684354ffa00e1.png', 'Погрузитесь в мир цвета и вкуса с нашим неотразимым рецептом кексов «Фиолетовый бархат». Эти кексы с ярким оттенком и бархатистой текстурой, напоминающей классический «Красный бархат», очаруют вас. Независимо от того, празднуете ли вы особое событие или просто хотите полакомиться сладким, эти кексы фиолетового цвета обязательно произведут впечатление. Присоединяйтесь к нам, и мы раскроем секреты приготовления этих восхитительных лакомств на вашей кухне.', 'Мука 250 г. Сахар 300 г. Сливочное масло	200 г.  Яйца 3 шт. Пурпурный пищевой краситель. Какао-порошок. Кефир 250 мл. Сода. Ванильный экстракт. Сливочный сыр 400 г. Сахарная пудра 150 г. Сливки 33% 200 мл.', 0, '2024-05-27'),
(3, 'тирамису', 45, 445, 8, 'торты', '/image/recipe/6841fd5da3316.png', 'ацуауцацуа', 'мука. яйцо. масло.', 0, '2025-06-05'),
(4, 'тирамису', 45, 445, 8, 'торты', '/image/recipe/6841fdf357d62.png', 'ацуауцацуа', 'мука. яйцо. масло.', 0, '2025-06-05');

-- --------------------------------------------------------

--
-- Структура таблицы `recipe_steps`
--

CREATE TABLE `recipe_steps` (
  `id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `step_number` int NOT NULL COMMENT 'Порядковый номер этапа',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `recipe_steps`
--

INSERT INTO `recipe_steps` (`id`, `recipe_id`, `step_number`, `description`, `image_path`) VALUES
(1, 1, 1, '\r\n                \r\n                \r\n                \r\n                \r\n                \r\n                Взбиваем масло и сахар.\r\n\r\nВ большой миске взбейте 200 г сливочного масла с 300 г сахара на средней скорости миксера 5–7 минут, пока масса не станет светлой и воздушной.                                                                                                                                        ', '/image/recipe/steps/684362233b142.jpg'),
(2, 1, 2, '\r\n                \r\n                \r\n                \r\n                \r\n\r\n                Добавляем яйца, краситель и ваниль.\r\n\r\nПо одному вводите 3 яйца, каждый раз взбивая до однородности. Добавьте 2 ст. л. пурпурного красителя (можно гелевого или порошкового) и 1 ч. л. ванильного экстракта. Взбивайте, пока цвет не станет ровным и насыщенным. \r\n\r\nЧередуем сухие и жидкие ингредиенты.\r\n   \r\nВ отдельной миске смешайте 250 г муки и 2 ст. л. какао. Начинайте добавлять в тесто: 1/3 сухой смеси → перемешайте лопаткой. 125 мл кефира→ аккуратно вмешайте. Повторите еще два раза, заканчивая мукой.\r\n\r\nГасим соду и завершаем тесто. В 1 ч. л. уксуса добавьте 1 ч. л. соды (начнется реакция с пузырьками). Быстро введите в тесто и аккуратно перемешайте. Консистенция: густая, но льющаяся, насыщенного фиолетового цвета.                                                                                                                                                       ', '/image/recipe/steps/6843636bf31cc.jpg'),
(3, 1, 3, '\r\n                \r\n                \r\n                Выпечка коржей.\r\n\r\nРазделите тесто на 2 формы (диаметр 20 см), смазанные маслом и присыпанные мукой. Выпекайте при 175°C 25–30 минут (проверяйте зубочисткой: если сухая — готово). Дайте остыть 10 минут в форме, затем переверните на решетку.                                            ', '/image/recipe/steps/684364ca44620.png'),
(4, 1, 4, '\r\n                \r\n                Приготовление крема.\r\n\r\nВзбиваем сливки: 200 мл холодных сливок (33%) взбейте с 150 г сахарной пудры до устойчивых пиков (около 3–4 минут). Взбиваем сливочный сыр: В другой миске 400 г сливочного сыра (например, Philadelphia) взбейте 2–3 минуты до гладкости. Соединяем крем: Аккуратно вмешайте сливки в сыр лопаткой (движения снизу вверх, чтобы не осели).                           ', '/image/recipe/steps/684366b3d7abb.jpg'),
(5, 1, 5, 'Сборка кексов. \r\n\r\nПромажьте коржи кремом (можно добавить прослойку из ягод). Покройте кекс полностью. Украсьте: Фиолетовой сахарной крошкой; Свежими ягодами (ежевика, черника); Цветами из крема.', '/image/recipe/steps/6843681e9b316.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` date NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_deletion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletion_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `recipe_id`, `text`, `created_at`, `status`, `reason_deletion`, `deletion_time`) VALUES
(1, 2, 4, 'мне очень понравились эти кексы, но кекс моего парня на вкус лучше', '2025-05-27', 'approved', NULL, NULL),
(2, 13, 1, 'dsdsdsdds', '2024-05-27', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `saved_recipes`
--

CREATE TABLE `saved_recipes` (
  `user_id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `saved_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numder_recipes` int NOT NULL,
  `created_at` date NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `numder_recipes`, `created_at`, `role`) VALUES
(1, 'Иосиф Сталин', 'kolhoz@gmail.com', '$2y$10$acA/.vMKQcwp/hDnL9D3puxiTKQhp115sziESAy5A1mTCW9C1PiGK', 0, '2025-05-28', 'user'),
(2, 'Омар Рудберг', 'omar@gmail.com', '$2y$10$fHUE3a0s5WkhJC1PXvS31.v.JUl2BrfFtZNQlzOIIW.MGhS7DmGfG', 0, '2025-05-28', 'user'),
(3, 'Альбер Камю', 'existentialism@mail.ru', '$2y$10$CyrwgQJtrOCRexFGrKW9H.s3BPnBthtqX5PZYdlPw9s8aonYndU2q', 0, '2025-05-28', 'user'),
(13, 'Эдвин Рюдинг', 'IloveOmar@mail.ru', '$2y$10$hRF2e8GKN5BVEfb5jEWtDedwj8cJCpq3z1Y36KwlALDh8dBj9pWoK', 0, '2025-05-28', 'user'),
(15, 'вика', 'vika@gmail.com', '$2y$10$rg6DI68iSaLzy3tfx2pQse/heDDdLTZgZ7oWch3u9m2edtqBJokcG', 0, '2025-06-07', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blog_steps`
--
ALTER TABLE `blog_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`);

--
-- Индексы таблицы `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `recipe_steps`
--
ALTER TABLE `recipe_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Индексы таблицы `saved_recipes`
--
ALTER TABLE `saved_recipes`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `blog_steps`
--
ALTER TABLE `blog_steps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `recipe_steps`
--
ALTER TABLE `recipe_steps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `blog_steps`
--
ALTER TABLE `blog_steps`
  ADD CONSTRAINT `blog_steps_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `recipe_steps`
--
ALTER TABLE `recipe_steps`
  ADD CONSTRAINT `recipe_steps_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `saved_recipes`
--
ALTER TABLE `saved_recipes`
  ADD CONSTRAINT `saved_recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `saved_recipes_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
