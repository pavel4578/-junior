-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 29 2026 г., 09:40
-- Версия сервера: 5.7.39-log
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `junior`
--

-- --------------------------------------------------------

--
-- Структура таблицы `application_statuses`
--

CREATE TABLE `application_statuses` (
  `user_id` int(11) NOT NULL,
  `vacancy_id` int(11) NOT NULL,
  `status` enum('приглашение','принят','отказ') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_comment` text COLLATE utf8mb4_unicode_ci,
  `status_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `application_statuses`
--

INSERT INTO `application_statuses` (`user_id`, `vacancy_id`, `status`, `admin_comment`, `status_at`) VALUES
(3, 3, 'приглашение', 'Поздравляем! Мы хотим пригласить вас на собеседование.', '2026-09-25 15:20:15');

-- --------------------------------------------------------

--
-- Структура таблицы `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `contact_info` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `companies`
--

INSERT INTO `companies` (`id`, `name`, `address`, `contact_info`) VALUES
(1, 'ООО \"Цифровые Решения\"', 'Москва, ул. Тестовая, д.1', '+79811234567'),
(2, 'ИП Студия Кода', 'Санкт-Петербург, пр. Невский, д.20', 'hr@codestudio.ru'),
(3, 'Авито', 'Москва, ул. Тестовая, д.1', '1234hr@codestudio.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `resumes`
--

CREATE TABLE `resumes` (
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `resumes`
--

INSERT INTO `resumes` (`user_id`, `file_path`, `uploaded_at`) VALUES
(9, 'uploads/resumes/user_9_cv.pdf', '2026-09-28 07:46:32');

-- --------------------------------------------------------

--
-- Структура таблицы `student_vacancy_actions`
--

CREATE TABLE `student_vacancy_actions` (
  `user_id` int(11) NOT NULL,
  `vacancy_id` int(11) NOT NULL,
  `status` enum('просмотрено','подана заявка') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `student_vacancy_actions`
--

INSERT INTO `student_vacancy_actions` (`user_id`, `vacancy_id`, `status`, `action_at`) VALUES
(1, 1, 'просмотрено', '2026-09-25 15:20:15'),
(1, 3, 'подана заявка', '2026-09-26 15:18:15'),
(1, 11, 'подана заявка', '2026-09-26 09:20:42'),
(1, 12, 'подана заявка', '2026-09-26 13:21:21'),
(1, 13, 'подана заявка', '2026-09-26 15:51:00'),
(1, 14, 'подана заявка', '2026-09-28 11:44:53'),
(3, 3, 'подана заявка', '2026-09-25 15:20:15'),
(4, 1, 'подана заявка', '2026-09-26 16:02:18'),
(4, 11, 'подана заявка', '2026-09-26 08:49:01'),
(4, 12, 'просмотрено', '2026-09-26 16:27:25'),
(4, 13, 'подана заявка', '2026-09-28 12:36:22'),
(4, 14, 'подана заявка', '2026-09-29 06:35:23'),
(5, 14, 'подана заявка', '2026-09-26 16:35:06'),
(9, 14, 'подана заявка', '2026-09-28 07:45:30');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `fio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` char(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('student','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `fio`, `login`, `password`, `phone`, `email`, `role`) VALUES
(1, 'Иванов Иван Иванович', '111', '111', '+79001234567', 'ivan@example.com', 'student'),
(2, 'Петрова Мария Сергеевна', '222', '222', '+79012345678', 'petrova@example.com', 'admin'),
(3, 'Кузнецов Олег Викторович', '000', '000', '+79023456789', 'kuznec@example.com', 'student'),
(4, 'Нечаева Ольга Васильевна', '666', '666', '+79023456789', '5@gmail.com', 'student'),
(5, 'Быкадорова Альбина Сергеевна', '1', '1', '+79023456789', '2@gmail.com', 'student'),
(9, 'Хрусталева Инна Васильевна', '0', '0', '+79023456789', 'pavel459832@gmail.com', 'student');

-- --------------------------------------------------------

--
-- Структура таблицы `vacancies`
--

CREATE TABLE `vacancies` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `tech_stack` text COLLATE utf8mb4_unicode_ci,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format_type` enum('вакансия','стажировка') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `places_count` smallint(6) DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `vacancies`
--

INSERT INTO `vacancies` (`id`, `company_id`, `title`, `salary`, `tech_stack`, `img`, `format_type`, `places_count`, `description`, `created_at`) VALUES
(1, 1, 'Junior Python Developer', '80000.00', 'Python, Django, PostgreSQL', '11.png', 'вакансия', 3, 'Ищем Junior Python разработчика для развития внутренних экосистем компании.\r\n\r\nОбязанности:\r\n- Разработка и поддержка серверной логики на Django.\r\n- Интеграция сторонних сервисов по API.\r\n- Оптимизация SQL-запросов к PostgreSQL.\r\n\r\nТребования: базовые знания Python, понимание работы реляционных БД, готовность обучаться.', '2026-09-25 15:20:15'),
(2, 1, 'Стажер Data Science', '80000.00', 'Python, Pandas, ML', '2.png', 'стажировка', 5, 'Стажировка в отделе Big Data для начинающих специалистов.\r\n\r\nЗадачи:\r\n- Сбор, очистка и предобработка данных с помощью Pandas.\r\n- Участие в построении простейших моделей машинного обучения.\r\n\r\nТребования: знание синтаксиса Python, базовое понимание математической статистики, Jupyter Notebook.', '2026-09-25 15:20:15'),
(3, 2, 'Frontend-разработчик (Junior)', '90000.00', 'React.js, HTML/CSS, JavaScript', '3.png', 'вакансия', 2, 'Ищем junior-разработчика интерфейсов в команду клиентского сервиса.\r\n\r\nЧто делать:\r\n- Верстка адаптивных веб-страниц по макетам из Figma.\r\n- Разработка интерактивных компонентов на React.js.\r\n\r\nТребования: уверенный HTML/CSS, основы JavaScript, базовый опыт работы с React.', '2026-09-25 15:20:15'),
(9, 1, 'Middle Python Developer', '180000.00', 'Python, Django, FastAPI, Docker, PostgreSQL', '4.png', 'вакансия', 2, 'Ищем специалиста для поддержки внутренней инфраструктуры и развития CI/CD процессов.\r\n\r\nЗадачи:\r\n- Написание манифестов для деплоя сервисов в FastAPI/Docker.\r\n- Оптимизация и масштабирование баз данных PostgreSQL.\r\n\r\nТребования: опыт работы с Linux, понимание контейнеризации, базовый синтаксис Python.', '2026-09-26 04:41:11'),
(10, 2, 'Data Analyst', '110000.00', 'Python, SQL, Tableau, PowerBI', '5.png', 'вакансия', 1, 'Анализ продуктовых метрик и проектирование дашбордов.\r\n\r\nОбязанности:\r\n- Написание сложных аналитических запросов к базам данных.\r\n- Построение интерактивных отчетов в Tableau и PowerBI.\r\n- Исследование поведения пользователей.\r\n\r\nТребования: отличные знания SQL, базовый Python (Pandas/NumPy).', '2026-09-26 04:41:11'),
(11, 3, 'QA Automation Engineer', '130000.00', 'Python, PyTest, Selenium, Git', '6.png', 'вакансия', 2, 'Автоматизация тестирования веб-платформы.\r\n\r\nОбязанности:\r\n- Написание автотестов на Python (PyTest, Selenium).\r\n- Нахождение багов и составление баг-репортов в Git.\r\n\r\nТребования: базовые навыки программирования, понимание методологий тестирования, базовый Git.', '2026-09-26 04:41:11'),
(12, 1, 'Стажер Python (Django)', '40000.00', 'Python, Django, HTML/CSS', '04.png', 'стажировка', 4, 'Стажировка с возможностью дальнейшего трудоустройства в штат.\r\n\r\nЗадачи:\r\n- Помощь в разработке backend-модулей на Django.\r\n- Написание простых шаблонов HTML/CSS.\r\n\r\nТребования: минимальный опыт работы с Django, понимание клиент-серверной архитектуры.', '2026-09-26 04:41:11'),
(13, 2, 'DevOps Engineer', '200000.00', 'Linux, Docker, Kubernetes, CI/CD, Ansible', '05.png', 'вакансия', 1, 'Управление серверной инфраструктурой компании.\r\n\r\nОбязанности:\r\n- Администрирование серверов под управлением Linux.\r\n- Контейнеризация приложений (Docker, Kubernetes).\r\n- Настройка процессов автоматического деплоя (CI/CD, Ansible).\r\n\r\nТребования: понимание сетевых протоколов, опыт настройки пайплайнов, системное мышление.', '2026-09-26 04:41:11'),
(14, 1, 'Junior JavaScript Developer', '70000.00', 'JavaScript, React.js, HTML/CSS, TypeScript', '06.png', 'вакансия', 2, 'Ищем начинающего фронтенд-разработчика для работы над корпоративным порталом компании.', '2026-09-26 16:20:41');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `application_statuses`
--
ALTER TABLE `application_statuses`
  ADD PRIMARY KEY (`user_id`,`vacancy_id`),
  ADD KEY `vacancy_id` (`vacancy_id`);

--
-- Индексы таблицы `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `resumes`
--
ALTER TABLE `resumes`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `student_vacancy_actions`
--
ALTER TABLE `student_vacancy_actions`
  ADD PRIMARY KEY (`user_id`,`vacancy_id`),
  ADD KEY `vacancy_id` (`vacancy_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `vacancies`
--
ALTER TABLE `vacancies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `vacancies`
--
ALTER TABLE `vacancies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `application_statuses`
--
ALTER TABLE `application_statuses`
  ADD CONSTRAINT `application_statuses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `application_statuses_ibfk_2` FOREIGN KEY (`vacancy_id`) REFERENCES `vacancies` (`id`);

--
-- Ограничения внешнего ключа таблицы `resumes`
--
ALTER TABLE `resumes`
  ADD CONSTRAINT `resumes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `student_vacancy_actions`
--
ALTER TABLE `student_vacancy_actions`
  ADD CONSTRAINT `student_vacancy_actions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `student_vacancy_actions_ibfk_2` FOREIGN KEY (`vacancy_id`) REFERENCES `vacancies` (`id`);

--
-- Ограничения внешнего ключа таблицы `vacancies`
--
ALTER TABLE `vacancies`
  ADD CONSTRAINT `vacancies_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
