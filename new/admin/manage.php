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
	if(isset($_FILES['picture'])) uploadProfilePic($_FILES['picture'],$_POST['id']);

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

				echo '<td><p><input type="text" name="name" value="'.$row['name'].'" class="small" /></p><p><input type="text" value="'.$row['shortname'].'" /></p></td>';
				echo '<td><p><input type="email" name="email" value="'.$row['email'].'" class="small" /></p></td>';

				echo '<td><select name="role">';
				roleSelect($row['role']);
				echo '</select></td>';

				echo '<td><select name="role">';
				tutorSelect($row['tutor']);
				echo '</select><p>'.$row['tutor'].'</p></td>';

				echo '<td><p><input type="text" name="subjects" value="'.$row['subjects'].'"/></p></td>';

				//echo '<td><p>'.$row['bio'].'</p></td>';
				echo '<td><textarea name="bio" placeholder="Short biography..">'.$row['bio'].'</textarea></td>';
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