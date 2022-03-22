<?php
$is_auth = rand(0, 1);
$user_name = 'Евгений'; // укажите здесь ваше имя
// в примере нет постов с типом post-video
$posts_array = [
    [
        'header' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'name_user' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'header' => 'Игра Престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty qwerty
        qwerty qwerty qwerty qwerty qwerty qwerty qwerty ',
        'name_user' => 'Владик',
        'avatar' => 'userpic.jpg'
    ],
    [
        'header' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'name_user' => 'Виктор',
        'avatar' => 'userpic-mark.jpg'
    ],
    [
        'header' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'name_user' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'header' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'name_user' => 'Владик',
        'avatar' => 'userpic.jpg'
    ]
];
$title = "readme: популярное";
