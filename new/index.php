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

		<title><?php // output meta title if there is one
			if(!empty($page->meta_title)) echo $page->meta_title;
			else echo $page->title; 
		?> | MyHRSFC</title>

		<meta name="description" content="<?php outputContent($page->desc,false); ?>">

<?php 
	globalContentBlock('head');
	echo $page->special_head; // output page specific meta
?>
		
	</head>
	
	<body lang="en" ontouchstart="">

		<?php navbar($page->idpages); ?>

		<!-- MAIN -->
		<div id="main">
			<?php globalContentBlock('social'); globalContentBlock('sidewriting'); ?>

			<!-- Content -->
			<div id="content">

<?php

	if(($page->title != "") || ($page->subtitle != "")) { // hide masthead when no title and subtitle, content full width
?>

				<!-- masthead -->
				<div id="masthead">
					<span class="head">
						<?php echo htmlentities($page->title); ?></span>
						<span class="subhead"><?php echo htmlentities($page->subtitle); ?>
					</span>
					<!--<ul class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
						<li typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title">home</a></li>
					</ul>-->
					<?php breadcrumbs($page->idpages); ?>
				</div>
				<!-- ENDS masthead -->
				
				<!-- page content -->
				<div class="page-content <?php if(($page->assoc_councillor!="") || ($page->sidebar!="")) echo 'hasaside'; ?>">

<?php
	} // end non-full width content

	if($page->editor) outputContent($page->body); // use markdown
	else outputContent($page->body,false); // don't use markdown

	if(($page->title != "") || ($page->subtitle != "")) { // show aside when not full width content

		if(($page->assoc_councillor!="") || ($page->sidebar!="")) { // to show sidebar if page owned or sidebar content set
			echo '<aside>';

			if ($page->assoc_councillor != "") outputCouncillor($page->assoc_councillor);
			if ($page->sidebar != "") {
				outputContent($page->sidebar);
			}
			echo '</aside><div class="clearfix"></div>';
		}
?>
				</div>
				<!-- ENDS page content -->
<?php
	} // end non-full width content
?>
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>