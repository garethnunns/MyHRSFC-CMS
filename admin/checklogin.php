<?php
	session_start();
	if(!isset($_SESSION['user'])) { // not logged in: go to login page
		header('Location: /admin/login.php?goto=' . urlencode($_SERVER["PHP_SELF"]));
		exit();
	}
	else {
		$user = $_SESSION['user'];
		$sudo = $_SESSION['sudo'];
	}
	
	require_once dirname(__FILE__).'/../includes/common.php';
	require_once dirname(__FILE__).'/functions.php';
?>