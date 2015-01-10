<?php
	include '../includes/secure.php';
	session_start();
	if(isset($_SESSION['user'])) header('Location: /admin/index.php');
	else {
		if(isset($_POST['email']) && isset($_POST['password'])) {
			$email = $_POST['email'];
			$password = $_POST['password'];

			try {
				$sth = $dbh->prepare('SELECT idcouncillors, sudo
					FROM councillors
					WHERE email = ? AND password = ?');
				$sth->bindValue(1, $email, PDO::PARAM_STR); 
				$sth->bindValue(2, $password, PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();
				
				if ($count==1) {

					$result = $sth->fetchAll();

					foreach($result as $row) {
						$_SESSION['user'] =  $row['idcouncillors'];
						if($row['sudo']) $_SESSION['sudo'] = true;
					}

					header('Location: ' . urldecode($_GET['goto']));
				}
				else {
					$error=true;
				}
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

?><!doctype html>
<html lang="en-gb" class="no-js">

	<!-- HEADER -->
	<head>

		<title>Admin Login | Hills Road Sixth Form College</title>

		<meta name="description" content="Stay up to date and in the loop with the official website from the HRSFC, Hills Road Sixth Form College, Student Council" />

		<?php include '../includes/head.php'; ?>
		
	</head>
	
	<body lang="en">

		<?php include '../includes/header.php'; ?>

		<!-- MAIN -->
		<div id="main">
				
			<?php include '../includes/social.php'; ?>
			
			<!-- Content -->
			<div id="content">
			
				<!-- masthead -->
				<div id="masthead">
					<span class="head">Welcome</span><span class="subhead">New site</span>
					<ul class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
						<li typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title">home</a> / </li>
						<li typeof="v:Breadcrumb"><a href="/login.php" rel="v:url" property="v:title">Admin Login</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div id="page-content-full">
					<form method="POST">
						<h2>Please log in</h2>

						<?php if($error) echo "<p>An error occured, please try again</p>"; ?>

						<p>Email: <input type="email" name="email" placeholder="email@address.com" <?php if(isset($email)) echo 'value="'.$email.'"'; ?> /></p>
						<p>Password: <input type="password" name="password" placeholder="Password" /></p>
						<p><input type="submit" value="Login"></p>
					</form>
				
				</div>
				<!-- ENDS page content -->
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php include '../includes/footer.php'; ?>

	</body>
</html>