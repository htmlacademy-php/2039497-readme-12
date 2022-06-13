<?php
$is_auth = rand(0, 1);
$user_name = 'Евгений';
$title = "readme: популярное";
$posts_array = get_popular_posts($link);

if (isset($_GET['type_post'])) {
    if ($_GET['type_post'] === '1') {
        $posts_array = get_popular_selected_posts($link, 1);
    } elseif ($_GET['type_post'] === '2') {
        $posts_array = get_popular_selected_posts($link, 2);
    } elseif ($_GET['type_post'] === '3') {
        $posts_array = get_popular_selected_posts($link, 3);
    } elseif ($_GET['type_post'] === '4') {
        $posts_array = get_popular_selected_posts($link, 4);
    } elseif ($_GET['type_post'] === '5') {
        $posts_array = get_popular_selected_posts($link, 5);
    } else {
        header("HTTP/1.1 404 Not Found");
        print("Такой страницы не существует");
        exit();
    }
}

$content_type_array = get_all_type_content($link);

