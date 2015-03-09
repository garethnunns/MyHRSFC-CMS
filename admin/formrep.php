<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Form Reps | MyHRSFC Admin</title>

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
					<span class="subhead">Manage Form Reps</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Manage Form Reps</h1>
					
<?php
	if(isset($_GET['del'])) {
		if($sudo) { // only sudo users allowed to delete
			try {
				$sth = $dbh->prepare("DELETE FROM form_reps
							WHERE idform_reps = :id LIMIT 1");
				$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">Those form reps were successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting those form reps</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	if(isset($_POST['add'])) { // adding an item
		if(validString('form rep1',$_POST['rep1']) && validString('form rep2',$_POST['rep2'])) {
			// valid name and description
			try {
				$sth = $dbh->prepare("INSERT INTO form_reps 
					VALUES (null,:tutor,:upper,:rep1,:rep2)");
				$sth->bindValue(':tutor',$_POST['tutor'], PDO::PARAM_STR);
				$sth->bindValue(':upper',$_POST['upper'], PDO::PARAM_STR);
				$sth->bindValue(':rep1',$_POST['rep1'], PDO::PARAM_STR);
				$sth->bindValue(':rep2',$_POST['rep2'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">Form reps were successfully added</p>';
				}
				else {
					echo '<p class="error">There was an internal error adding those form reps, please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	try {
		$sql = "SELECT *
				FROM form_reps
				ORDER BY tutor,`upper`,rep1,rep2";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are there any a to z items in database
			echo '<table rules="all" class="responsive">
			<tr><th width=50>Tutor</th><th>Form reps</th><th width=110>Year</th>';
			if($sudo) echo '<th width=75>Delete</th>';
			echo '</tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td>
				<select name="tutor" onchange="update('.$row['idform_reps'].',this)" >';
				tutorSelect($row['tutor']);
				echo '</select></td>

				<td>
				<input type="text" name="rep1" class="full" placeholder="Form rep name 1"
				value="'.htmlentities($row['rep1']).'" onblur="update(\''.$row['idform_reps'].'\',this)" />
				<input type="text" name="rep2" class="full" placeholder="Form rep name 2"
				value="'.htmlentities($row['rep2']).'" onblur="update(\''.$row['idform_reps'].'\',this)" />
				</td>

				<td class="center">
				<input type="radio" name="year'.$row['idform_reps'].'" id="lower'.$row['idform_reps'].'" value="0"';
				echo $row['upper'] ? '':' checked ';
				echo ' onchange="update('.$row['idform_reps'].',this)" title="upper" />
				<label for="lower'.$row['idform_reps'].'"> Lower</label><br>

				<input type="radio" name="year'.$row['idform_reps'].'" id="upper'.$row['idform_reps'].'" value="1"';
				echo $row['upper'] ? ' checked ':'';
				echo ' onchange="update('.$row['idform_reps'].',this)" title="upper" />
				<label for="upper'.$row['idform_reps'].'"> Upper</label>
				</td>';
				
				if($sudo) {
					echo '<td class="center"><a href="formrep.php?del='.$row['idform_reps'].'">Delete &#187;</a></td>';
				}
				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no form reps stored in the database</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					<aside>
						<h3>Add Form Rep</h3>
						<form method="post">
							<p>Tutor: <select name="tutor"><?php tutorSelect(); ?></select>
							<p>First Form Rep:<br>
							<input type="text" name="rep1" class="full" placeholder="Full Name" /></p>
							<p>Second Form Rep:<br>
							<input type="text" name="rep2" class="full" placeholder="Full Name" /></p>
							<p>Year: 
							<input type="radio" name="upper" id="upper" value="1"/><label for="upper"> Upper</label>
							<input type="radio" name="upper" id="lower" value="0"/><label for="lower"> Lower</label></p>
							<input type="submit" value="Add form rep &#187;" name="add">
						</form>
					</aside>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
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
			url: "editformrep.php",
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
