<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage GCBs | MyHRSFC Admin</title>

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
					<span class="subhead">Manage Global Content Blocks</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Manage Global Content Blocks</h1>
					
<?php
	if(isset($_GET['del'])) { // deleting a gcb
		try {
			$sth = $dbh->prepare("DELETE FROM gcb
						WHERE idgcb = :id LIMIT 1");
			$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
			$sth->execute();

			$count = $sth->rowCount();

			if($count) {
				echo '<p class="success">The global content block was successfully deleted</p>';
			}
			else {
				echo '<p class="error">There was an error deleting the global content block</p>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	if(isset($_POST['name']) && isset($_POST['content'])) { // adding a gcb
		if(validString('GCB name',$_POST['name']) && validString('GCB content',$_POST['content'])) {
			// valid name and content
			try {
				$sth = $dbh->prepare("INSERT INTO gcb (name,content) 
					VALUES (:name,:content)");
				$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
				$sth->bindValue(':content',$_POST['content'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">The global content block was successfully added</p>';
				}
				else {
					echo '<p class="error">There was an error adding the global content block, please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	try {
		$sql = "SELECT *
				FROM gcb
				ORDER BY name";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // there are GCBs in the db
			echo '<table rules="all" class="responsive">
			<tr><th width=100>Name</th><th>Content</th><th width=75>Delete</th>';
			echo '</tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td class="center"><input type="text" name="name" class="small"
				value="'.htmlentities($row['name']).'" onblur="update(\''.$row['idgcb'].'\',this)" /></td>

				<td><textarea name="content" onblur="update(\''.$row['idgcb'].'\',this)">'.$row['content'].'</textarea></td>';
				if($sudo) echo '<td class="center"><a href="gcb.php?del='.$row['idgcb'].'">Delete &#187;</a></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no global content blocks stored in the database</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					<aside>
						<h3>Add Global Content Block</h3>
						<p><i>This will needed to be added into the website code by the webmaster</i></p>
						<form method="post">
							<p>Name: <input type="text" name="name" class="small" placeholder="e.g. footer" /></p>
							<p>Description:</p>
							<textarea name="content" placeholder="HTML code"></textarea>
							<input type="submit" value="Add content block &#187;">
						</form>
					</aside>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});

	function update(id,input) {
		$.ajax({
			type: "POST",
			url: "editgcb.php",
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