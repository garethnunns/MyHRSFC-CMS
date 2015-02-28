<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Profile Editor | MyHRSFC Admin</title>

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
					<span class="subhead">
					<?php
						if($sudo) echo '<a href="users.php">Manage Users</a>';
						else echo 'Update your profile' ?>
					</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content">	        	
<?php
	if(!$sudo) $requser = $user;
	if(isset($_GET['user']) && $sudo) $requser = $_GET['user']; // sudo users can edit any user

	if(isset($_POST['updatepic'])) uploadProfilePic($_FILES['picture'],$requser);

	if(isset($_POST['updatepassword'])) { // updating password
		if($_POST['password'] == $_POST['password2']) { // passwords match
			try {
				$password = cryptPassword($_POST['password']);
				$sth = $dbh->prepare("UPDATE councillors 
					SET password = :password 
					WHERE idcouncillors = :id");
				$sth->bindValue(':id',$requser, PDO::PARAM_INT);
				$sth->bindValue(':password',$password, PDO::PARAM_STR);
				$sth->execute();
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
		else echo '<p class="error">The passwords do not match, please try again</p>';
	}

	if(isset($_POST['add'])) { // adding
		if (validString('councillor name',$_POST['name']) &&
			validString('councillor shortname',$_POST['shortname']) &&
			validString('councillor subjects',$_POST['subjects']) &&
			validString('councillor bio',$_POST['bio'])) {
			if ($_POST['password'] == $_POST['password2'] 
				&& validString('councillor password',$_POST['password'])) { // passwords match & valid
				if(validEmail($_POST['email'])) {
					try {
						$password = cryptPassword($_POST['password']);
						$sth = $dbh->prepare("INSERT INTO councillors
							(name,shortname,email,password,role,tutor,subjects,bio,sudo,active)
							VALUES (:name,:shortname,:email,:password,:role,:tutor,:subjects,:bio,:sudo,1)");
						$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
						$sth->bindValue(':shortname',$_POST['shortname'], PDO::PARAM_STR);
						$sth->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
						$sth->bindValue(':password',$password, PDO::PARAM_STR);
						$sth->bindValue(':role',$_POST['role'], PDO::PARAM_INT);
						$sth->bindValue(':tutor',$_POST['tutor'], PDO::PARAM_STR);
						$sth->bindValue(':subjects',$_POST['subjects'], PDO::PARAM_STR);
						$sth->bindValue(':bio',$_POST['bio'], PDO::PARAM_STR);
						$sth->bindValue(':sudo',$_POST['sudo'], PDO::PARAM_INT);
						$sth->execute();
						echo '<p class="success">User sucessfully added</p>';

						if(!getIDfromEmail($_POST['email']) && isset($_FILES['picture'])) {
							echo '<p class="error">New user could not be found to add profile picture to</p>';
						}
						else uploadProfilePic($_FILES['picture'],getIDfromEmail($_POST['email']));
					}
					catch (PDOException $e) {
						echo $e->getMessage();
					}
				}
			}
			else echo '<p class="error">The passwords do not match, please try again</p>';
		}
	}

	if(isset($_POST['initials']) && isset($_POST['tutor'])) addTutor($_POST['initials'],$_POST['tutor']);
	if(isset($_POST['addrole'])) addRole($_POST['addrole']);

	if(isset($requser)) {
		try {
			$sth = $dbh->prepare("SELECT *
				FROM councillors
				WHERE idcouncillors = ?
				LIMIT 1");
			$sth->bindValue(1, $requser, PDO::PARAM_INT);
			$sth->execute();

			$count = $sth->rowCount();
		
			if ($count) {
				$councillor = $sth->fetch(PDO::FETCH_OBJ);
				echo '<h1>Edit '.$councillor->shortname.'\'s profile</h1>';
			}
			else echo '<p class="error">The requested councillor could not be found</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	else echo '<h1>Add user</h1>';

	if((isset($requser) && $count) || !isset($requser)) {
		// image
		if(!empty($councillor->image)) {
			echo '<img src="'.$councillor->image.'" class="thumb" />
			<h6>Update';
		}
		else echo '<h6>Add';
		echo ' image</h6>
		<form method="post" enctype="multipart/form-data">
		<p><input type="file" name="picture" /></p>';
		if(isset($requser)) echo '<p><input type="submit" value="Upload &#187;" name="updatepic" /></p></form>'; 
		// end form for editing profile picture
		
		echo '<div class="clearfix"></div>

		<h6>Name</h6>
		<p><input type="text" name="name" value="'.$councillor->name.'" class="small" 
		onblur="update(this)" placeholder="Full name" required /></p>

		<h6>Short name</h6>
		<p><input type="text" name="shortname" value="'.$councillor->shortname.'" class="small" 
		onblur="update(this)" placeholder="First name/nick name" /></p>

		<h6>Email</h6>
		<p><input type="email" name="email" value="'.$councillor->email.'" 
		onblur="update(this)" placeholder="Personal/college address" required /></p>';

		if(isset($requser)) echo '<form method="post">';

		echo '<h6>Password</h6>
		<p><input type="password" name="password" placeholder="Secure password" required></p>
		<p><input type="password" name="password2" placeholder="Confirm password" required></p>';

		if(isset($requser)) {
			echo '<p><input type="submit" value="Update Password &#187;" name="updatepassword" /></p></form>';
		}

		if($sudo) { // only sudo can change role
			echo '<h6>Role</h6>
			<p><select name="role" onchange="update(this)" >';
			roleSelect($councillor->role);
			echo '</select></p>';
		}
		
		echo '<h6>Tutor</h6>
		<p><select name="tutor" 
		onchange="update(this)" >';
		tutorSelect($councillor->tutor);
		echo '</select></p>';

		echo '<h6>Subjects</h6>
		<input type="text" name="subjects" class="full" 
		onblur="update(this)" placeholder="Subjects taken this year" value="'.$councillor->subjects.'"/>

		<h6>Biography</h6>
		<textarea name="bio" placeholder="Short biography.." 
		onblur="update(this)">'.$councillor->bio.'</textarea>';

		if($sudo) {
			echo '<h6>Sudo:</h6>
			<input type="radio" name="sudo" id="yes" value="1"';
			echo $councillor->sudo ? ' checked ':'';
			echo ' onchange="update(this)" /><label for="yes"> Yes</label>
			<input type="radio" name="sudo" id="no" value="0"';
			echo $councillor->sudo ? '':'checked';
			echo ' onchange="update(this)" /><label for="no"> No</label>
			<p>Takes effect at next login</p>';
		}

		if(!isset($requser)) echo '<p><input type="submit" value="Add User &#187;" name="add" /></p></form>'; // end form for adding
	}

	if($sudo) { ?>
		<p>&nbsp;</p>
		<h3>Add role</h3>
		<form method="POST">
			<p>Name of the role: <input type="text" name="addrole" placeholder="e.g. Chair" /> 
			<input type="submit" value="Add role &#187;"></p>
		</form>

		<h3>Add tutor</h3>
		<form method="POST">
			<p>Initials: <input type="text" name="initials" class="small" placeholder="e.g. ADC" /> 
			Tutor Name: <input type="text" name="tutor" placeholder="e.g. Mr Cumming" /> 
			<input type="submit" value="Add Tutor &#187;"></p>
		</form>
	<?php } // end sudo only for adding role and tutor ?>
					
				
				</div>
				<!-- ENDS page content -->

<?php if(isset($requser) && $count) { // only AJAX when editing ?>
<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
		$('label').removeClass('success');
	});

	function update(input) {
		var id = <?php echo $councillor->idcouncillors; ?>;
		if($(input).attr("type") == 'radio') { // find selected one and get val
			var val = $("input:radio[name='"+input.name+"']:checked" ).val();
		}
		else var val = $(input).val();
		var field = input.name;
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
<?php } // end updating only AJAX? ?>
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>