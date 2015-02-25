<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Functions | MyHRSFC Admin</title>

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
					<span class="subhead">Manage Functions</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				<!-- page content -->
				<div class="page-content">	        	
					
					<h1>Manage Functions</h1>
					
<?php
	if(isset($_GET['del'])) { // deleting a function
		try {
			$sth = $dbh->prepare("DELETE FROM functions
						WHERE idfunctions = :id LIMIT 1");
			$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
			$sth->execute();

			$count = $sth->rowCount();

			if($count) {
				echo '<p class="success">The function was successfully deleted</p>';
			}
			else {
				echo '<p class="error">There was an error deleting the function</p>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	if(isset($_POST['name']) && isset($_POST['desc']) && isset($_POST['desc']) && isset($_POST['content'])) {
		// adding a function
		if(validString('function name',$_POST['name'])
			&& validString('function desc',$_POST['desc'])
			&& validString('function content',$_POST['content'])) {
			// valid name, description and content
			try {
				$sth = $dbh->prepare("INSERT INTO functions (name,`desc`,content) 
					VALUES (:name,:description,:content)");
				$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
				$sth->bindValue(':description',$_POST['desc'], PDO::PARAM_STR);
				$sth->bindValue(':content',$_POST['content'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">The function was successfully added</p>';
				}
				else {
					echo '<p class="error">There was an error adding the function, please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	try {
		$sql = "SELECT *
				FROM functions
				ORDER BY name";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // there are functions in the db
			echo '<table rules="all" class="responsive">
			<tr><th width=150>Name</th><th width=150>Description</th><th>Content</th><th width=75>Delete</th>';
			echo '</tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td><input type="text" name="name"
				value="'.htmlentities($row['name']).'" onblur="update(\''.$row['idfunctions'].'\',this)" /></td>
				<td>
				<textarea name="desc" onblur="update(\''.$row['idfunctions'].'\',this)">'.
				htmlentities($row['desc']).'</textarea>
				</td>
				<td>
				<textarea name="content" onblur="update(\''.$row['idfunctions'].'\',this)">'.
				htmlentities($row['content']).'</textarea>
				</td>';
				if($sudo) {
					echo '<td class="center"><a href="function.php?del='.$row['idfunctions'].'">Delete &#187;</a></td>';
				}
				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no functions stored in the database</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					<p>&nbsp;</p>
					<h3>Add a function</h3>
					<form method="post">
						<p>Name: <input type="text" name="name" class="small" placeholder="function(var)" /></p>
						<p>Description:</p>
						<textarea name="desc" placeholder="Description of what the function does"></textarea>
						<p>Content:</p>
						<textarea name="content" placeholder="PHP code"></textarea>
						<input type="submit" value="Add function &#187;">
					</form>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});

	function update(id,input) {
		$.ajax({
			type: "POST",
			url: "editfunction.php",
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