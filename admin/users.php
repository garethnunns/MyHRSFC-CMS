<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Users | MyHRSFC Admin</title>

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
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage Users</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				<!-- page content -->
				<div class="page-content">
					
					<h1>Manage Users</h1>

					<p><a href="user.php">Add user &#187;</a></p>

					<table rules="all" class="responsive">
						<tr class="headings">
							<th class="image">Image</th>
							<th colspan="2">Personal</th>
							<th>Tutor &amp; Sudo</th>
							<th>Edit</th>
							<th>Active</th>
						</tr>

<?php
	if(isset($_FILES['picture'])) uploadProfilePic($_FILES['picture'],$_POST['id']);

	if(isset($_POST['initials']) && isset($_POST['tutor'])) addTutor($_POST['initials'],$_POST['tutor']);

	if(isset($_POST['role'])) addRole($_POST['role']);

	if(isset($_GET['act'])) {
		try {
			$sql = "UPDATE councillors SET active = NOT active WHERE idcouncillors = :id";
			$sth = $dbh->prepare($sql);
			$sth->bindValue(':id',$_GET['act'], PDO::PARAM_STR);
			$sth->execute();
			$count = $sth->rowCount();
			if($count) {
				echo '<p class="success">The councillor has been ';
				echo $result->active ? 'deactivated' : 'activated';
				'</p>';
			}
			else echo '<p class="error">The councillor to be activated/deactivated was not found</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	try {

		$sql = "SELECT *
				FROM councillors
				ORDER BY councillors.active DESC, councillors.name ASC";

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
				<input type="submit" value="Upload &#187;" /></form></td>

				<td><h6>Name</h6>
				<p><input type="text" name="name" value="'.$row['name'].'" class="small" 
				onblur="update('.$row['idcouncillors'].',this)" /></p>

				<h6>Short name</h6>
				<p><input type="text" name="shortname" value="'.$row['shortname'].'"class="small" 
				onblur="update('.$row['idcouncillors'].',this)" /></p>
				</td>

				<td><h6>Email</h6>
				<p><input type="email" name="email" value="'.$row['email'].'" 
				onblur="update('.$row['idcouncillors'].',this)" /></p>
				<h6>Role</h6><p><select name="role" 
				onchange="update('.$row['idcouncillors'].',this)" >';
				roleSelect($row['role']);
				echo '</select></p></td>

				<td>
				<h6>Tutor</h6>
				<p><select name="tutor" 
				onchange="update('.$row['idcouncillors'].',this)" >';
				tutorSelect($row['tutor']);
				echo '</select></p>

				<h6>Sudo:</h6>
				<input type="radio" name="sudo'.$row['idcouncillors'].'" id="yes'.$row['idcouncillors'].'" value="1"';
				echo $row['sudo'] ? ' checked ':'';
				echo ' onchange="update('.$row['idcouncillors'].',this)" title="sudo" />
				<label for="yes'.$row['idcouncillors'].'"> Yes</label>
				<input type="radio" name="sudo'.$row['idcouncillors'].'" id="no'.$row['idcouncillors'].'" value="0"';
				echo $row['sudo'] ? '':' checked ';
				echo ' onchange="update('.$row['idcouncillors'].',this)" title="sudo" />
				<label for="no'.$row['idcouncillors'].'"> No</label></td>

				<td class="center"><a href="user.php?user='.$row['idcouncillors'].'">Edit &#187;</a></td>
				<td class="center"><a href="users.php?act='.$row['idcouncillors'].'">';
				if($row['active']) echo 'Deactivate';
				else echo 'Activate';
				echo ' &#187;</a></td>';

				echo '</tr>';
			}
		}
		else echo '<tr><td colspan="6"><p>No councillors are currently active</p></td></tr>';
		// shouldn't occur as you have to be logged on to see this page
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					</table>

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
				
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
		$('label').removeClass('success');
	});

	function update(id,input) {
		var val = $(input).val();
		var field = input.name;
		if($(input).attr("type") == 'radio') { // find selected one and get val
			val = $("input:radio[name='"+input.name+"']:checked" ).val();
			field = $(input).attr("title");
		}
		$.ajax({
			type: "POST",
			url: "editcouncillor.php",
			data: { id: id, field: field, value: val }
		}).done(function(msg) {
			if(msg == 'Success') {
				if($(input).attr("type") == 'radio') { // turn label for it green
					$("label[for='"+$(input).attr('id')+"']").addClass('success');
				}
				else $(input).addClass('success');
			} 
			else $('.page-content').prepend(msg); // add errors to the top of the page
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