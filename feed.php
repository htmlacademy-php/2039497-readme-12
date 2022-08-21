<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';


if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$id_user = $_SESSION['id'];
$title = "readme: моя лента";
$is_auth = 1;
$user = $_SESSION['user'];

if (isset($_GET['type_post'])) {
    $posts_array = get_posts_subscriptions($link, $id_user, $_GET['type_post']);
} else {
    $posts_array = get_posts_subscriptions($link, $id_user);
}

// if (isset($_GET['like_post']) && !empty($_GET['like_post'])) {
    add_like($link, $id_user); // $_GET['like_post']
//     header("Location: {$_SERVER['HTTP_REFERER']}");
//     exit();
// }

// if (isset($_GET['repost']) && !empty($_GET['repost'])) {
    add_repost($link, $id_user); //$_GET['repost']
//     header("Location: {$_SERVER['HTTP_REFERER']}");
//     exit();
// }

$content_type_array = get_all_type_content($link);
$class_main = "page__main--feed";

$main = include_template("main_feed.php", ["posts_array" => $posts_array, "content_type_array" => $content_type_array, "user" => $user]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user" => $user,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);
