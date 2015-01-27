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

					<h3>Add role</h3>
					<form method="POST">
						<p>Name of the role: <input type="text" name="role" placeholder="e.g. Chair" /> 
						<input type="submit" value="Add role &#187;"></p>
					</form>

					<h3>Add tutor</h3>
					<form method="POST">
						<p>Initials: <input type="text" name="initials" class="small" placeholder="e.g. ADC" /> 
						Tutor Name: <input type="text" name="tutor" placeholder="e.g. Mr Cumming" /> 
						<input type="submit" value="Add Tutor &#187;"></p>
					</form>

					<table rules="all" class="responsive">
						<tr class="headings">
							<th class="image">Image</th>
							<th colspan="2">Personal</th>
							<th>Tutor &amp; Subjects</th>
							<th>Biography</th>
						</tr>

<?php
	if(isset($_FILES['picture'])) uploadProfilePic($_FILES['picture'],$_POST['id']);

	if(isset($_POST['initials']) && isset($_POST['tutor'])) {
		try {
			$sth = $dbh->prepare("INSERT INTO tutors(initials,name) 
				VALUES (:initials,:name)");
			$sth->bindValue(':initials',$_POST['initials'], PDO::PARAM_STR);
			$sth->bindValue(':name',$_POST['tutor'], PDO::PARAM_STR);
			$sth->execute();
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	try {

		$sql = "SELECT *
				FROM councillors
				WHERE active 
				ORDER BY councillors.active, councillors.name";

		$count = $dbh->query($sql)->rowCount();
		if($count) {
			foreach($dbh->query($sql) as $row) {
				echo '<tr>';

				// image
				echo '<td>';
				if(!empty($row['image'])) {
					echo '<img src="'.$row['image'].'" class="thumb" />
					<h6>Update';
				}
				else echo '<h6>Add';
				echo ' image</h6><form method="post" enctype="multipart/form-data"><input type="file" name="picture" />
				<input type="hidden" name="id" value="'.$row['idcouncillors'].'" />
				<input type="submit" value="Upload &#187;" /></form></td>';

				echo '<td><h6>Name</h6>
				<p><input type="text" name="name" value="'.$row['name'].'" class="small" 
				onblur="update('.$row['idcouncillors'].',\'name\',this)" /></p>

				<h6>Short name</h6>
				<p><input type="text" value="'.$row['shortname'].'"class="small" 
				onblur="update('.$row['idcouncillors'].',\'shortname\',this)" /></p>
				</td>';

				echo '<td><h6>Email</h6>
				<p><input type="email" name="email" value="'.$row['email'].'" 
				onblur="update('.$row['idcouncillors'].',\'email\',this)" /></p>
				<h6>Role</h6><p><select name="role" 
				onchange="update('.$row['idcouncillors'].',\'role\',this)" >';
				roleSelect($row['role']);
				echo '</select></p></td>';

				echo '<td>
				<h6>Tutor</h6>
				<p><select name="role" 
				onchange="update('.$row['idcouncillors'].',\'bio\',this)" >';
				tutorSelect($row['tutor']);
				echo '</select></p>';
				echo '<h6>Subjects</h6>
				<textarea name="subjects" class="small" 
				onblur="update('.$row['idcouncillors'].',\'subjects\',this)" >'.$row['subjects'].'</textarea></td>';

				//echo '<td><p>'.$row['bio'].'</p></td>';
				echo '<td><textarea name="bio" placeholder="Short biography.." 
				onblur="update('.$row['idcouncillors'].',\'bio\',this)" >'.$row['bio'].'</textarea></td>';
			}
		}
		else echo '<tr><td colspan="6"><p>No councillors are currently active</p></td></tr>'; // shouldn't occur as you have to be logged on to see this page
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					</table>
				
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});
	function update(id,field,input) {
		$.ajax({
			type: "POST",
			url: "editcouncillor.php",
			data: { id: id, field: field, value: $(input).val() }
		}).done(function(msg) {
			if(msg == 'Success') $(input).addClass('success');
			else $('.page-content').append(msg);
		});
	}
</script>
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>