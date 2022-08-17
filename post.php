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
$title = "readme: публикация";
$is_auth = 1;
$user = $_SESSION['user'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $form = $_POST;

    if (isset($form['destination-user']) && isset($form['action'])) {
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
    } elseif (isset($form['comment'])) {
        if (empty($form['comment'])) {
            $errors[$form['post_id']]['comment'] = "Это поле обязательно к заполнению.";
            // print_r($errors);exit;
        }
        if (empty($errors)) {
            add_comment($link, $form, $id_user);
        }
    }
}

if (!isset($_GET['id']) || !isset_post($link, $_GET['id'])) {
    header("HTTP/1.1 404 Not Found");
    print("Такой страницы не существует");
    exit();
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

