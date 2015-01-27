<?php
	include '/home/a6325779/public_html/new/admin/checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Users | Hills Road Sixth Form College</title>

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
					<span class="head">Welcome</span><span class="subhead">New site</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content">	        	
					
					<h1>Manage Users</h1>

					<table rules="all">
						<tr>
							<th>Image</th>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Tutor</th>
							<th>Subjects</th>
							<th>Biography</th>
						</tr>

<?php
	if(isset($_FILES['picture'])) { // image upload
		$toUpload = true;
		$picturesFolder = '../img/profiles/';
		$picture = $_FILES['picture'];
		$pictureExt = end((explode(".", $picture["name"])));


		if(!file_exists($picturesFolder)) { // check to see if folder exists
			if (!mkdir($picturesFolder, 0777, true)) { // tries to create folder
				die('<p class="error">Failed to create the folder to store the photo in</p>');
				$toUpload = false;
			}
		}

		if($picture["size"] > 625000) { // check to see if > 5MB
			echo '<p class="error">Sorry, the uploaded file is larger than 5MB</p>';
			$toUpload = false;
		}

		$allowedType = array("jpg","jpeg","png","gif");
		if(!in_array(strtolower($pictureExt),$allowedType)) { // check to see if allowed file type
			echo '<p class="error">Sorry, the only ';
			foreach ($allowedType as $ext) {
				echo '.'.$ext.', ';
			}
			echo 'are allowed</p>';
			$toUpload = false;
		}

		if($toUpload) {
			$newPath = $picturesFolder.'councillor'.$_POST['id'].'.'.$pictureExt;
			if(move_uploaded_file($picture['tmp_name'], $newPath)) {
				try {
					$picsth = $dbh->prepare("UPDATE councillors 
						SET image = :imgpath
						WHERE idcouncillors = :id");
					$picsth->bindValue(':imgpath',substr($newPath,2), PDO::PARAM_STR);
					$picsth->bindValue(':id',$_POST['id'], PDO::PARAM_INT);
					$picsth->execute();
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
			else echo '<p class="error">Sorry, there was an error uploading your photo, please try again</p>';
		}
	}
	try {
		$sql = "SELECT councillors.*, councillors_roles.rolename 
				FROM councillors, councillors_roles
				WHERE councillors.role = councillors_roles.idroles
					AND councillors.active 
				ORDER BY councillors.active, councillors.name";

		$count = $dbh->query($sql)->rowCount();
		if($count) {
			foreach($dbh->query($sql) as $row) {
				echo '<tr>';

				// image
				echo '<td>';
				if(!empty($row['image'])) {
					echo '<img src="'.$row['image'].'" class="councimg" />';
					echo '<p>Update';
				}
				else echo '<p>Add';
				echo ' image</p><form method="post" enctype="multipart/form-data"><input type="file" name="picture" />';
				echo '<input type="hidden" name="id" value="'.$row['idcouncillors'].'" />';
				echo '<br><input type="submit" value="Upload &#187;" /></form></td>';

				echo '<td><p><input type="text" name="name" value="'.$row['name'].'"/></p><p><input type="text" value="'.$row['shortname'].'" /></p></td>';
				echo '<td><p><input type="text" name="emai" value="'.$row['email'].'"/></p></td>';
				echo '<td><p>'.$row['rolename'].'</p></td>';
				echo '<td><p>'.$row['tutor'].'</p></td>';
				echo '<td><p>'.$row['subjects'].'</p></td>';
				echo '<td><p>'.$row['bio'].'</p></td>';
			}
		}
		else echo '<tr><td colspan="8"><p>No councillors are currently active</p></td></tr>'; // shouldn't occur as you have to be logged on to see this page
	}
	catch (PDOException $e) {
		echo $e->getMessage();
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