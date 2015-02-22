<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Admin | MyHRSFC Admin</title>

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
				<div class="page-content hasaside">	        	
					
					<h1>Welcome to the MyHRSFC Admin</h1>
					
					<p><a href="user.php?user=<?php echo $user ?>">Edit profile</a></p>


					<?php if($sudo) { ?>
					<p><a href="user.php">Add user</a></p>

					<p><a href="users.php">Manage users</a></p>

					<p><a href="role.php">Manage roles</a></p>

					<p><a href="tutor.php">Manage tutors</a></p>
					<?php } ?>


					<p><a href="page.php">Add new page</a></p>

					<p><a href="pages.php">Manage pages</a></p>

					<?php if($sudo) { ?>
					<p><a href="nav.php">Manage navigation</a></p>

					<p><a href="link.php">Manage links</a></p>
					<?php } ?>


					<p><a href="atoz.php">Manage A to Z</a></p>

					<p><a href="logout.php">Logout</a></p>

					<aside>
						<?php outputCouncillor($user); ?>
						<p><a href="user.php?user=<?php echo $user ?>">Edit profile</a></p>
					</aside>
				
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