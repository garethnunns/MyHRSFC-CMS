<?php
	include '/home/a6325779/public_html/new/admin/checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Page Editor | Hills Road Sixth Form College</title>

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
				<div class="page-content">

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

		if(!$sudo) $councillor = $user; // normal users only create/update own page
		else $councillor = $_POST['councillor']; // sudo choose author of page

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

			if($count) { // already exists
				$error = true;
				echo '<p class="error"><a href="/'.$alias.'">A page with that alias</a> already exists</p>';
			}
			else {
				$sql = "INSERT INTO pages (title,subtitle,body,sidebar,alias,meta_title,`desc`,assoc_councillor,editor,active)
						VALUES (:title,:subtitle,:body,:sidebar,:alias,:metatitle,:desc,:councillor,:editor,'1')";
			}
		}
		else { // updating

			// look up page in database
			$lookupsth = $dbh->prepare('SELECT alias
										FROM pages
										WHERE idpages = ?');
			$lookupsth->bindValue(1, $_POST['page'], PDO::PARAM_INT);
			$lookupsth->execute();

			$count = $lookupsth->rowCount();

			if($count) {
				$result = $lookupsth->fetch(PDO::FETCH_OBJ);

				if(isSpecial($result->alias)) { // checks to see if page is special
					$alias = $result->alias; // alias remains the same then update the other fields
				}
				$sql = "UPDATE pages 
						SET	title = :title,
							subtitle = :subtitle,
							body = :body,
							sidebar = :sidebar,";
				if($sudo) { // only sudo can change alias and owner
					$sql.="	alias = :alias,
							assoc_councillor = :councillor,";
				}
				$sql .= "	meta_title = :metatitle,
							`desc` = :desc,
							editor = :editor
						WHERE idpages = :page";
				if(!$sudo) $sql .= " AND assoc_councillor = :councillor";
			}
			else {
				$error = true;
				echo '<p class="error">The page you are trying to edit does not exist in the database</p>';
			}
		}

		if(!$error) {
			try {
				$sth = $dbh->prepare($sql);
				$sth->bindValue(':title',$_POST['title'], PDO::PARAM_STR);
				$sth->bindValue(':subtitle',$_POST['subtitle'], PDO::PARAM_STR);
				$sth->bindValue(':body',$_POST['body'], PDO::PARAM_STR);
				$sth->bindValue(':sidebar',$_POST['sidebar'], PDO::PARAM_STR);
				if(!isset($_POST['page']) || ($sudo)) { // if adding the page or editing the page as sudo
					$sth->bindValue(':alias',$alias, PDO::PARAM_STR); 
				}
				$sth->bindValue(':metatitle',$_POST['metatitle'], PDO::PARAM_STR);
				$sth->bindValue(':desc',$_POST['desc'], PDO::PARAM_STR);
				$sth->bindValue(':editor',$editor, PDO::PARAM_INT);
				if(isset($_POST['page'])) $sth->bindValue(':page',$_POST['page'], PDO::PARAM_INT); // updating only
				if(!$councillor) $sth->bindValue(':councillor',null, PDO::PARAM_INT); // 'none' councillor
				else $sth->bindValue(':councillor',$councillor, PDO::PARAM_INT);
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
		echo '<p>Title: <input type="text" name="title" placeholder="Used in the mast head" value="'.$page->title.'" /></p>';

		echo '<p>Subtitle: <input type="text" name="subtitle" placeholder="Used in the mast head" value="'.$page->subtitle.'" /></p>';

		echo '<p>Content:</p><textarea name="body" placeholder="Main content for the page">'.$page->body.'</textarea>';

		echo '<p>Sidebar:</p><textarea name="sidebar" placeholder="Content to go in the sidebar">'.$page->sidebar.'</textarea>';

		echo '<h3>Advanced</h3>';

		if((!isset($reqpage)) || ((isset($sudo)) && !isSpecial($page->alias))) { 
			// adding the page or editing the page as sudo and not a special page
			echo '<p>Alias: <input type="text" name="alias" class="short" placeholder="The link to the page" value="'.$page->alias.'" /></p>';
		}

		echo '<p>Page title: <input type="text" name="metatitle" placeholder="Used in the tab name" value="'.$page->meta_title.'" /></p>';

		echo '<p>Description: <input type="text" name="desc" placeholder="Used in the mast head" value="'.$page->desc.'" /></p>';

		echo '<p><input type="checkbox" name="editor" id="editor" value="1" ';
		if (!isset($reqpage) || $page->editor) echo 'checked'; // set by default
		echo '/> <label for="editor">Use Markdown (recommended)</label>';

		if($sudo) {
			echo '<p>Owner: <select name="councillor">
			<option value="0">None</option>';
			councSelect($page->assoc_councillor);
			echo '</select></p>';
		}

		echo '<p><input type="submit" name="submit" value="';
		if(!isset($reqpage)) echo 'Add';
		else echo 'Update';
		echo ' Page &#187;"" /></p>';
		echo '</form>';
	}
?>
					</table>
				
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