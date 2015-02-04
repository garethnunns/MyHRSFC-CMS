<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Admin | Hills Road Sixth Form College</title>

		<?php globalContentBlock('head'); ?>
		
	</head>
	
	<body lang="en" ontouchstart="">

		<?php navbar(); ?>

		<!-- MAIN -->
		<div id="main">
				
			<?php globalContentBlock('social'); globalContentBlock('sidewriting'); ?>
			
			<!-- Content -->
			<div id="content">
			
				<!-- masthead -->
				<div id="masthead">
					<span class="head">MyHRSFC Admin</span><span class="subhead">Edit the site</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content">	        	
					
					<h1>Welcome to the MyHRSFC Admin</h1>

					<p><a href="users.php">Manage all users</a></p>

					<p><a href="page.php">Add new page</a></p>

					<p><a href="pages.php">Manage pages</a></p>

					<p><a href="logout.php">Logout</a></p>
				
				</div>
				<!-- ENDS page content -->
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>