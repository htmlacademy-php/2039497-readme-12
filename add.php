<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data/data_for_auth.php';




$content_type_array = get_all_type_content($link);
$class_main = "page__main--adding-post";
$errors = [];

if (isset($_POST['type-content'])) {

    $type_content = $_POST['type-content'];
    $required = get_required_fields($type_content);              // Обазятельные для заполнения поля
    $prepared_post = array_fill_keys(get_prepared_post($type_content), FILTER_DEFAULT);
    $post = filter_input_array(INPUT_POST, $prepared_post, true); // Данные формы, по сути это и будут обязательные поля, тк по условию обязательны все (только у картинок есть выбор)

    // Правила валидации полей
    $rules = [
        "$type_content-tags" => function($value) {
            return validate_tags($value);
        },
        'userpic-file-photo' => function() {
            return validate_file_photo('userpic-file-photo');
        },
        'photo' => function($value) {
            return validate_link_photo($value);
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

        if ($key === 'photo' && !empty($post[$key])) {
            $image = file_get_contents($post[$key]);

            if (!$image) {
                $errors[$key] = "Файл не удалось загрузить.";
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file_name_tmp = explode("/", $post[$key]);
                $file_name = end($file_name_tmp);
                $path = __DIR__ . '/uploads/';
                file_put_contents($path . $file_name, $image);
                $file_type = finfo_file($finfo, $path . $file_name);

                if (!in_array($file_type, ["image/gif", "image/png", "image/jpeg"])) {
                    $errors[$key] = 'Картинка должна соответствовать форматам: gif, jpeg, png.';
                    unlink($path . $file_name);
                }
            }
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

    // Если ошибок нет, загружаем файлы и записываем данные в бд
    if (!count($errors)) {

        if (array_key_exists('userpic-file-photo', $post)) {

            $field = 'userpic-file-photo';

            $file_name = $_FILES[$field]['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;

            move_uploaded_file($_FILES[$field]['tmp_name'], $file_path . $file_name);
            $post[$field] = $file_url;

        } elseif (isset($post['photo'])) {
            $post['photo'] = '/uploads/' . $file_name;
        }

        $post_field_filter = array_filter($post, function($k) use ($type_content) {
            return $k !== "$type_content-tags";
        }, ARRAY_FILTER_USE_KEY);

        $post_field_filter['id_type_post'] = get_id_type_post($link, $type_content);

        $stmt = db_get_prepare_stmt($link, get_sql_add_post($type_content), $post_field_filter);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $post_id = mysqli_insert_id($link);

            // Тег существует?
            if (isset_hashtag($link, $post["$type_content-tags"])) {

                // Заполняем только связующую таблицу
                $hashtag_id = isset_hashtag($link, $post["$type_content-tags"])['id'];
                $stmt = db_get_prepare_stmt($link, get_sql_add_posts_hashtag(), [$post_id, $hashtag_id]);
                $res = mysqli_stmt_execute($stmt);
                if (!$res) {
                    $error = mysqli_error($link);
                    print("Ошибка MySQL: " . $error);
                    exit();
                }

            } else {
                // Добавляем тег
                foreach(explode(" ", $post["$type_content-tags"]) as $tag) {
                    $stmt = db_get_prepare_stmt($link, get_sql_add_hashtag(), [$tag]);
                    $res = mysqli_stmt_execute($stmt);

                    if ($res) {
                        $hashtag_id = mysqli_insert_id($link); // id добавленного тега
                        // Заполняем связующую таблицу
                        $stmt = db_get_prepare_stmt($link, get_sql_add_posts_hashtag(), [$post_id, $hashtag_id]);
                        $res = mysqli_stmt_execute($stmt);

                        if (!$res) {
                            $error = mysqli_error($link);
                            print("Ошибка MySQL: " . $error);
                            exit();
                        }

                    } else {
                        $error = mysqli_error($link);
                        print("Ошибка MySQL: " . $error);
                        exit();
                    }
                }
            }

            header("Location: post.php?id=" . $post_id);

        } else {
            $error = mysqli_error($link);
            print("Ошибка MySQL: " . $error);
            exit();
        }
    }
}

$main = include_template("main_add.php", [
    "content_type_array" => $content_type_array,
    "errors" => $errors,
]);

/**
* Переменные из подключаемого файла data/data_for_auth.php
* @var $is_auth
* @var $user_name
* @var $title
*/
$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);
