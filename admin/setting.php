<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Settings | MyHRSFC Admin</title>

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
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage A to Z</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Settings</h1>
					
<?php

	$year = currentYear();

	if(isset($_POST['add'])) { // adding settings
		if(strlen($_POST['year']) == 4 && $_POST['year'] > $year && is_numeric($_POST['year'])) {
			if($_FILES['photo']['name']) { // there is an image set
				if (validString('settings email',$_POST['email']) 
					&& filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
					&& validString('settings head',$_POST['head'])) {
					// valid email and head
					try {
						$sth = $dbh->prepare("INSERT INTO settings 
							VALUES (:year,:email,:head,'')");
						$sth->bindValue(':year',$_POST['year'], PDO::PARAM_INT);
						$sth->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
						$sth->bindValue(':head',$_POST['head'], PDO::PARAM_STR);
						$sth->execute();

						$added = $sth->rowCount();

						if($added) {
							$year = currentYear();
							echo '<p class="success">Settings successfully added for '.$year.'</p>';
						}
						else {
							echo '<p class="error">There was an internal error adding the settings, please try again</p>';
						}
					}
					catch (PDOException $e) {
						echo $e->getMessage();
					}
				}
			}
			else echo '<p class="error">There must be a group photo, or at least a filler image</p>';
		}
		else echo '<p class="error">The year must be 4 digits and greater than the last year, sorry, please try again</p>';
	}
	elseif(isset($_POST['update'])) { // updating the settings
		if (validString('settings email',$_POST['email']) 
			&& filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
			&& validString('settings head',$_POST['head'])) {
			// valid new email and head
			try {
				$sth = $dbh->prepare("UPDATE settings 
					SET email = :email, specialhead = :head
					WHERE year = :year");
				$sth->bindValue(':year',$year, PDO::PARAM_INT);
				$sth->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
				$sth->bindValue(':head',$_POST['head'], PDO::PARAM_STR);
				$sth->execute();

				$updated = $sth->rowCount();

				if($updated) {
					echo '<p class="success">The settings were successfully updated</p>';
				}
				else {
					echo '<p class="error">There was an internal error updating the settings
					(or no changes were made), please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	$dir = '../img/year/';
	if(!file_exists($dir)) mkdir($dir);

	if(isset($_FILES['photo']) && $_FILES['photo']['name'] && !empty($year)) { // adding a photo and there is a year
		$folder = $dir.$year.'/';
		if(!file_exists($folder)) mkdir($folder);

		$ext = end((explode(".", $_FILES['photo']['name'])));

		if(!in_array(strtolower($ext), array("jpg","jpeg","png","gif"))) { // is image
			echo '<p class="error">The uploaded file was not an image</p>';
		}
		else {
			if(file_exists($folder.'group.'.$ext)) rename($folder.'group.'.$ext,$folder.'group_old.'.$ext); // old pic
			if(!move_uploaded_file($_FILES['photo']['tmp_name'], $folder.'group.'.$ext)){ // bad upload
				echo '<p class="error">There was an error uploading the image</p>';
				// put old one back in
				if(file_exists($folder.'group_old.'.$ext)) rename($folder.'group_old.'.$ext,$folder.'group.'.$ext);
			}
			else {
				if(file_exists($folder.'group_old.'.$ext)) unlink($folder.'group_old.'.$ext); // delete old

				try {
					// update database with location
					$sth = $dbh->prepare("UPDATE settings 
										SET image = :image
										WHERE year = $year");
					$sth->bindValue(':image',substr($folder,2).'group.'.$ext, PDO::PARAM_STR);
					$sth->execute();

					$updated = $sth->rowCount();

					if($updated) echo '<p class="success">The image was successfully uploaded</p>';
					else echo '<p class="error">There was an internal error updating the database</p>';
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
	}

	try { // output current settings
		$sql = "SELECT *
				FROM settings
				WHERE year = $year";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are there settings
			$settings = $dbh->query($sql)->fetch(PDO::FETCH_OBJ);

			echo '<h3>'.$year.'\'s settings</h3>

			<form method="post" enctype="multipart/form-data">

			<h5>Group Photo</h5>
			<img src="'.$settings->image.'" />
			<p>Update: <input type="file" name="photo" /></p>

			<h5>Group Email</h5>
			<input type="text" name="email" class="full" value="'.$settings->email.'" />

			<h5>Code to go in the &lt;head&gt; of every page</h5>
			<textarea name="head">'.htmlentities($settings->specialhead).'</textarea>

			<input type="submit" value="Update settings &#187;" name="update" />

			</form>';
		}
		else echo '<p>There are currently no settings in the database</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					<aside>
						<h3>New settings for this year</h3>
						<form method="post" enctype="multipart/form-data">
							<p>Year: <input type="text" name="year" class="small" placeholder="e.g. 201..." /></p>
							<p>Group email address:<br>
							<input type="email" name="email" class="full" placeholder="e.g. studentcouncil@hillsro...." />
							<p>Special Head:</p>
							<textarea name="head" placeholder="Head all pages"></textarea>
							<p>Group photo: <input type="file" name="photo" />
							<input type="submit" value="Add settings &#187;" name="add" />
						</form>
					</aside>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});

	/*function update(id,input) {
		$.ajax({
			type: "POST",
			url: "editatoz.php",
			data: { id: id, field: input.name, value: $(input).val() }
		}).done(function(msg) {
			if(msg == 'Success') $(input).addClass('success');
			else $('.page-content').prepend(msg); // add errors to the top of the page
		});
	}*/
</script>
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>