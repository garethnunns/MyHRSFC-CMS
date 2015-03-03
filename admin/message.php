<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Messages | MyHRSFC Admin</title>

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
					<span class="subhead">Your messages</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
<?php
	if(isset($_GET['read'])) {
		try {
			$sql = "UPDATE contact
					SET complete = NOT complete 
					WHERE idcontact = :id 
					AND (assoc_councillor = $user OR assoc_councillor = 0)";
			$sth = $dbh->prepare($sql);
			$sth->bindValue(':id',$_GET['read'], PDO::PARAM_INT);
			$sth->execute();
			if(!$sth->rowCount()) echo '<p class="error">Error updating message read state</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
?>
				
				<!-- page content -->
				<div class="page-content">
					
					<h1><?php echo messageCount($user).(messageCount($user)==1 ? ' message' : ' messages') ?></h1>

<?php

	try {
		$sql = "SELECT *
				FROM contact
				WHERE assoc_councillor = $user
				OR assoc_councillor = 0
				ORDER BY complete, idcontact DESC";

		$count = $dbh->query($sql)->rowCount();
		if($count) {
			echo '<table rules="all" class="responsive">
			<tr>
			<th width=50>ID</th>
			<th width=180>Contacter</th>
			<th width="75">To</th>
			<th>Message</th>
			<th width=70>Read?</th>
			</tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>

				<td class="center nomobile">'.$row['idcontact'].'</td>
				
				<td>';
				if($row['name']) echo '<p><i>Name:</i><br>'.$row['name'].'</p>';
				if($row['email']) echo '<p><i>Email:</i><br>'.$row['email'].'</p>';
				echo '</td>

				<td class="center"><b>'.($row['assoc_councillor'] ? 'You' : 'Council').'</b></td>
				
				<td>';
				if($row['subject']) echo '<h4>'.$row['subject'].'</h4>';
				echo '<p>'.nl2br($row['message']).'</p>
				</td>

				<td class="center">';
				echo '<a href="message.php?read='.$row['idcontact'].'">'.
				($row['complete'] ? 'Unread':'Read').' &#187;</a>
				</td>

				</tr>';
			}
			echo '</table>';
		}
		else echo '<p>You don\'t have any messages</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
				
				</div>
				<!-- ENDS page content -->
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>