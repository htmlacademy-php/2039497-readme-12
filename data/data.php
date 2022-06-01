<?php
$is_auth = rand(0, 1);
$user_name = 'Евгений'; // укажите здесь ваше имя
// в примере нет постов с типом video
$posts_array_OLD = [
    [
        'header' => 'Цитата',
        'type' => 'quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'name_user' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'header' => 'Игра Престолов',
        'type' => 'text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!
        qwerty qwerty qwerty <script>alert("test")</script> qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty ',
        'name_user' => 'Владик',
        'avatar' => 'userpic.jpg'
    ],
    [
        'header' => 'Наконец, обработал фотки!',
        'type' => 'photo',
        'content' => 'rock-medium.jpg',
        'name_user' => 'Виктор',
        'avatar' => 'userpic-mark.jpg'
    ],
    [
        'header' => 'Моя мечта',
        'type' => 'photo',
        'content' => 'coast-medium.jpg',
        'name_user' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'header' => 'Лучшие курсы',
        'type' => 'link',
        'content' => 'www.htmlacademy.ru',
        'name_user' => 'Владик',
        'avatar' => 'userpic.jpg'
    ]
];
$title = "readme: популярное";
// меняем сырые данные на запросы к БД
$sql_all_post = "SELECT
            `p`.`header`,
            `tc`.`type`,
        CASE
            WHEN `tc`.`type` = 'Цитата'
                THEN `p`.`author_quote`
            WHEN `tc`.`type` = 'Текст'
                THEN `p`.`content_text`
            WHEN `tc`.`type` = 'Картинка'
                THEN `p`.`content_photo`
            WHEN `tc`.`type` = 'Ссылка'
                THEN `p`.`content_link`
            ELSE `p`.`content_video`
        END AS `content`,
            `u`.`login` as `name_user`,
            `u`.`avatar`,
            `tc`.`class_name`
        FROM
            `posts` `p`
            JOIN `users` `u` on `u`.`id` = `p`.`user_id`
            JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
        ORDER BY `count_views` DESC;";
$sql_all_type_content = "SELECT
                            `tc`.`type`,
                            `tc`.`class_name`
                        FROM
                            `type_content` `tc`;";
$result1 = mysqli_query($link, $sql_all_post);
if (!$result1) {
	$error = mysqli_error($link);
	print("Ошибка MySQL: " . $error);
}
$posts_array = mysqli_fetch_all($result1, MYSQLI_ASSOC);
$result2 = mysqli_query($link, $sql_all_type_content);
if (!$result2) {
	$error = mysqli_error($link);
	print("Ошибка MySQL: " . $error);
}
$content_type_array = mysqli_fetch_all($result2, MYSQLI_ASSOC);


