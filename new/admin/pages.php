<?php
	include '/home/a6325779/public_html/new/admin/checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Pages | Hills Road Sixth Form College</title>

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
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage Pages</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content">
					
					<h1>Pages Manager</h1>

					<table rules="all">
						<tr class="headings">
							<th>ID</th>
							<th>Page</th>
							<th class="nomobile">Title</th>
							<th>Owner</th>
							<th>Edit</th>
							<?php if($sudo) { ?><th>Deactivate</th> <?php } ?>
						</tr>

<?php

	if(isset($_GET['act']) && $sudo) {
		try {
			// look up the page to check it
			$lookupsth = $dbh->prepare('SELECT alias, active
										FROM pages
										WHERE idpages = ?');
			$lookupsth->bindValue(1, $_GET['act'], PDO::PARAM_STR);
			$lookupsth->execute();

			$count = $lookupsth->rowCount();

			if($count) { // exists
				$result = $lookupsth->fetch(PDO::FETCH_OBJ);

				if(isSpecial($result->alias)) { // special pages can't be inactive
					echo '<p class="error">This pages cannot be activated/deactivated</p>';
				}	
				else {
					$sql = "UPDATE pages SET active = NOT active WHERE idpages = :page";
					$sth = $dbh->prepare($sql);
					$sth->bindValue(':page',$_GET['act'], PDO::PARAM_STR);
					$sth->execute();
					echo '<p class="success"><a href="/'.$result->alias.'" target="_blank">The page</a> has been ';
					echo $result->active ? 'deactivated' : 'activated';
					'</p>';
				}
			}
			else echo '<p class="error">The page to be activated/deactivated could not be found</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	try {
		$sql = "SELECT pages.idpages, pages.alias, pages.title, pages.active, councillors.name
				FROM pages
				LEFT JOIN councillors ON pages.assoc_councillor = councillors.idcouncillors";
		if(!$sudo) $sql .= " WHERE pages.assoc_councillor = $user";
		$sql .= " ORDER BY pages.alias";

		$count = $dbh->query($sql)->rowCount();
		if($count) {
			foreach($dbh->query($sql) as $row) {
				echo '<tr>';

				echo '<td class="center">'.$row['idpages'].'</td>';

				echo '<td>
				<a href="/'.$row['alias'].'" target="_blank">'.$row['alias'].' &#187;</a>
				</td>';

				echo '<td class="nomobile">'.$row['title'].'</td>';

				echo '<td>'.$row['name'].'</td>';

				echo '<td class="center"><a href="page.php?page='.$row['idpages'].'"">Edit &#187;</a></td>';

				if($sudo) {
					echo '<td class="center">';
					if(!isSpecial($row['alias'])) { // can be deactivated
						echo '<a href="?act='.$row['idpages'].'">';
						if($row['active']) echo 'Deactivate';
						else echo 'Activate';
						echo ' &#187;</a>';
					}
					else echo '<i>Cannot be deleted</i>';

					echo '</td>';
				}

				echo '</tr>';
			}
		}
		else echo '<tr><td colspan="6"><p>There are no pages that you are able to edit</p></td></tr>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					</table>
				
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