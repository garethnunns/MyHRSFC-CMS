<?php
	include '../includes/secure.php';


	session_start();
	if(!isset($_SESSION['user'])) header('Location: /admin/login.php?goto=' . urlencode($_SERVER["PHP_SELF"]));
	else $user = $_SESSION['user'];
?>