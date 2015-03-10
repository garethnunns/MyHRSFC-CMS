<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Blog Editor | MyHRSFC Admin</title>

		<?php globalContentBlock('head'); ?>

		<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/ >
		<script src="../js/jquery.datetimepicker.js"></script>
		<script type="text/javascript">
		$(document).ready(function () {
			$('#date').datetimepicker({
				inline:true,
				format:'Y-m-d H:i:s'
			});
		});
		</script>
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
					<span class="subhead"><a href="blogs.php">Manage Blog Posts</a></span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">

					<?php 
						if(isset($_GET['post'])) {
							$reqpage = $_GET['post'];
							echo '<h1>Edit Blog Post</h1>';
						}
						else echo '<h1>Add Blog Post</h1>';
					?>

<?php
	if(isset($_POST['submit'])) { // creating or updating
		$error = false;

		if(!$sudo) {
			$councillor = $user; // normal users only create/update their own posts
		}
		else {
			$councillor = $_POST['councillor']; // sudo only can choose author of posts
		}

		$alias = $_POST['alias']; // may be changed later if adding post to append id

		$editor = isset($_POST['editor']) ? 1 : 0;


		if(!isset($_POST['post'])) { // creating

			// look up the max id of the blog

			/* this solution is more robust but doesn't work on current server
			SELECT `AUTO_INCREMENT` as max
			FROM INFORMATION_SCHEMA.TABLES
			WHERE TABLE_SCHEMA = DATABASE()
			AND TABLE_NAME = 'blog'
			*/

			$max = $dbh->query('SELECT MAX(idblog) AS max FROM blog')->fetch(PDO::FETCH_OBJ)->max;

			$alias .= '-'.($max ? ($max+1) : 1); // add a 1 to the first post and max after that

			$sql = "INSERT INTO blog 
					VALUES (null,:alias,:title,:content,:councillor,:date,null,:desc,:image,1)";

			$image = ''; // image defaults to none
		}
		else { // updating
			// check post exists in the database 
			$lookupsth = $dbh->prepare("SELECT alias 
										FROM blog 
										WHERE idblog = :id". 
										($sudo ? '' : " AND assoc_councillor = $user") ." LIMIT 1");
			$lookupsth->bindValue(':id',$_POST['post'], PDO::PARAM_INT);
			$lookupsth->execute();
			
			$exists = $lookupsth->rowCount();

			if($exists) { // check post exists in the db
				// alias can't be updated
				$alias = $lookupsth->fetch(PDO::FETCH_OBJ)->alias;

				$sql = "UPDATE blog
						SET	title = :title,
							content = :content,	
							alias = :alias,
							assoc_councillor = :councillor,
							updated = :date,
							`desc` = :desc,
							image = :image
						WHERE idblog = :post 
						AND `date` < :date";
				if(!$sudo) $sql .= " AND assoc_councillor = :councillor"; // normies can only edit their posts

				$oldimg = $dbh->prepare("SELECT image FROM blog WHERE idblog = :id LIMIT 1");
				$oldimg->bindValue(':id',$_POST['post'], PDO::PARAM_INT);
				$oldimg->execute();
				$image = $oldimg->fetch(PDO::FETCH_OBJ)->image; // image defaults back to old one
			}
			else $error = true;
		}

		if(!$error) {
			if ((validAlias('blog alias',$alias)) &&
				validString('blog title',$_POST['title']) &&
				validString('blog content',$_POST['content']) &&
				validString('blog description',$_POST['desc'])) {

				try {
					// uploading image
					if($_FILES['photo']['name']) {
						$dir = '../img/blog/'.$alias.'/';
						if(!file_exists($dir)) mkdir($dir,0777,true); // make folder for that alias if needed

						$ext = end((explode(".", $_FILES['photo']['name'])));

						if(!in_array(strtolower($ext), array("jpg","jpeg","png","gif"))) { // is image
							echo '<p class="error">The uploaded file was not an image</p>';
						}
						else {
							$name = basename($_FILES['photo']['name']);
							if(file_exists($dir.$name)) rename($dir.$name,$dir.'old_'.$name);
							if(!move_uploaded_file($_FILES['photo']['tmp_name'], $dir.$name)){ // bad upload
								echo '<p class="error">There was an error uploading the image</p>';
								// put old one back in
								if(file_exists($dir.$name)) rename($dir.'old_'.$name,$dir.$name);
							}
							else {
								if(file_exists($dir.'old_'.$name)) unlink($dir.'old_'.$name); // delete old
							}
							$image = substr($dir.$name,2);
						}
					}

					$sth = $dbh->prepare($sql);
					$sth->bindValue(':alias',$alias, PDO::PARAM_STR); 
					$sth->bindValue(':title',htmlentities($_POST['title']), PDO::PARAM_STR);
					$sth->bindValue(':content',$_POST['content'], PDO::PARAM_STR);
					if(isset($_POST['post'])) $sth->bindValue(':post',$_POST['post'], PDO::PARAM_INT); // updating only
					$sth->bindValue(':councillor',$councillor, PDO::PARAM_INT);
					$sth->bindValue(':date',$_POST['date'], PDO::PARAM_STR);
					$sth->bindValue(':desc',htmlentities($_POST['desc']), PDO::PARAM_STR);
					$sth->bindValue(':image',htmlentities($image), PDO::PARAM_STR);
					$sth->execute();

					if($sth->rowCount()) { // change to the db
						echo '<p class="success">Blog post successly ';
						if(isset($_POST['page'])) echo 'updated';
						else echo 'created, create another or <a href="blogs.php">view all</a>';
						echo '</p>';
					}
					else {
						echo '<p class="error">There was an error '.
						(!isset($_POST['page']) ? 'adding the post' : 'updating the post, or nothing was changed').
						', please try again</p>';
					}
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
				FROM blog
				WHERE idblog = ?";
			if(!$sudo) $sql .= " AND assoc_councillor = $user";
			$sql .= " LIMIT 1";

			$sth = $dbh->prepare($sql);
			$sth->bindValue(1, $reqpage, PDO::PARAM_INT);
			$sth->execute();

			$count = $sth->rowCount();
		
			if($count) {
				$post = $sth->fetch(PDO::FETCH_OBJ);
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	if((isset($reqpage)) && (!$count)) { // editing and post not found
		echo '<p class="error">This post you requested doesn\'t exist or you don\'t have permission to edit it</p>
			<p>You can see the posts that you can edit <a href="blogs.php">here</a></p>';
	}
	else {
		echo '<form method="post" enctype="multipart/form-data">';

		if(isset($post->idblog)) echo '<input type="hidden" name="post" value="'.$post->idblog.'"/>';
		echo '
		<p><b>Title</b>:
		<input type="text" name="title" placeholder="Title of the blog post" value="'.$post->title.'" /></p>';

		if(!isset($_GET['post'])) echo '<p><b>Link:</b> myhrsfc.co.uk/blog/
		<input type="text" name="alias" placeholder="Link to post" value="'.$post->alias.'" /></p>';

		echo' <p><b>Content</b>:</p>
		<textarea name="content" placeholder="Main content for the post">'.$post->content.'</textarea>

		<p>Description:</p>
		<input type="text" name="desc" class="full" placeholder="A summary of the page" value="'.$post->desc.'" /><br>
		<i>Used in search engine summaries of pages and when linked on social media<br>
		If it is left blank, the start of the post is used instead</i></p>

		<p>Social Image: ';
		echo $post->image ? '<img src="'.$post->image.'"/>Update' : 'Add';
		echo ' image... <input type="file" name="photo" /><br>
		<i>The image for the page when it is linked on social media and 
		<a href="'.currentPhoto().'">the current group photo</a> by default<br>
		The image must be the absolute path on the website, e.g. myhrsfc.co.uk<u>'.currentPhoto().'</u></i></p>';

		if($sudo) {
			echo '<p>Author: <select name="councillor">
			<option value="0">Whole Council</option>';
			councSelect($post->assoc_councillor);
			echo '</select></p>';
		}

		echo '<p><b>'.($post->date ? 'Update' : 'Post').' date:</b></p>
		<input name="date" id="date" type="text" value="'.date('Y-m-d H:i:s').'" />
		<p><i>Defaults to the current time and date';
		if($post->date) echo '<br>Must be later than the original date of the post';
		echo '</i></p>

		<p><input type="submit" name="submit" value="';
		if(!isset($reqpage)) echo 'Add';
		else echo 'Update';
		echo ' Post &#187;"" /></p>
		<p><b>Bold attribute names indicate required fields</b></p>
		</form>';
	}
?>
					<aside>
						<h3>MarkDown</h3>
						<div>
							<?php markDownInstructions(); ?>
						</div>
					</aside>
					<div class="clearfix"></div>
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