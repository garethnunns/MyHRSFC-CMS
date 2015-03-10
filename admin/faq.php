<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage FAQs | MyHRSFC Admin</title>

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
					<span class="subhead">Manage FAQs</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Manage FAQs</h1>

					<aside>
<?php
	if(isset($_POST['question']) && isset($_POST['answer'])) { // adding an item
		if(validString('FAQ question',$_POST['question']) && validString('FAQ answer',$_POST['answer'])) {
			// valid question and answer
			try {
				$sth = $dbh->prepare("INSERT INTO faqs (question,answer) 
					VALUES (:question,:answer)");
				$sth->bindValue(':question',$_POST['question'], PDO::PARAM_STR);
				$sth->bindValue(':answer',$_POST['answer'], PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">FAQ item successfully added</p>';
				}
				else {
					echo '<p class="error">There was an internal error adding the FAQ item, please try again</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}
?>
						<h3>Add FAQ</h3>
						<form method="post">
							<p>Question:<br>
							<input type="text" name="question" class="full" placeholder="The question..." /></p>
							<p>Answer:<br>
							<textarea name="answer" placeholder="The answer..."></textarea></p>
							<input type="submit" value="Add FAQ &#187;">
						</form>
					</aside>
					
<?php
	if(isset($_GET['del'])) {
		if($sudo) { // only sudo users allowed to delete
			try {
				$sth = $dbh->prepare("DELETE FROM faqs
							WHERE idfaqs = :id LIMIT 1");
				$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">FAQ item successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting the FAQ item</p>';
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	try {
		$sql = "SELECT *
				FROM faqs
				ORDER BY question";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are there any FAQ items in database
			echo '<table rules="all" class="responsive">
			<tr><th width=150>Question</th><th>Answer (using MarkDown)</th>';
			if($sudo) echo '<th width=75>Delete</th>';
			echo '</tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td><input type="text" name="question"
				value="'.htmlentities($row['question']).'" onblur="update(\''.$row['idfaqs'].'\',this)" /></td>

				<td>
				<textarea name="answer" onblur="update(\''.$row['idfaqs'].'\',this)">'.$row['answer'].'</textarea>
				</td>';
				if($sudo) echo '<td class="center"><a href="faq.php?del='.$row['idfaqs'].'">Delete &#187;</a></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no FAQs stored in the database</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input, textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
	});

	function update(id,input) {
		$.ajax({
			type: "POST",
			url: "editfaq.php",
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