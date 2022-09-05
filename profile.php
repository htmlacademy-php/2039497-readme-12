<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$id_user = $_SESSION['id'];
$title = "readme: профиль";
$is_auth = 1;
$user = $_SESSION['user'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = $_POST;

    add_subscription($link, $form, $id_user, $user);
    del_subscription($link, $form, $id_user);
    add_comment($link, $form, $id_user, $errors);
}


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_profile = get_user_profile($link, $_GET['id']);
} else {
    $user_profile = $user;
}

add_like($link, $id_user);
add_repost($link, $id_user);

$count_post_user = get_count_post_user($link, $user_profile['login']);
$count_subscriptions_user = get_count_subscriptions_user($link, $user_profile['login']);
$posts_array = get_posts_user($link, $user_profile['id']);

foreach($posts_array as &$post) {
    $hashtags = get_hashtags($link, $post['id']);
    $comments = get_comments($link, $post['id']);
    $post['hashtags'] = $hashtags ? $hashtags : [];
    $post['comments'] = $comments ? $comments : [];
}

$likes_array = get_likes($link, $user_profile['id']);
$subscribers_array = get_subscribers($link, $user_profile['id']);

$class_main = "page__main--profile";

$main = include_template("main_profile.php", [
    "count_post_user" => $count_post_user,
    "count_subscriptions_user" => $count_subscriptions_user,
    "user_profile" => $user_profile,
    "likes_array" => $likes_array,
    "posts_array" => $posts_array,
    "subscribers_array" => $subscribers_array,
    "errors" => $errors,
    "link" => $link,
    "registration_user_id" => $id_user,
    "user" => $user,
]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user" => $user,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);
