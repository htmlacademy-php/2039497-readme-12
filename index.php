<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'functions.php';


$title = 'readme: авторизация';
$errors = [];

if (isset($_SESSION['user'])) {
    header("Location: feed.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$form = $_POST;
	$required = ['login', 'password'];

	foreach ($required as $field) {
	    if (empty($form[$field])) {
	        $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

	$login = mysqli_real_escape_string($link, $form['login']);
	$sql = "SELECT * FROM `users` WHERE `login` = '$login'";
	$res = mysqli_query($link, $sql);

	$user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

	if (!count($errors)) {
        if ($user) {
            if (password_verify($form['password'], $user['password'])) {
                $_SESSION['user'] = $user;
                $_SESSION['id'] = $user['id'];
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['login'] = 'Такой пользователь не найден';
        }
    }

	if (!count($errors)) {
        header("Location: feed.php");
		exit();
	}
}

$layout_content = include_template('enter_layout.php', [
	'title' => $title,
    'errors' => $errors
]);

print($layout_content);
