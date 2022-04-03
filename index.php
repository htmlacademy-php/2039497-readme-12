<?php
require_once 'helpers.php';
require_once 'data/data.php';

date_default_timezone_set("Europe/Moscow");

/**
 * Переменная из подключаемого файла data/data.php
 * @var $posts_array
 */
$main = include_template("main.php", ["posts_array" => $posts_array]);

/**
 * Переменные из подключаемого файла data/data.php
 * @var $is_auth
 * @var $user_name
 * @var $title
 */
$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "title" => $title,
    "main" => $main
]);

print($layout_content);
?>
