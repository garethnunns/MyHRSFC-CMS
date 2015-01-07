<?php
	if(!isset($common_included_flag)) require_once '/home/a6325779/public_html/includes/common.php';
	if(!$mysqlconnected) mysqlConnect(); // they used this on all the other pages so why not

	$imagename = mysql_real_escape_string($_GET['imagename']);
	$col = mysql_real_escape_string($_GET['col']);
	$data = mysql_real_escape_string($_GET['data']);

	mysql_query("UPDATE councillors SET $col='$data' WHERE imagename='$imagename'");
?>