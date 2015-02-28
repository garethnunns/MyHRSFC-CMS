<?php
	require_once 'includes/common.php';

	if((isset($_GET['page'])) || ($_GET['page'] != ""))  $page = $_GET['page'];
	else $page = 'index';

	if(substr($_GET['page'],-5)=='.html') {
		$page = substr($_GET['page'],0,-5); // allow .html extensions
	}

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

			if ($sth->rowCount() == 0) {
				echo "!!! NO 404 PAGE EXISTS !!!";
				exit();
			}
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

		<title><?php // output meta title if there is one, if not use page title
			$meta_title = !empty($page->meta_title) ? $page->meta_title : $page->title; 
			echo $meta_title;
		?> | MyHRSFC</title>

		<meta name="generator" content="CMS created by Gareth Nunns - garethnunns.com">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="canonical" href="http://myhrsfc.co.uk/<?php
			$metaAlias = $page->alias!='index' ? $page->alias : '';
			echo $metaAlias; 
		?>">

		<meta name="description" 
content="<?php // output the first 150 characters of desc (or body if no desc)
	$desc = htmlspecialchars($page->desc ? $page->desc : strip_tags($page->body));
	echo strlen($desc)>150 ? (substr($desc, 0, 150).'...') : $desc;
?>">

<?php
	echo '
	<!--Open Graph -->
	<meta property="og:url" content="http://myhrsfc.co.uk/'.$metaAlias.'">
	<meta property="og:site_name" content="MyHRSFC">
	<meta property="og:title" content="'.$meta_title.' - MyHRSFC">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_GB">
	<meta property="og:description" content="'.(strlen($desc)>200 ? (substr($desc, 0, 197).'...') : $desc).'">
	<meta property="og:image" content="http://myhrsfc.co.uk'.
	($page->social_img ? $page->social_img : currentPhoto()).'">

	<!-- Twitter Cards -->
	<meta name="twitter:card" content="'; // if there is an image, show it larger
	echo empty($page->social_img) ? 'Summary Card' : 'Summary Card with Large Image';
	echo '">
	<meta name="twitter:site" content="@myhrsfc">
	<meta name="twitter:title" content="'.$meta_title.' - MyHRSFC">
	<meta name="twitter:description" content="'.(strlen($desc)>200 ? (substr($desc, 0, 197).'...') : $desc).'">
	<meta name="twitter:image:src" content="http://myhrsfc.co.uk'.
	($page->social_img ? $page->social_img : currentPhoto()).'">

	<!-- Schema/Google+ -->
	<meta itemprop="name" content="'.$meta_title.' - MyHRSFC">
	<meta itemprop="description" content="'.(strlen($desc)>200 ? (substr($desc, 0, 197).'...') : $desc).'">
	<meta itemprop="image" content="http://myhrsfc.co.uk'.
	($page->social_img ? $page->social_img : currentPhoto()).'">

	<!-- global -->
	';

	globalContentBlock('head');

	echo '
	<!-- end global -->

	<!-- page specific -->
	'.$page->special_head.'
	<!-- end page specific -->';
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
						<?php echo $page->title; ?></span>
						<span class="subhead"><?php echo $page->subtitle; ?>
					</span>
					<?php breadcrumbs($page->idpages); ?>
				</div>
				<!-- ENDS masthead -->
				
				<!-- page content -->
				<div class="page-content <?php if(($page->assoc_councillor!="") || ($page->sidebar!="")) echo 'hasaside'; ?>">

<?php
	} // end non-full width content

	outputContent($page->body,($page->editor ? true : false)); // output content using markdown or not

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