<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Societies | MyHRSFC Admin</title>

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
					<span class="subhead">Manage Societies</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Manage Societies</h1>

					<aside>
						<h3>Add society</h3>
<?php
	if(isset($_POST['add'])) { // adding an item
		if (validString('society name',$_POST['society']) 
			&& validString('society leader name',$_POST['name'])
			&& validString('society leader email',$_POST['email'])
			&& validString('society description',$_POST['desc'])) {
			// valid inputs
			try {
				$sth = $dbh->prepare("INSERT INTO societies VALUES (null,:society,:name,:email,:description)");
				$sth->bindValue(':society',$_POST['society'], PDO::PARAM_STR);
				$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
				$sth->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
				$sth->bindValue(':description',$_POST['desc'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">The society was successfully added</p>';
				}
				else {
					echo '<p class="error">There was an internal error adding the society, please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}
?>
						<form method="post">
							<p>Name: <input type="text" name="society" placeholder="Name of society" /></p>
							<p>Leader: <input type="text" name="name" placeholder="Full name of leader" /></p>
							<p>College ID of leader: <input type="text" name="email" placeholder="e.g. AB123456" /></p>
							<p>Description:</p>
							<textarea name="desc" placeholder="Description of the society"></textarea>
							<input type="submit" value="Add society &#187;" name="add">
						</form>
					</aside>
					
<?php
	if(isset($_GET['del'])) {
		if($sudo) { // only sudo users allowed to delete
			try {
				$sth = $dbh->prepare("DELETE FROM societies
							WHERE idsocieties = :id LIMIT 1");
				$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">The society has been successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting the society</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	try {
		$sql = "SELECT *
				FROM societies
				ORDER BY society";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are there any societies in database
			echo '<table rules="all" class="responsive">
			<tr><th width=118>Name</th><th width=120>Leader</th><th>Description</th>';
			if($sudo) echo '<th width=75>Delete</th>';
			echo '</tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td class="center"><input type="text" name="society" class="full" placeholder="Society Name"
				value="'.htmlentities($row['society']).'" onblur="update(\''.$row['idsocieties'].'\',this)" /></td>

				<td class="center">Leader\'s name:<br>
				<input type="text" name="name" class="small" placeholder="Leader\'s name"
				value="'.htmlentities($row['name']).'" onblur="update(\''.$row['idsocieties'].'\',this)" /><br>

				Leader\'s email:<br>
				<input type="text" name="email" class="small" placeholder="Leader\'s email"
				value="'.htmlentities($row['email']).'" onblur="update(\''.$row['idsocieties'].'\',this)" />
				</td>

				<td>
				<textarea name="desc" onblur="update(\''.$row['idsocieties'].'\',this)">'.$row['desc'].'</textarea>
				</td>';

				if($sudo) echo '<td class="center">
					<a href="society.php?del='.$row['idsocieties'].'">Delete &#187;</a>
					</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no societies items stored in the database</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});

	function update(id,input) {
		$.ajax({
			type: "POST",
			url: "editsociety.php",
			data: { id: id, field: input.name, value: $(input).val() }
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