
CREATE DATABASE `readme`;
USE `readme`;


-- Представляет зарегистрированного пользователя.
CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL,                       -- дата и время, когда этот пользователь завёл аккаунт
    `email` VARCHAR(128) NOT NULL UNIQUE,
    `login` VARCHAR(64) NOT NULL UNIQUE,
    `password` CHAR(64) NOT NULL,                         -- хэшированный пароль пользователя
    `avatar` TEXT                                         -- ссылка на загруженный аватар пользователя
);


/*
Тип контента.
Один из пяти предопределенных типов контента.
*/
CREATE TABLE `type_content` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(128) NOT NULL UNIQUE,                     -- название (Текст, Цитата, Картинка, Видео, Ссылка)
    `class_name` VARCHAR(128) NOT NULL UNIQUE                -- имя класса для иконки (photo, video, text, quote, link)
);


/*
Пост.
Состоит из заголовка и содержимого. Набор полей, которые будут заполнены, зависит от выбранного типа.

Связи:
- автор: пользователь, создавший пост;
- тип контента: тип контента, к которому относится пост;
- хештеги: связь вида «многие-ко-многим» с сущностью «хештег».
*/
CREATE TABLE `posts` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL,                            -- дата создания: дата и время, когда этот пост был создан пользователем
    `header` TEXT,                                             -- заголовок: задаётся пользователем
    `content_text` TEXT,                                       -- текстовое содержимое: задаётся пользователем
    `author_quote` TEXT,                                       -- автор цитаты: задаётся пользователем
    `content_photo` TEXT,                                      -- изображение: ссылка на сохранённый файл изображения
    `content_video` TEXT,                                      -- видео: ссылка на видео с youtube
    `content_link` TEXT,                                       -- ссылка: ссылка на сайт, задаётся пользователем
    `count_views` INT NOT NULL DEFAULT 0,                      -- число просмотров
    `user_id` INT NOT NULL,
    `type_content_id` INT NOT NULL,
    FOREIGN KEY `user_index` (`user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY `type_content_index` (`type_content_id`) REFERENCES `type_content` (`id`),
    FULLTEXT `fulltext_index` (`header`, `content_text`, `author_quote`)   -- ключ для полнотекстового поиска
);


/*
Хештег.
Один из используемых хештегов на сайте. Сущность состоит только из названия хештега.
*/
CREATE TABLE `hashtags` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `hashtag` VARCHAR(255) UNIQUE,
    INDEX `hashtag_index` (`hashtag`)
);


-- Cвязь вида «многие-ко-многим» с сущностью «хештег».
CREATE TABLE `posts_hashtag` (
    `post_id` INT NOT NULL,
    `hashtag_id` INT NOT NULL,
    FOREIGN KEY `post_index` (`post_id`) REFERENCES `posts` (`id`),
    FOREIGN KEY `hashtag_index` (`hashtag_id`) REFERENCES `hashtags` (`id`),
    PRIMARY KEY (`post_id`, `hashtag_id`)
);


/*
Комментарий.
Текстовый комментарий, оставленный к одному из постов.
Связи:
- автор: пользователь, создавший пост;
- пост: пост, к которому добавлен комментарий.
*/
CREATE TABLE `comments` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL,                           -- дата создания: дата и время создания комментария
    `comment` TEXT NOT NULL,                                  -- содержимое: задается пользователем
    `user_id` INT NOT NULL,
    `post_id` INT NOT NULL,
    FOREIGN KEY `user_index` (`user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY `post_index` (`post_id`) REFERENCES `posts` (`id`)
);


/*
Лайк.
Эта сущность состоит только из связей и не имеет собственных полей.

Связи:
- пользователь: кто оставил этот лайк;
- пост: какой пост лайкнули.
*/
CREATE TABLE `likes` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL,
    `user_id` INT NOT NULL,
    `post_id` INT NOT NULL,
    FOREIGN KEY `user_index` (`user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY `post_index` (`post_id`) REFERENCES `posts` (`id`)
);


/*
Подписка.
Эта сущность состоит только из связей и не имеет собственных полей.
Сущность создается, когда пользователь подписывается на другого пользователя.

Связи:
- автор: пользователь, который подписался;
- подписка: пользователь, на которого подписались.
*/
CREATE TABLE `subscriptions` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `source_user_id` INT NOT NULL,
    `destination_user_id` INT NOT NULL,
    FOREIGN KEY `source_user_index` (`source_user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY `destination_user_index` (`destination_user_id`) REFERENCES `users` (`id`)
);


/*
Сообщение.
Одно сообщение из внутренней переписки пользователей на сайте.

Связи:
- отправитель: пользователь, отправивший сообщение;
- получатель: пользователь, которому отправили сообщение.
*/
CREATE TABLE `messages` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL,                        -- дата создания: дата и время, когда это сообщение написали
    `message` TEXT NOT NULL,                               -- содержимое: задаётся пользователем
    `sender_user_id` INT NOT NULL,
    `receiver_user_id` INT NOT NULL,
    FOREIGN KEY `sender_user_index` (`sender_user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY `receiver_user_index` (`receiver_user_id`) REFERENCES `users` (`id`)
);


/*
Роли пользователей.
Сайт могут использовать только зарегистрированные пользователи.
Анонимный пользователь всегда видит только приветственную страницу, где предлагается завести аккаунт или войти на сайт.
*/
CREATE TABLE `roles` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `role` VARCHAR(64) NOT NULL UNIQUE
);


CREATE TABLE `reposts` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL,
    `user_id_old` INT NOT NULL,
    `post_id_old` INT NOT NULL,
    `user_id_new` INT NOT NULL,
    `post_id_new` INT NOT NULL,
    FOREIGN KEY `user_index_old` (`user_id_old`) REFERENCES `users` (`id`),
    FOREIGN KEY `post_index_old` (`post_id_old`) REFERENCES `posts` (`id`),
    FOREIGN KEY `user_index_new` (`user_id_new`) REFERENCES `users` (`id`),
    FOREIGN KEY `post_index_new` (`post_id_new`) REFERENCES `posts` (`id`)
);


-- список типов контента для поста;
INSERT INTO `type_content` (`type`, `class_name`) VALUES ('Цитата', 'quote'),
  ('Текст', 'text'),
  ('Картинка', 'photo'),
  ('Ссылка', 'link'),
  ('Видео', 'video');
