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
$id_user = $_SESSION['id'];

$cur_page = $_GET['page'] ?? 1;
$page_items = 9;

if (isset($_GET['type_post']) && $_GET['type_post'] !== "all") {

    $items_count = get_count_popular_posts($link, $_GET['type_post']);
    $pages_count = ceil($items_count / $page_items);
    $offset = ((int)$cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $posts_array = get_popular_posts($link, $page_items, $offset, $_GET['sorted'] ?? "popular", $_GET['type_post']);
    $filter_posts = "?type_post=" . $_GET['type_post'];
} else {

    $items_count = get_count_popular_posts($link);
    $pages_count = ceil($items_count / $page_items);
    $offset = ((int)$cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $filter_posts = "?type_post=all";
    $posts_array = get_popular_posts($link, $page_items, $offset, $_GET['sorted'] ?? "popular");
}

add_like($link, $id_user);

$sorted = "&sorted=popular";
if (isset($_GET['sorted']) && !empty($_GET['sorted'])) {
    $sorted = "&sorted={$_GET['sorted']}";
}

$content_type_array = get_all_type_content($link);
$class_main = "page__main--popular";

$main = include_template("main.php", [
    "posts_array" => $posts_array,
    "content_type_array" => $content_type_array,
    "pages" => $pages,
    "pages_count" => $pages_count,
    "cur_page" => $cur_page,
    "filter_posts" => $filter_posts,
    "sorted" => $sorted
]);

$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user" => $user,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);

