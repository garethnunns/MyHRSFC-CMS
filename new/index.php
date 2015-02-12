<?php
	require_once 'includes/common.php';

	if((isset($_GET['page'])) || ($_GET['page'] != ""))  $page = $_GET['page'];
	else $page = 'index';

	try {
		$sth = $dbh->prepare('SELECT *
			FROM pages
			WHERE alias = ? AND active LIMIT 1');
		$sth->bindValue(1, $page, PDO::PARAM_STR);
		$sth->execute();

		$count = $sth->rowCount();
		
		if ($count) {
			$page = $sth->fetch(PDO::FETCH_OBJ);
		}
		else {
			$sql = 'SELECT *
				FROM pages
				WHERE alias = "404" LIMIT 1';

			$sth = $dbh->query($sql);

			if ($sth->rowCount() == 0) echo "!!! NO 404 PAGE EXISTS !!!";
			else $page = $sth->fetch(PDO::FETCH_OBJ);
		}
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title><?php 
			if(!empty($page->meta_title)) echo $page->meta_title;
			else echo $page->title; 
		?> | MyHRSFC</title>

		<meta name="description" content="<?php outputContent($page->desc,false); ?>">

<?php 
	globalContentBlock('head');
	echo $page->special_head;
?>
		
	</head>
	
	<body lang="en" ontouchstart="">

		<?php navbar(); ?>

		<!-- MAIN -->
		<div id="main">
			<?php globalContentBlock('social'); globalContentBlock('sidewriting'); ?>

			<!-- Content -->
			<div id="content">

<?php

	if(($page->title != "") || ($page->sidebar != "")) {
?>

				<!-- masthead -->
				<div id="masthead">
					<span class="head"><?php echo $page->title; ?></span><span class="subhead"><?php echo $page->subtitle; ?></span>
					<ul class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
						<li typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title">home</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				<!-- page content -->
				<div class="page-content <?php if(($page->assoc_councillor!="") || ($page->sidebar!="")) echo 'hasaside'; ?>">

<?php
	}

	if($page->editor) outputContent($page->body); 
	else outputContent($page->body,false);

	if(($page->assoc_councillor!="") || ($page->sidebar!="")) {
		echo '<aside>';

		if ($page->assoc_councillor != "") outputCouncillor($page->assoc_councillor);
		if ($page->sidebar != "") {
			outputContent($page->sidebar);
		}
		echo '</aside><div class="clearfix"></div>';
	}

	if(($page->title != "") || ($page->sidebar != "")) {
?>
				</div>
				<!-- ENDS page content -->
<?php
	}
?>
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>