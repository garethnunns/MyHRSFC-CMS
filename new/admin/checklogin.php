<?php
	include '/admin/secure.php';

	session_start();
	if(!isset($_SESSION['user'])) header('Location: /admin/login.php');
	else $user = $_SESSION['user'];
?>