<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';


if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = "Популярный контент";
$is_auth = 1;
$user = $_SESSION['user'];

if (isset($_GET['type_post'])) {
    $posts_array = get_popular_posts($link, $_GET['type_post']);
} else {
    $posts_array = get_popular_posts($link);
}

$content_type_array = get_all_type_content($link);
$class_main = "page__main--popular";

$main = include_template("main.php", ["posts_array" => $posts_array, "content_type_array" => $content_type_array]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user_name" => $user['login'],
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);

