<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';


if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$id_user = $_SESSION['id'];
$title = "Страница результатов поиска";
$is_auth = 1;
$user = $_SESSION['user'];

$search = $_GET['search'] ?? '';
$posts_array = [];

if ($search) {
    if (substr($search, 0, 1) === "#") {
        $posts_array = get_search_posts_by_tag($link, substr($search, 1));
    } else {
        $posts_array = get_search_posts($link, $search);
    }
}

add_like($link, $id_user);
add_repost($link, $id_user);

$class_main = "page__main--search-results";

$main = include_template("main_search.php", ["posts_array" => $posts_array, "search" => $search]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user" => $user,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);
