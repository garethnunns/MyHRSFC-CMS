<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Page Editor | MyHRSFC Admin</title>

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
					<span class="head"><a href="/admin">Admin</a></span>
					<span class="subhead"><a href="pages.php">Manage Pages</a></span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">

					<?php 
						if(isset($_GET['page'])) {
							$reqpage = $_GET['page'];
							echo '<h1>Edit Page</h1>';
						}
						else echo '<h1>Add Page</h1>';
					?>

<?php
	if(isset($_POST['submit'])) { // creating or updating
		$error = false;

		if(!$sudo) {
			$councillor = $user; // normal users only create/update own page
			$specialhead = '';
		}
		else {
			$councillor = $_POST['councillor']; // sudo choose author of page
			$specialhead = $_POST['specialhead'];
		}

		$alias = $_POST['alias']; // may be changed later if special

		$editor = isset($_POST['editor']) ? 1 : 0;


		if(!isset($_POST['page'])) { // creating

			// look up alias in database
			$lookupsth = $dbh->prepare('SELECT idpages
										FROM pages
										WHERE alias = ?');
			$lookupsth->bindValue(1, $alias, PDO::PARAM_STR);
			$lookupsth->execute();

			$count = $lookupsth->rowCount();

			if($count && !isSpecial($alias)) { // already exists
				$error = true;
				echo '<p class="error"><a href="/'.$alias.'">A page with that alias</a> already exists</p>';
			}
			else {
				$sql = "INSERT INTO pages 
				VALUES 
				(null,:alias,:title,:subtitle,:metatitle,:head,:body,:sidebar,:councillor,:desc,:social,:editor,1)";
			}
		}
		else { // updating
			$result = pageExists($_POST['page']);

			if($result) { // check page exists in the db

				if(isSpecial($alias) || isSpecial($result) || !$sudo) { // checks to see if new or old alias is special
					$alias = $result; // alias remains the same and then updates the other fields
				}
				$sql = "UPDATE pages 
						SET	title = :title,
							subtitle = :subtitle,
							body = :body,
							sidebar = :sidebar,	
							alias = :alias,
							assoc_councillor = :councillor,
							meta_title = :metatitle,
							special_head = :head,
							`desc` = :desc,
							editor = :editor,
							social_img = :social
						WHERE idpages = :page";
				if(!$sudo) $sql .= " AND assoc_councillor = :councillor"; // user can only update the page they own
			}
			else $error = true;
		}

		if(!$error) {
			if ( (((!isset($_POST['page']) || ($sudo)) && validAlias('page alias',$alias)) || (isset($_POST['page']) && !$sudo)) &&
				validString('page title',$_POST['title']) &&
				validString('page subtitle',$_POST['subtitle']) &&
				validString('page meta title',$_POST['metatitle']) &&
				validString('page special head',$_POST['specialhead']) &&
				validString('page content',$_POST['body']) &&
				validString('page sidebar',$_POST['sidebar']) &&
				validString('page description',$_POST['desc']) &&
				validString('page social image',$_POST['social_img'])) {

				try {
					$dir = '../img/'.$alias;
					if(!file_exists($dir)) mkdir($dir); // make files folder for that page if needed

					$sth = $dbh->prepare($sql);
					$sth->bindValue(':title',htmlentities($_POST['title']), PDO::PARAM_STR);
					$sth->bindValue(':subtitle',htmlentities($_POST['subtitle']), PDO::PARAM_STR);
					$sth->bindValue(':body',$_POST['body'], PDO::PARAM_STR);
					$sth->bindValue(':sidebar',$_POST['sidebar'], PDO::PARAM_STR);
					$sth->bindValue(':alias',$alias, PDO::PARAM_STR); 
					$sth->bindValue(':metatitle',htmlentities($_POST['metatitle']), PDO::PARAM_STR);
					$sth->bindValue(':desc',htmlentities($_POST['desc']), PDO::PARAM_STR);
					$sth->bindValue(':editor',$editor, PDO::PARAM_INT);
					if(isset($_POST['page'])) $sth->bindValue(':page',$_POST['page'], PDO::PARAM_INT); // updating only
					if(!$councillor) $sth->bindValue(':councillor',null, PDO::PARAM_INT); // 'none' councillor
					else $sth->bindValue(':councillor',$councillor, PDO::PARAM_INT);
					$sth->bindValue(':social',htmlentities($_POST['social_img']), PDO::PARAM_STR);
					$sth->bindValue(':head',$specialhead, PDO::PARAM_STR);
					$sth->execute();

					echo '<p class="success">Page successly ';
					if(isset($_POST['page'])) echo 'updated';
					else echo 'created, create another or <a href="pages.php">view all</a>';
					echo '</p>';
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
	}


	if(isset($reqpage)) {
		try {

			$sql = "SELECT *
				FROM pages
				WHERE idpages = ?";
			if(!$sudo) $sql .= " AND assoc_councillor = $user";
			$sql .= " LIMIT 1";

			$sth = $dbh->prepare($sql);
			$sth->bindValue(1, $reqpage, PDO::PARAM_INT);
			$sth->execute();

			$count = $sth->rowCount();
		
			if ($count) {
				$page = $sth->fetch(PDO::FETCH_OBJ);
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	if((isset($reqpage)) && (!$count)) { // editing and page not found
		echo '<p class="error">This page you requested doesn\'t exist or you don\'t have permission to edit it</p>
			<p>You can see the pages that you can edit in the <a href="pages.php">Pages Manager</a></p>';
	}
	else {
		echo '<form method="post">';

		if(isset($page->idpages)) echo '<input type="hidden" name="page" value="'.$page->idpages.'"/>';
		echo '
		<p><i>The title and subtitle are used in the masthead above (if both are left blank it is not shown)</i></p>
		<p>Title:
		<input type="text" name="title" placeholder="Used in the mast head" value="'.$page->title.'" /></p>

		<p>Subtitle: 
		<input type="text" name="subtitle" placeholder="Used in the mast head" value="'.$page->subtitle.'" /></p>

		<p><b>Content</b>: <i>The main content of the page, using functions and optionally MarkDown</i></p>
		<textarea name="body" placeholder="Main content for the page">'.$page->body.'</textarea>

		<p>Sidebar: <i>If left blank, the sidebar isn\'t displayed</i></p>
		<textarea name="sidebar" placeholder="Content to go in the sidebar">'.$page->sidebar.'</textarea>

		<h3>Advanced</h3>

		<p><input type="checkbox" name="editor" id="editor" value="1" ';
		if (!isset($reqpage) || $page->editor) echo 'checked'; // set by default for creating new pages
		echo '/> <label for="editor">Use MarkDown (recommended)</label>
		<i><a href="https://help.github.com/articles/markdown-basics/">Learn more about MarkDown</a></i></p>';

		if((!isset($reqpage)) || ((isset($sudo)) && !isSpecial($page->alias))) { 
			// adding the page or editing the page as sudo and not a special page
			echo '<p><b>Alias</b>: 
			<input type="text" name="alias" class="short" placeholder="Link to the page" value="'.$page->alias.'" />
			<i>e.g. myhrsfc.co.uk/<u>welcome</u></i></p>';
		}

		echo '<p>Page title: 
		<input type="text" name="metatitle" placeholder="Used in the tab name" value="'.$page->meta_title.'" /><br>
		<i>The short title of the page that is shown in the browser\'s tab, e.g. <u>Home</u> | MyHRSFC<br>
		If it is left blank the title attribute is used</i></p>

		<p>Description:</p>
		<input type="text" name="desc" class="full" placeholder="A summary of the page" value="'.$page->desc.'" /><br>
		<i>Used in search engine summaries of pages and when linked on social media<br>
		If it is left blank, the start of the page is used instead</i></p>

		<p>Social Image</p>
		<input type="text" name="social_img" class="full" 
		placeholder="Picture for the page" value="'.$page->social_img.'" /><br>
		<i>The image for the page when it is linked on social media and 
		<a href="'.currentPhoto().'">the current group photo</a> by default<br>
		The image must be the absolute path on the website, e.g. myhrsfc.co.uk<u>'.currentPhoto().'</u></i></p>';

		if($sudo) {
			echo '<p>Special Head: <i>Content that you want to specifically go in the &lt;head&gt; of this page</i></p>
			<textarea name="specialhead" placeholder="Code to go in the head section of this page" class="small">'.
			$page->special_head.'</textarea>

			<p>Owner: <select name="councillor">
			<option value="0">None</option>';
			councSelect($page->assoc_councillor);
			echo '</select>
			<i>If set, displays the sidebar with details about the councillor</i></p>';
		}

		echo '<p><input type="submit" name="submit" value="';
		if(!isset($reqpage)) echo 'Add';
		else echo 'Update';
		echo ' Page &#187;"" /></p>
		<p><b>Bold attribute names indicate required fields</b></p>
		</form>';
	}
?>
					<aside>
						<h3>MarkDown &dtrif;</h3>
						<div>
							<?php markDownInstructions(); ?>
						</div>

						<h3>Functions &dtrif;</h3>
						<div>
							<p>Just copy these in to insert</p>
<?php
	$functions = $dbh->query("SELECT `name`,`desc` FROM functions ORDER BY name");

	$funccount = $functions->rowCount();

	if($funccount) { // there are functions in the database
		foreach($functions as $function) {
			echo '<h5>{ ',htmlentities($function['name']).' }</h5>';
			echo '<p>';
			line($function['desc']);
			echo '</p>';
		}
	}
?>
						</div>
<?php
	$dir = '../img/'.$page->alias.'/'; // that page's folder

	if(isset($reqpage) && !file_exists($dir)) mkdir($dir); // editing and folder doesn't already exist

	if(isset($_FILES['file']) && $_FILES["file"]['name'] != '') { // upload file into page folder
		if(!move_uploaded_file($_FILES["file"]["tmp_name"], $dir.basename($_FILES["file"]['name']))){
			echo '<p class="error">There was an error uploading the file</p>';
		}
	}
	if(isset($reqpage) && file_exists($dir)) { // editing and folder exists
		echo '<h3>Files &dtrif;</h3>';
		echo '<div>';
		$files = array_diff(scandir($dir), array('..', '.'));
		if(count($files)) { // the page does have some files in the folder
			echo '<ul>';
			foreach ($files as $file) { // output list of files
				$url = '/img/'.$page->alias.'/'.$file;
				echo '<li><a href="'.$url.'" target="_blank">'.$url.'</a></li>';
			}
			echo '</ul>';
		}
		else echo '<p><i>No files stored for this page</i></p>';

		echo '<h5>Add file</h6>
		<form method="post" enctype="multipart/form-data">
		<p><input type="file" name="file" /></p>
		<p><input type="submit" value="Upload &#187;" /></p>';
		echo '</div>';
	} 
?>
					</aside>
					<div class="clearfix"></div>
				</div>
				<!-- ENDS page content -->
			
			</div>
			<!-- ENDS content -->

<script type="text/javascript">
	$('aside h3').on('click touch', function () { // expand pane when heading is clicked
		$('aside div').not($(this).next()).slideUp();
		$(this).next().slideToggle();
	});
</script>
<style>
	aside h3 {
		cursor: pointer;
	}
	aside div {
		display: none;
	}
	aside a {
		word-break: break-all;
	}
</style>	
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>