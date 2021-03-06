<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage A to Z | MyHRSFC Admin</title>

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
					
					<h1>Manage A to Z</h1>

					<aside>
<?php
	if(isset($_POST['name']) && isset($_POST['desc'])) { // adding an item
		if(validString('A to Z name',$_POST['name']) && validString('A to Z description',$_POST['desc'])) {
			// valid name and description
			try {
				$sth = $dbh->prepare("INSERT INTO AtoZ (name,`desc`) 
					VALUES (:name,:description)");
				$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
				$sth->bindValue(':description',$_POST['desc'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">A to Z item successfully added</p>';
				}
				else {
					echo '<p class="error">There was an internal error adding the A to Z item, please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}
?>
						<h3>Add A to Z item</h3>
						<form method="post">
							<p>Name: <input type="text" name="name" class="small" placeholder="e.g. Absence" /></p>
							<p>Description:</p>
							<textarea name="desc" placeholder="Explanation of item..."></textarea>
							<input type="submit" value="Add Item &#187;">
						</form>
					</aside>
					
<?php
	if(isset($_GET['del'])) {
		if($sudo) { // only sudo users allowed to delete
			try {
				$sth = $dbh->prepare("DELETE FROM AtoZ
							WHERE idAtoZ = :id LIMIT 1");
				$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">A to Z item successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting the A to Z item</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	try {
		$sql = "SELECT *
				FROM AtoZ
				ORDER BY name";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are there any a to z items in database
			echo '<table rules="all" class="responsive">
			<tr><th>Name</th><th>Description (using MarkDown)</th>';
			if($sudo) echo '<th width=75>Delete</th>';
			echo '</tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td><input type="text" name="name" class="full"
				value="'.htmlentities($row['name']).'" onblur="update(\''.$row['idAtoZ'].'\',this)" /></td>
				<td><textarea name="desc" onblur="update(\''.$row['idAtoZ'].'\',this)">'.$row['desc'].'</textarea></td>';
				if($sudo) echo '<td class="center"><a href="atoz.php?del='.$row['idAtoZ'].'">Delete &#187;</a></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no A to Z items stored in the database</p>';
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
			url: "editatoz.php",
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