<?php
	include '/home/a6325779/public_html/admin/secure.php';
	session_start();
	if(isset($_SESSION['user'])) header('Location: /home/a6325779/public_html/admin/index.php');

?><!doctype html>
<html lang="en-gb" class="no-js">

	<head>
		<title>Admin Login | Hills Road Student Council</title>

		<?php include '/home/a6325779/public_html/includes/head.php'; ?>
		
	</head>
	
	<body lang="en">

		<?php include '/home/a6325779/public_html/includes/header.php'; ?>

		<!-- MAIN -->
		<div id="main">
				
			<?php include '/home/a6325779/public_html/includes/social.php'; ?>
			
			<!-- Content -->
			<div id="content">
			
				<!-- masthead -->
				<div id="masthead">
					<span class="head">Admin</span><span class="subhead">Edit the site</span>
					<ul class="breadcrumbs">
						<l><a href="index.html">home</a> / </li>
						<li><a href="/admin">admin</a> / </li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div id="page-content-full">	        	
					
					<p>bla bla bla</p>
				
				</div>
				<!-- ENDS page content -->
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php include '/home/a6325779/public_html/includes/footer.php'; ?>

	</body>
</html>