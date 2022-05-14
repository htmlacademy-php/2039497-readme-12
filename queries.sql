-- Напишите запросы для добавления информации в БД:

USE `readme`;

-- список типов контента для поста;
INSERT INTO `type_content` (`type`, `class_name`) VALUES ('Цитата', 'post-quote');
INSERT INTO `type_content` (`type`, `class_name`) VALUES ('Текст', 'post-text');
INSERT INTO `type_content` (`type`, `class_name`) VALUES ('Картинка', 'post-photo');
INSERT INTO `type_content` (`type`, `class_name`) VALUES ('Ссылка', 'post-link');
INSERT INTO `type_content` (`type`, `class_name`) VALUES ('Видео', 'post-video');


-- придумайте пару пользователей;
INSERT INTO `users` (`created_at`, `email`, `login`, `password`) VALUES (DATE_ADD(NOW(), INTERVAL -30 DAY), 'ivanov@example.com', 'ivanov', MD5('password123'));
INSERT INTO `users` (`created_at`, `email`, `login`, `password`) VALUES (DATE_ADD(NOW(), INTERVAL -5 DAY), 'petrov@example.com', 'petrov', MD5('password456'));


-- существующий список постов.
INSERT INTO `posts` VALUES (1, DATE_ADD(NOW(), INTERVAL -12 DAY), 'Это первый пост', 'Первый поcт в блоге заслуживает пары комментариев', '', '', '', '', 2, 1, 2);
INSERT INTO `posts` VALUES (2, DATE_ADD(NOW(), INTERVAL -2 DAY), 'Это второй пост', 'После первого поста в блоге идет второй пост...', '', '', '', '', 1, 2, 2);


-- придумайте пару комментариев к разным постам;
INSERT INTO `comments` VALUES (1, DATE_ADD(NOW(), INTERVAL -10 DAY), 'это комментарий пользователя ivanov', 1, 1);
INSERT INTO `comments` VALUES (2, DATE_ADD(NOW(), INTERVAL -1 DAY), 'это комментарий пользователя petrov', 2, 1);



-- Напишите запросы для этих действий:

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT
  *
FROM
  `posts` `p`
  JOIN `users` `u` on `u`.`id` = `p`.`user_id`
  JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
ORDER BY `count_views` DESC;


-- получить список постов для конкретного пользователя;
SELECT
  *
FROM
  `posts` `p`
  JOIN `users` `u` on `u`.`id` = `p`.`user_id`
  JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
WHERE `p`.`user_id` = 1;


-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
SELECT
  *
FROM
  `comments` `c`
  JOIN `posts` `p` on `p`.`id` = `c`.`post_id`
  JOIN `users` `u` on `u`.`id` = `c`.`user_id`
WHERE
  `c`.`post_id` = 1;


-- добавить лайк к посту;
INSERT INTO `likes` (`user_id`, `post_id`) VALUES (2, 1);


-- подписаться на пользователя.
INSERT INTO `subscriptions` (`source_user_id`, `destination_post_id`) VALUES (2, 1);


