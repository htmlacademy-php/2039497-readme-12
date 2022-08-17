<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$id_user = $_SESSION['id'];
$title = "Профиль";
$is_auth = 1;
$user = $_SESSION['user'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $form = $_POST;

    if (isset($form['comment'])) {
        if (empty($form['comment'])) {
            $errors['comment'] = "Это поле обязательно к заполнению.";
        }
        if (empty($errors)) {
            add_comment($link, $form, $id_user);
        }
    } elseif (isset($form['destination-user']) && isset($form['action'])) {
        if ($form['action'] == "sub") {
            add_subscription($link, $form, $id_user);

            $destination_user = get_user_profile($link, $form['destination-user']);

            $dsn = 'smtp://login:passwd@mail.example.ru:465';
            $transport = Transport::fromDsn($dsn);
            $message = new Email();
            $message->to("{$destination_user['email']}");
            $message->from("{$user['email']}");
            $message->subject("У вас новый подписчик");
            $body = "Здравствуйте, {$destination_user['login']}. На вас подписался новый пользователь {$user['login']}. Вот ссылка на его профиль: http://example.ru/profile.php?id={$user['id']}";
            $message->text($body);
            $mailer = new Mailer($transport);
            $mailer->send($message);

        }
        if ($form['action'] == "desub") {
            del_subscription($link, $form, $id_user);
        }
    }

}


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_profile = get_user_profile($link, $_GET['id']);
} else {
    $user_profile = $user;
}

if (isset($_GET['like_post']) && !empty($_GET['like_post'])) {
    add_like($link, $id_user, $_GET['like_post']);
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

if (isset($_GET['repost']) && !empty($_GET['repost'])) {
    add_repost($link, $id_user, $_GET['repost']);
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}


$count_post_user = get_count_post_user($link, $user_profile['login']);
$count_subscriptions_user = get_count_subscriptions_user($link, $user_profile['login']);
$posts_array = array_merge(get_posts_user($link, $user_profile['id']), get_reposts_user($link, $user_profile['id']));

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
