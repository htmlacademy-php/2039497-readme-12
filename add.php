<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = "Добавление поста";
$is_auth = 1;
$user = $_SESSION['user'];
$user_id = $user['id'];

$content_type_array = get_all_type_content($link);
$class_main = "page__main--adding-post";
$errors = [];

if (isset($_POST['type-content'])) {

    $type_content = $_POST['type-content'];
    $required = get_required_fields($type_content);              // Обазятельные для заполнения поля
    $prepared_post = array_fill_keys(get_prepared_post($type_content), FILTER_DEFAULT);
    // Данные формы, по сути это и будут обязательные поля, тк по условию обязательны все (только у картинок есть выбор)
    // Если какого то поля не будет в форме, то в массиве его значением будет NULL
    $post = filter_input_array(INPUT_POST, $prepared_post, true);

    // Правила валидации полей
    $rules = [
        "$type_content-tags" => function($value) {
            return validate_tags($value);
        },
        'userpic-file-photo' => function() use (&$post) {
            return validate_file_photo('userpic-file-photo', $post);
        },
        'photo' => function($value) use (&$post) {
            return validate_link_photo($value, $post);
        },
        'video' => function($value) {
            return validate_video($value);
        },
    ];

    // Валидируем поля
    foreach ($post as $key => $value) {

        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value) && ($key !== 'userpic-file-photo')) {

            if (strpos($key, "heading")) {
                $errors[$key] = "Заголовок.<br>Это поле должно быть заполнено.";
            } elseif ($key === 'quote') {
                $errors[$key] = "Текст цитаты.<br>Это поле должно быть заполнено.";
            } elseif ($key === 'quote-author') {
                $errors[$key] = "Автор.<br>Это поле должно быть заполнено.";
            } elseif ($key === 'text') {
                $errors[$key] = "Текст поста.<br>Это поле должно быть заполнено.";
            } elseif ($key === 'link') {
                $errors[$key] = "Ссылка.<br>Это поле должно быть заполнено.";
            } elseif ($key === 'video') {
                $errors[$key] = "Ссылка на youtube.<br>Это поле должно быть заполнено.";
            } elseif (strpos($key, "tags")) {
                $errors[$key] = "Теги.<br>Это поле должно быть заполнено.";
            } elseif ($key === 'photo') {
                $errors[$key] = "Ссылка из интернета.<br>Это поле должно быть заполнено.";
            } elseif ($key === 'userpic-file-photo') {
                $errors[$key] = "Выберете фото или укажите ссылку из интернета.";
            } else {
                $errors[$key] = "$key.<br>Это поле должно быть заполнено.";
            }
        }
    }

    $errors = array_filter($errors);

    // Если ошибок нет, переносим файлы изображений в рабочую директорию и записываем данные в бд
    if (!count($errors)) {

        if (array_key_exists('userpic-file-photo', $post)) {
            $file_from = $_FILES['userpic-file-photo']['tmp_name'];
            $file_to = __DIR__ . '/uploads/' . $_FILES['userpic-file-photo']['name'];

            move_uploaded_file($file_from, $file_to);
            $post['userpic-file-photo'] = '/uploads/' . $post['userpic-file-photo'];

        } elseif (array_key_exists('photo', $post)) {
            $file_from = sys_get_temp_dir() . $post['photo'];
            $file_to = __DIR__ . '/uploads/' . $post['photo'];

            rename($file_from, $file_to);
            $post['photo'] = '/uploads/' . $post['photo'];
        }

        // Уберем из массива $post теги, чтобы даллее этот отфильтрованный массив передать в функцию на
        // добавление поста
        $post_field_filter = array_filter($post, function($k) use ($type_content) {
            return $k !== "$type_content-tags";
        }, ARRAY_FILTER_USE_KEY);

        $post_field_filter['id_type_post'] = get_id_type_post($link, $type_content);
        $post_id = add_post($link, $type_content, $post_field_filter, $user_id, $post["$type_content-tags"]);
        $subscribers = get_subscribers($link, $user_id);

        foreach($subscribers as $subscriber) {
            $subject = "Новая публикация от пользователя {$user['login']}";
            $body = "Здравствуйте, {$subscriber['login']}. Пользователь {$user['login']} только что опубликовал новую запись „{$post_field_filter['text-heading']}“. Посмотрите её на странице пользователя: http://example.ru/profile.php?id={$subscriber['id']}";

            send_message($user, $subscriber, $subject, $body);
        }

        header("Location: post.php?id=" . $post_id);
        exit();
    }
}

$main = include_template("main_add.php", [
    "content_type_array" => $content_type_array,
    "errors" => $errors,
]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user" => $user,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);
