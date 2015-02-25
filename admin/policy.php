<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Policies | MyHRSFC Admin</title>

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
					<span class="subhead">Manage Policies</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content">	        	
					
					<h1>Manage Policies</h1>
					
<?php
	if(isset($_GET['del'])) {
		if($sudo) { // only sudo users allowed to delete
			try {
				$sth = $dbh->prepare("DELETE FROM policies
							WHERE idpolicies = :id LIMIT 1");
				$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">The policy was successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting the policy</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	if(isset($_POST['add'])) { // adding an item
		if (validString('policy name',$_POST['name']) 
			&& validString('policy desc',$_POST['desc'])
			&& validProgress($_POST['progress'])) {
			$councillor = $user;
			if($sudo) $councillor = $_POST['counc'];
			// valid name and description
			try {
				$sth = $dbh->prepare("INSERT INTO policies
					VALUES (null,:councillor,:name,:description,:progress)");
				$sth->bindValue(':councillor',$councillor, PDO::PARAM_INT);
				$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
				$sth->bindValue(':description',$_POST['desc'], PDO::PARAM_STR);
				$sth->bindValue(':progress',$_POST['progress'], PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">The policy was successfully added</p>';
				}
				else {
					echo '<p class="error">There was an internal error adding the policy, please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	try {
		$sql = "SELECT *
				FROM policies ";
		if(!$sudo) { // normies can only view their own policies
			$sql .="WHERE assoc_councillor = $user ";
		}
		$sql .="ORDER BY name";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are there any a to z items in database
			echo '<table rules="all" class="responsive">
			<tr><th>Name</th><th>Description (using MarkDown)</th><th width="150">Progress';
			if($sudo) echo ' &amp;<br>Councillor</th><th width=75>Delete';
			echo '</th></tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td><input type="text" name="name" class="full"
				value="'.htmlentities($row['name']).'" onblur="update(\''.$row['idpolicies'].'\',this)" /></td>

				<td><textarea name="desc" onblur="update(\''.$row['idpolicies'].'\',this)">'.
				$row['desc'].'</textarea></td>

				<td class="center"><input type="range" name="progress" min="0" max="100" 
				value="'.$row['progress'].'" onchange="update(\''.$row['idpolicies'].'\',this)">';

				if($sudo) {
					echo '<select name="assoc_councillor" onchange="update(\''.$row['idpolicies'].'\',this)">
					<option value="0">Whole council</option>';
					councSelect($row['assoc_councillor']);
					echo '</select>';
				}
				echo '</td>';

				if($sudo) echo '<td class="center"><a href="policy.php?del='.$row['idpolicies'].'">Delete &#187;</a></td>';
				
				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no policies or you don\'t have any</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					<h3>Add a policy</h3>
					<form method="post">
						<p>Name:<br>
						<input type="text" name="name" class="full" placeholder="Policy name" /></p>
						<p>Description:</p>
						<textarea name="desc" placeholder="Explanation of policy..."></textarea>
						<p>Progress made: (%)<br>
						<input type="range" name="progress" min="0" max="100" value="0" /></p>
						<p>Councillor:
						<?php if($sudo) {?><select name="counc">
							<option value="0">Whole council</option>
							<?php councSelect(0); ?>
						</select><?php } ?></p>
						<input type="submit" value="Add policy &#187;" name="add">
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
			url: "editpolicy.php",
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