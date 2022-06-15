<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data/data_for_auth.php';




if (isset($_GET['type_post'])) {
    $posts_array = get_popular_posts($link, $_GET['type_post']);
} else {
    $posts_array = get_popular_posts($link);
}

$content_type_array = get_all_type_content($link);
$class_main = "page__main--popular";

$main = include_template("main.php", ["posts_array" => $posts_array, "content_type_array" => $content_type_array]);

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

