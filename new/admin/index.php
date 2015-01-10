<?php
	include '/home/a6325779/public_html/new/admin/checklogin.php';
?><!doctype html>
<html lang="en-gb" class="no-js">

	<!-- HEADER -->
	<head>

		<title>Admin | Hills Road Sixth Form College</title>

		<meta name="description" content="Stay up to date and in the loop with the official website from the HRSFC, Hills Road Sixth Form College, Student Council" />

		<?php include '../includes/head.php'; ?>
		
	</head>
	
	<body lang="en">

		<?php include '../includes/header.php'; ?>

		<!-- MAIN -->
		<div id="main">
				
			<?php include '../includes/social.php'; ?>
			
			<!-- Content -->
			<div id="content">
			
				<!-- masthead -->
				<div id="masthead">
					<span class="head">Welcome</span><span class="subhead">New site</span>
					<ul class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
						<li typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title">home</a> / </li>
						<li typeof="v:Breadcrumb"><a href="/admin" rel="v:url" property="v:title">Admin</a> / </li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div id="page-content-full">	        	
					
					<h1>Logged in</h1>
				
				</div>
				<!-- ENDS page content -->
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php include '../includes/footer.php'; ?>

	</body>
</html>