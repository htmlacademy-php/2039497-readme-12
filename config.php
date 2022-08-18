<?php
session_start();

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$name = 'readme';

require 'vendor/autoload.php';

date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect($host, $user, $pass, $name);
if (!$link) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}


