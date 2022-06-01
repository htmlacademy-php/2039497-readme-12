<?php
	$host = '127.0.0.1'; 
	$user = 'root';      
	$pass = '';          
	$name = 'readme';

	$link = mysqli_connect($host, $user, $pass, $name);
	if ($link == false) {
		print("Ошибка подключения: " . mysqli_connect_error());
	}
?>