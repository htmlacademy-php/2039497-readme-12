-- Напишите запросы для добавления информации в БД:

USE `readme`;

-- список типов контента для поста;
INSERT INTO `type_content` (`type`, `class_name`) VALUES ('Цитата', 'quote'),
  ('Текст', 'text'),
  ('Картинка', 'photo'),
  ('Ссылка', 'link'),
  ('Видео', 'video');


-- придумайте пару пользователей;
INSERT INTO `users` (`created_at`, `email`, `login`, `password`, `avatar`) VALUES (DATE_ADD(NOW(), INTERVAL -30 DAY), 'ivanov@example.com', 'ivanov', MD5('password123'), 'userpic.jpg');
INSERT INTO `users` (`created_at`, `email`, `login`, `password`, `avatar`) VALUES (DATE_ADD(NOW(), INTERVAL -5 DAY), 'petrov@example.com', 'petrov', MD5('password456'), 'userpic.jpg');


-- существующий список постов.
INSERT INTO `posts` VALUES (1, DATE_ADD(NOW(), INTERVAL -12 DAY), 'Это первый пост', 'Первый поcт в блоге заслуживает пары комментариев', '', '', '', '', 2, 1, 2);
INSERT INTO `posts` VALUES (2, DATE_ADD(NOW(), INTERVAL -2 DAY), 'Это второй пост', 'После первого поста в блоге идет второй пост...', '', '', '', '', 1, 2, 2);
INSERT INTO `posts` VALUES (3, DATE_ADD(NOW(), INTERVAL -2 DAY), 'Это ТРЕТИЙ пост', 'Какая-то цитата', '', '', '', '', 1, 2, 1);


-- придумайте пару комментариев к разным постам;
INSERT INTO `comments` VALUES (1, DATE_ADD(NOW(), INTERVAL -10 DAY), 'это комментарий пользователя ivanov', 1, 1);
INSERT INTO `comments` VALUES (2, DATE_ADD(NOW(), INTERVAL -1 DAY), 'это комментарий пользователя petrov', 2, 1);



-- Напишите запросы для этих действий:

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT
  `p`.`header`,
  `u`.`login`,
  `tc`.`type`
FROM
  `posts` `p`
  JOIN `users` `u` on `u`.`id` = `p`.`user_id`
  JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
ORDER BY `count_views` DESC;


-- получить список постов для конкретного пользователя;
SELECT
  `p`.`header`,
  `u`.`login`,
  `tc`.`type`
FROM
  `posts` `p`
  JOIN `users` `u` on `u`.`id` = `p`.`user_id`
  JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
WHERE `p`.`user_id` = 1;


-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
SELECT
  `c`.`comment`,
  `u`.`login`
FROM
  `comments` `c`
  JOIN `posts` `p` on `p`.`id` = `c`.`post_id`
  JOIN `users` `u` on `u`.`id` = `c`.`user_id`
WHERE
  `c`.`post_id` = 1;


-- добавить лайк к посту;
INSERT INTO `likes` (`user_id`, `post_id`, `created_at`) VALUES (2, 1, NOW());


-- подписаться на пользователя.
INSERT INTO `subscriptions` (`source_user_id`, `destination_user_id`) VALUES (2, 1);


-- INSERT INTO `reposts` (`id`, `created_at`, `user_id`, `post_id`) VALUES (1, '2022-08-15 17:59:47', 1, 1);
