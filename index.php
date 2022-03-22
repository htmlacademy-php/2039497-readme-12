<?php
require_once 'helpers.php';
require_once 'data.php';


$main = include_template("main.php", ["posts_array" => $posts_array]);
$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "title" => $title,
    "main" => $main
]);

print($layout_content);
?>
