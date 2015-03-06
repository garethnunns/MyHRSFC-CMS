<?php
	include 'checklogin.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Blog | MyHRSFC Admin</title>

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
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage Blog</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content">
					
					<h1>Manage blog</h1>

					<p><a href="blog.php">Add blog post &#187;</a></p>

<?php

	if(isset($_GET['act']) && $sudo) {
		try {
			$sql = "UPDATE blog SET active = NOT active WHERE idblog = :post";
			$sth = $dbh->prepare($sql);
			$sth->bindValue(':post',$_GET['act'], PDO::PARAM_STR);
			$sth->execute();
			if($sth->rowCount()) echo '<p class="success">The post has been activation state has been updated</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	try {
		$sql = "SELECT blog.date, blog.updated, blog.alias, blog.title, blog.active, councillors.name, blog.idblog
				FROM blog
				LEFT JOIN councillors ON blog.assoc_councillor = councillors.idcouncillors";
		if(!$sudo) $sql .= " WHERE blog.assoc_councillor = $user";
		$sql .= " ORDER BY (CASE WHEN blog.updated IS NULL THEN blog.date ELSE blog.updated END) DESC";

		$count = $dbh->query($sql)->rowCount();
		if($count) { ?>
					<table rules="all">
						<tr>
							<th>Date</th>
							<th>Blog Post</th>
							<?php if($sudo) {?><th>Author</th><?php } // normies will be author of all those they can see?>
							<th>Edit</th>
							<?php if($sudo) { ?><th>Deactivate</th> <?php } ?>
						</tr>
			<?php foreach($dbh->query($sql) as $row) {
				echo '<tr>

				<td class="center">'.date('j/n/y',strtotime($row['updated'] ? $row['updated'] : $row['date'])).'</td>

				<td>
				<a href="/blog/'.$row['alias'].'" target="_blank">'.$row['title'].' &#187;</a>
				</td>';

				if($sudo) echo '<td>'.($row['name'] ? $row['name'] : 'Whole Council').'</td>';

				echo '<td class="center"><a href="blog.php?post='.$row['idblog'].'"">Edit &#187;</a></td>';

				if($sudo) {
					echo '<td class="center">
					<a href="blogs.php?act='.$row['idblog'].'">';
					if($row['active']) echo 'Deactivate';
					else echo 'Activate';
					echo ' &#187;</a>
					</td>';
				}

				echo '</tr>';
			}
			echo '</table>';
		}
		else echo '<p>You don\'t have any blog posts that you can edit, 
			why not <a href="page.php">create a new one</a>?</p>';
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