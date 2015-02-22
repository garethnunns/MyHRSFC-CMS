<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Links | MyHRSFC Admin</title>

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
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage Links</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Manage links</h1>
					
<?php
	if(isset($_GET['del'])) {
		try {
			$lookupsth = $dbh->prepare("SELECT idnav
				FROM nav
				WHERE idlinks = :id");
			$lookupsth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
			$lookupsth->execute();

			$lookupcount = $lookupsth->rowCount();

			if(!$lookupcount) { // not used in nav
				$sth = $dbh->prepare("DELETE FROM links
							WHERE idlinks = :id LIMIT 1");
				$sth->bindValue(':id',$_GET['del'], PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) {
					echo '<p class="success">Link successfully deleted</p>';
				}
				else {
					echo '<p class="error">There was an error deleting the link</p>';
				}
			}
			else echo '<p class="error">The link is currently in use in the navigation and so was not deleted</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	if(isset($_POST['name']) && isset($_POST['url'])) { // adding an item
		if(validString('link name',$_POST['name']) && validString('link URL',$_POST['url'])) {
			// valid name and url
			if($_POST['email'] && (filter_var($_POST['url'], FILTER_VALIDATE_EMAIL))) {
				// valid email address
				try {
					$sth = $dbh->prepare("INSERT INTO links (name,URL,email) -- todo adding colour 
						VALUES (:name,:url,:email)");
					$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
					$sth->bindValue(':url',$_POST['url'], PDO::PARAM_STR);
					$sth->bindValue(':email',($_POST['email'] ? 1 : 0), PDO::PARAM_INT);
					$sth->execute();

					$count = $sth->rowCount();

					if($count) {
						echo '<p class="success">The link successfully added</p>';
					}
					else {
						echo '<p class="error">There was an internal error adding the link, please try again</p>';
					}
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
			else echo '<p class="error">Invalid email address provided</p>';
		}
	}

	try {
		$sql = "SELECT *
				FROM links
				ORDER BY name";

		$count = $dbh->query($sql)->rowCount();
		if($count) { // are links stored in the database
			echo '<table rules="all" class="responsive">
			<tr><th>Name</th><th>URL/Email</th><th>Email</th><th>Delete</th></tr>';
			foreach($dbh->query($sql) as $row) {
				echo '<tr>
				<td>
				<input type="text" name="name"
				value="'.htmlentities($row['name']).'" onblur="update(\''.$row['idlinks'].'\',this)" />
				</td>

				<td>
				<input type="text" name="URL" class="full"
				value="'.$row['URL'].'" onblur="update(\''.$row['idlinks'].'\',this)" />
				</td>

				<td>
				<input type="radio" name="email'.$row['idlinks'].'" id="yes'.$row['idlinks'].'" value="1"';
				echo $row['email'] ? ' checked ':'';
				echo ' onchange="update('.$row['idlinks'].',this)" title="email" />
				<label for="yes'.$row['idlinks'].'"> Yes</label>
				<input type="radio" name="email'.$row['idlinks'].'" id="no'.$row['idlinks'].'" value="0"';
				echo $row['email'] ? '':' checked ';
				echo ' onchange="update('.$row['idlinks'].',this)" title="email" />
				<label for="no'.$row['idlinks'].'"> No</label>
				</td>

				<td class="center"><a href="link.php?del='.$row['idlinks'].'">Delete &#187;</td>
				</tr>';
			}
			echo '</table>';
		}
		else echo '<p>There are currently no links stored in the database</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					<aside>
						<h3>Add link</h3>
						<form method="post">
							<p>Name: <input type="text" name="name" placeholder="e.g. Link to..." /></p>
							<p>URL:</p>
							<input type="text" name="url" class="full" placeholder="http://... / bla@bla.com" />
							<p><input type="checkbox" name="email" id="email">
							<label for="email">Link is an email address</label></p>
							<p><input type="submit" value="Add links &#187;"></p>
						</form>
					</aside>
				</div>
				<!-- ENDS page content -->

<script type="text/javascript">
	$('input,textarea').focus(function () { // remove success class when input used again
		$(this).removeClass('success');
		$('label').removeClass('success');
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
			url: "editlink.php",
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