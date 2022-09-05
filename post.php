<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$id_user = $_SESSION['id'];
$title = "readme: публикация";
$is_auth = 1;
$user = $_SESSION['user'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = $_POST;

    add_subscription($link, $form, $id_user, $user);
    del_subscription($link, $form, $id_user);
    add_comment($link, $form, $id_user, $errors);
}

if (!isset($_GET['id']) || !isset_post($link, $_GET['id'])) {
    header("HTTP/1.1 404 Not Found");
    print("Такой страницы не существует");
    exit();
}

add_like($link, $id_user);
add_repost($link, $id_user);

$id = $_GET['id'];
$class_main = "page__main--publication";
$post = get_selected_post($link, $id);
$count_post_user = get_count_post_user($link, $post['name_user']);
$count_subscriptions_user = get_count_subscriptions_user($link, $post['name_user']);
$created_at_user = get_created_at_user($link, $post['name_user']);

$hashtags = get_hashtags($link, $id);
$comments = get_comments($link, $id);
$post['hashtags'] = $hashtags ? $hashtags : [];
$post['comments'] = $comments ? $comments : [];

$main = include_template("main_details.php", [
    "post" => $post,
    "count_post_user" => $count_post_user,
    "count_subscriptions_user" => $count_subscriptions_user,
    "created_at_user" => $created_at_user,
    "registration_user_id" => $id_user,
    "link" => $link,
    "errors" => $errors,
    "user" => $user
]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user" => $user,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);

