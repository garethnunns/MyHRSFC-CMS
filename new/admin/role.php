<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Roles | Hills Road Sixth Form College</title>

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
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage Roles</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content">	        	
					
					<h1>Manage Tutors</h1>
					
<?php
	if(isset($_GET['del'])) {
		try {
			$lookupsth = $dbh->prepare("SELECT name 
				FROM councillors
				WHERE role = :role");
			$lookupsth->bindValue(':role',$_GET['del'], PDO::PARAM_STR);
			$lookupsth->execute();

			$lookupcount = $lookupsth->rowCount();

			if(!$lookupcount) {
				$sth = $dbh->prepare("DELETE FROM councillors_roles
							WHERE idroles = :role LIMIT 1");
				$sth->bindValue(':role',$_GET['del'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">Tutor successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting the tutor</p>';
				}
			}
			else echo '<p class="error">There are councillors with that role, so it wasn\'t deleted</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	// adding role
	if(isset($_POST['addrole'])) addRole($_POST['addrole']);

	try {
		$sql = "SELECT *
				FROM councillors_roles

				ORDER BY rolename";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are tutors in database
			echo '<table rules="all">
			<tr><th>ID</th><th>Role</th><th>Delete</th></tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td class="center">'.$row['idroles'].'</td>
				<td><input type="text"  class="full"
				value="'.$row['rolename'].'" onblur="update(\''.$row['idroles'].'\',this)" /></td>
				<td class="center"><a href="role.php?del='.$row['idroles'].'">Delete &#187;</td>
				</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no tutors stored</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					<h3>Add role</h3>
					<form method="post">
						<p>Name of the role: <input type="text" name="addrole" placeholder="e.g. Chair" /> 
						<input type="submit" value="Add role &#187;"></p>
					</form>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});

	function update(id,input) {
		$.ajax({
			type: "POST",
			url: "editrole.php",
			data: { id: id, value: $(input).val() }
		}).done(function(msg) {
			if(msg == 'Success') $(input).addClass('success');
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