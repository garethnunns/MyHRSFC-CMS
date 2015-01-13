<?php
	include 'includes/parsedown.php';
	include 'includes/secure.php';
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>MyHRSFC | The Student Council | Hills Road Sixth Form College</title>

		<meta name="description" content="Stay up to date and in the loop with the official website from the HRSFC, Hills Road Sixth Form College, Student Council" />

		<?php include 'includes/head.php'; ?>
		
	</head>
	
	<body lang="en" ontouchstart="">

		<?php include 'includes/header.php'; ?>

		<!-- MAIN -->
		<div id="main">
				
			<?php include 'includes/social.php'; ?>
			
			<!-- Content -->
			<div id="content">
			
				<!-- masthead -->
				<div id="masthead">
					<span class="head">Welcome</span><span class="subhead">New site</span>
					<ul class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
						<li typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title">home</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div id="page-content-full">

					<?php
						$parsedown = new Parsedown();

						try {
							$sql = "SELECT * 
								FROM pages 
								WHERE `alias` = 'atoz'";
							
							foreach ($dbh->query($sql) as $row) {
								echo '<h2>'.htmlentities($row['title']).'</h2>';
								
								$body = explode("{",$row['body']);

								foreach ($body as $key => $content) { // to execute functions
									if($key == 0) echo $parsedown->text($content);
									else {
										list($function,$text) = explode("}",$content);
										$function = trim($function);

										list($name,$args) = explode("(",$function);

										$name = trim($name);

										$args = substr($args,0,-1); // remove closing bracket
										$arg = explode(",",$args);

										$funcsth = $dbh->prepare('SELECT `name`,`content`
											FROM functions
											WHERE `name` = ? OR `name` LIKE ? OR `name` LIKE ?');
										$funcsth->bindValue(1, $name, PDO::PARAM_STR);
										$funcsth->bindValue(2, $name.'(%', PDO::PARAM_STR);
										$funcsth->bindValue(3, $name.' (%', PDO::PARAM_STR);
										$funcsth->execute();

										$count = $funcsth->rowCount();
										if($count == 1) {
											$funcrow = $funcsth->fetch(PDO::FETCH_OBJ);
											
											$funcName = $funcrow->name;
											$funcCode = $funcrow->content;

											if(preg_match('/\((.*?)\)/',$funcName,$params)) { // if there are arguments
												$params = explode(",",substr($params[0],1,-1));
												foreach ($params as $num => $param) {
													${$param} = $arg[$num];
												}
											}

											eval($funcCode);
										}
										else { // function not found/more than one function with that name found
											echo "<p>There was an error parsing the functon: $function </p>";
										}

										echo $parsedown->text($text);
									}
								}
							}
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
	
	<?php include 'includes/footer.php'; ?>

	</body>
</html>