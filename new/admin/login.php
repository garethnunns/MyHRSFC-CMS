<?php
	include '/home/a6325779/public_html/admin/secure.php';
	session_start();
	if(isset($_SESSION['user'])) header('Location: /home/a6325779/public_html/admin/index.php');

?><!doctype html>
<html lang="en-gb" class="no-js">

	<!-- HEADER -->
	<head>

		<title>Admin Login | Hills Road Sixth Form College</title>

		<meta name="description" content="Stay up to date and in the loop with the official website from the HRSFC, Hills Road Sixth Form College, Student Council" />

		<?php include 'includes/head.php'; ?>
		
	</head>
	
	<body lang="en">

		<?php include 'includes/header.php'; ?>

		<!-- MAIN -->
		<div id="main">
				
			<?php include 'includes/social.php'; ?>
			
			<!-- Content -->
			<div id="content">
			
				<!-- masthead -->
				<div id="masthead">
					<span class="head">Welcome</span><span class="subhead">New site</span>
					<ul class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
						<li typeof="v:Breadcrumb"><a href="index.html" rel="v:url" property="v:title">home</a> / </li>
						<!--<li typeof="v:Breadcrumb"><a href="bla" rel="v:url" property="v:title">bla</a> / </li>-->
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div id="page-content-full">	        	
					
					<h1>Hello World</h1>

					<form method="POST">
						<p>Email: <input type="email" name="email" placeholder="email@address.com" />
						<p>Password: <input type="password" name="password" placeholder="Password">
					</form>
				
				</div>
				<!-- ENDS page content -->
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php include 'includes/footer.php'; ?>

	</body>
</html>