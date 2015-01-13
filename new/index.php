<?php
	include 'includes/parsedown.php';
	include 'includes/secure.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>MyHRSFC | The Student Council | Hills Road Sixth Form College</title>

		<meta name="description" content="Stay up to date and in the loop with the official website from the HRSFC, Hills Road Sixth Form College, Student Council" />

		<?php include 'includes/head.php'; ?>
		
	</head>
	
	<body lang="en" ontouchstart="">

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
						<li typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title">home</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div id="page-content-full">

					<?php
						$parsedown = new Parsedown();

						try {
							$sql = "SELECT * FROM pages WHERE `alias` = 'atoz'";
							
							foreach ($dbh->query($sql) as $row) {
								echo '<h2>'.htmlentities($row['title']).'</h2>';
								echo $parsedown->text($row['body']);
							}


							$sql = "SELECT `content` FROM functions WHERE `name` LIKE 'AtoZ%'";
							
							foreach ($dbh->query($sql) as $row) {
								eval($row['content']);
							}
						}
						catch (PDOException $e) {
							echo $e->getMessage();
						}
					?>
				
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