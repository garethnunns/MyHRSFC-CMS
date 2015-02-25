<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage tutors | MyHRSFC Admin</title>

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
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage Tutors</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Manage Tutors</h1>
					
<?php
	if(isset($_GET['del'])) {
		try {
			$lookupsth = $dbh->prepare("SELECT idcouncillors 
				FROM councillors
				WHERE tutor = :initials
				UNION
				SELECT idform_reps
				FROM form_reps
				WHERE tutor = :initials");
			$lookupsth->bindValue(':initials',$_GET['del'], PDO::PARAM_STR);
			$lookupsth->execute();

			$lookupcount = $lookupsth->rowCount();

			if(!$lookupcount) {
				$sth = $dbh->prepare("DELETE FROM tutors
							WHERE initials = :initials LIMIT 1");
				$sth->bindValue(':initials',$_GET['del'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">Tutor successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting the tutor</p>';
				}
			}
			else echo '<p class="error">There are councillors or form reps with that tutor ('.$_GET['del'].'), 
			so they weren\'t deleted</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	// adding tutor
	if(isset($_POST['initials']) && isset($_POST['tutor'])) addTutor($_POST['initials'],$_POST['tutor']);

	try {
		$sql = "SELECT *
				FROM tutors
				ORDER BY initials";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are tutors in database
			echo '<table rules="all">
			<tr><th>Initials</th><th>Name</th><th>Delete</th></tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td class="center"><input type="text" name="initials" 
				value="'.$row['initials'].'" class="tiny" onblur="update(\''.$row['initials'].'\',this)" /></td>
				<td><input type="text" name="name" class="full"
				value="'.$row['name'].'" onblur="update(\''.$row['initials'].'\',this)" /></td>
				<td class="center"><a href="tutor.php?del='.$row['initials'].'">Delete &#187;</a></td>
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
					<aside>
						<h3>Add tutor</h3>
						<form method="post">
							<p>Initials: <input type="text" name="initials" class="tiny" placeholder="ADC" /></p>
							<p>Tutor Name: <input type="text" name="tutor" placeholder="Mr Cumming" /></p>
							<p><input type="submit" value="Add Tutor &#187;"></p>
						</form>
					</aside>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});

	function update(initials,input) {
		$.ajax({
			type: "POST",
			url: "edittutor.php",
			data: { initials: initials, field: input.name, value: $(input).val() }
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