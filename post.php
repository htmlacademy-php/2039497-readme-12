<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';


if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = "Пост";
$is_auth = 1;
$user = $_SESSION['user'];

if (!isset($_GET['id']) || !isset_post($link, $_GET['id'])) {
    header("HTTP/1.1 404 Not Found");
    print("Такой страницы не существует");
    exit();
}

$id = $_GET['id'];
$class_main = "page__main--publication";
$post = get_selected_post($link, $id);
$count_post_user = get_count_post_user($link, $post['name_user']);
$count_subscriptions_user = get_count_subscriptions_user($link, $post['name_user']);
$created_at_user = get_created_at_user($link, $post['name_user']);

$main = include_template("main_details.php", [
                        "post" => $post,
                        "count_post_user" => $count_post_user,
                        "count_subscriptions_user" => $count_subscriptions_user,
                        "created_at_user" => $created_at_user
                        ]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user_name" => $user['login'],
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);

