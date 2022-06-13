<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data/data.php';


date_default_timezone_set("Europe/Moscow");

$class_main = "page__main--popular";

/**
 * Переменные из подключаемого файла data/data.php
 * @var $posts_array
 * @var $content_type_array
 */
$main = include_template("main.php", ["posts_array" => $posts_array, "content_type_array" => $content_type_array]);

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
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);

