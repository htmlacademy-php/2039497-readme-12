<?php

require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';


$is_auth = 0;
$title = "readme: регистрация";
$class_main = "page__main--registration";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['email', 'password', 'login', 'password-repeat'];
    $form = $_POST;

    foreach ($required as $field) {
        if (empty($form[$field])) {
            if (strpos($field, "email")) {
                $errors[$field] = "Электронная почта.<br>Это поле должно быть заполнено.";
            } elseif ($field === 'password') {
                $errors[$field] = "Пароль.<br>Это поле должно быть заполнено.";
            } elseif ($field === 'login') {
                $errors[$field] = "Логин.<br>Это поле должно быть заполнено.";
            } elseif ($field === 'password-repeat') {
                $errors[$field] = "Повтор пароля.<br>Это поле должно быть заполнено.";
            }
        }
    }

    if (filter_var($form['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Некорретный email';
    }

    $errors['userpic-file'] = validate_avatar('userpic-file');
    $errors = array_filter($errors);

    if (empty($errors)) {

        if (!empty($_FILES['userpic-file']['name'])) {
            $file_from = $_FILES['userpic-file']['tmp_name'];
            $file_to = __DIR__ . '/uploads/' . $_FILES['userpic-file']['name'];
            move_uploaded_file($file_from, $file_to);
            $form['userpic-file'] = $_FILES['userpic-file']['name'];
        }

        if (add_user($link, $errors, $form)) {
            header("Location: /");
            exit();
        }
    }

}

$main = include_template("main_reg.php", [
    "errors" => $errors,
]);


$layout_content = include_template("layout.php", [
    "is_auth" => $is_auth,
    "title" => $title,
    "main" => $main,
    "class_main" => $class_main
]);

print($layout_content);
