<?php
	// CMS written by Gareth Nunns: http://garethnunns.com

	// functions used by both the admin and public page
	require_once dirname(__FILE__).'/secure.php';
	require_once dirname(__FILE__).'/parsedown.php';

	$parsedown = new Parsedown();

	// set timezone
	date_default_timezone_set('Europe/London');
	$dbh->exec("SET time_zone='+0:00';");

	function write($content) { // outputting MarkDown, allowing HTML tags 
		global $parsedown;

		echo $parsedown->text($content);
	}

	function line($content) { // outputting markdown, allowing only inline MarkDown
		global $parsedown;

		echo $parsedown->line(htmlspecialchars($content));
	}

	function outputContent($body,$markdown = true) {
	// output content that includes functions, with or with out markdown parsed

		global $dbh;

		$body = explode("{",$body);

		foreach ($body as $key => $content) { // to execute functions
			if($key == 0) { // everything / before first function
				if ($markdown) write($content);
				else echo $content;
			}
			else {
				list($function,$text) = explode("}",$content);
				$function = trim($function);

				list($name,$args) = explode("(",$function);

				$name = trim($name);

				$args = substr($args,0,-1); // remove closing bracket
				$arg = explode(",",$args);

				try {
					$funcsth = $dbh->prepare('SELECT `name`,`content`
						FROM functions
						WHERE `name` = ? OR `name` LIKE ? OR `name` LIKE ?');
					$funcsth->bindValue(1, $name, PDO::PARAM_STR);
					$funcsth->bindValue(2, $name.'(%', PDO::PARAM_STR);
					$funcsth->bindValue(3, $name.' (%', PDO::PARAM_STR);
					$funcsth->execute();
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}

				$count = $funcsth->rowCount();
				if($count == 1) { // finds function
					$funcrow = $funcsth->fetch(PDO::FETCH_OBJ);
					
					$funcName = $funcrow->name;
					$funcCode = $funcrow->content;

					if(preg_match('/\((.*?)\)/',$funcName,$params)) { // if there are arguments
						$params = explode(",",substr($params[0],1,-1)); // list of args not including brackets
						foreach ($params as $num => $param) {
							if(isset($arg[$num])) ${$param} = $arg[$num];
						}
					}

					eval($funcCode);
				}
				else { // function not found/more than one function with that name found
					echo "<p>There was an error parsing the functon: $function </p>";
				}

				if($markdown) write($text);
				else echo $text;
			}
		}
	}

	function outputCouncillor($id) { // show councillors details - optimised for use in <aside>
		global $dbh;

		try {
			// look up councillor
			$sth = $dbh->prepare("	SELECT 	councillors.name, councillors.shortname, councillors.email, councillors.bio, 
											councillors.image, councillors.active, councillors_roles.rolename 
									FROM councillors, councillors_roles
									WHERE councillors.idcouncillors =  ?
									AND councillors.role = councillors_roles.idroles LIMIT 1");
			$sth->bindValue(1, $id, PDO::PARAM_STR);
			$sth->execute();

			$count = $sth->rowCount();

			if($count) { // found one
				$councillor = $sth->fetch(PDO::FETCH_OBJ);

				// display their photo and link it to their email if they have one
				if(($councillor->email != "") && ($councillor->image != "")) 
					echo '<a href="mailto:'. $councillor->email .'">';
				if($councillor->image != "") 
					echo '<img src="'. $councillor->image .'" class="thumb med" alt="'.$councillor->name.'" /></a>';
				if(($councillor->email != "") && ($councillor->image != "")) 
					echo '</a>';

				// link their name to their email if they have one
				echo '<h3>';
				if($councillor->email != "") echo '<a href="mailto:'. $councillor->email .'">';
				echo $councillor->name;
				if($councillor->email != "") echo '</a>';
				echo '</h3>';
				
				echo '<h5 class="role">'.$councillor->rolename.'</h5>';
				echo '<h6 style="clear: both">'.$councillor->shortname."'s Biography</h6><p>";
				line($councillor->bio);
				echo '<p>';
			}
			else '<p>Councillor not found</p>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	} 

	function navBar($page=0) { // output the navigation bar, with the current page highlighted if it is set
		global $dbh;

		echo '<header><div class="wrapper">';

		// logo home link
		echo '<a href="/" id="logo"><img  src="/img/site/logo.png" alt="Student Council Logo"></a>';

		try {
			$sql = 'SELECT parents.*, pages.alias 
					FROM parents, pages 
					WHERE parents.idpages = pages.idpages
					ORDER BY parents.position';
			
			$count = $dbh->query($sql)->rowCount();

			if($count) {
				echo '<nav>
				<h2>Menu</h2>
				<ul id="nav" class="sf-menu">';

				foreach($dbh->query($sql) as $parent) {
					$id = $parent['idparents'];

					// look up to see if this parent has children
					$childsql = "SELECT nav.*, links.*, pages.alias, pages.title
								FROM nav
								LEFT JOIN links
								ON nav.idlinks = links.idlinks
								LEFT JOIN pages
								ON nav.idpages = pages.idpages
								WHERE nav.idparents = $id
								ORDER BY position";

					$children = $dbh->query($childsql)->rowCount();

					$alias = $parent['alias'];
					if($alias=='index') $alias = ''; // links to home page '/' not '/index'

					echo '<li class="';
					if(!$children) echo 'nochildren ';
					if($page == $parent['idpages']) echo 'current ';
					if($parent['special']) echo 'special-menu-item';
					echo '">
					<a href="/'.$alias.'">'.$parent['name'].'
					<span class="subheader">'.$parent['subheader'].'</span>
					</a>';

					if($children) { // has dropdown
						echo '<ul>';
						foreach($dbh->query($childsql) as $child) { // each dropdown element
							echo '<li class="';
							if(!empty($child['idpages'])) { // internal page link
								if($page == $child['idpages']) echo 'current';

								$childalias = $child['alias'];
								if($childalias=='index') $childalias = ''; // links to home page '/' not '/index'

								echo '"><a href="/'.$childalias.'">'.$child['title'].'</a>';
							}
							else { // URL link
								if($child['email']) echo 'con'; // adds email symbol
								elseif(!empty($child['idcolours'])) {
									$col = $dbh->query('SELECT class 
										FROM colours 
										WHERE idcolours = '.$child['idcolours']);
									$colcount = $col->rowCount();

									if($colcount) { // found colour with that id
										$colour = $col->fetch(PDO::FETCH_OBJ);
										echo $colour->class;
									}
								}
								echo '"><a href="';
								echo $child['email'] ? 'mailto:' : '';
								echo $child['URL'].'" target="_blank">'.$child['name'].'</a>';
							}

							echo '</li>'; // close drop down item
						}
						echo '</ul>'; // close drop down menu
					}

					echo '</li>'; // close parent
				}

				echo '</ul></nav>'; // close nav bar
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}

		echo '</div></header>';
	}

	function breadcrumbs($page,$blog=null) { // output breadcrumb links to $page (id of page)
		global $dbh;

		echo '<ul class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
		<li typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title">home</a></li>';

		try {
			$sql = "SELECT parents.name, child.alias AS childAlias, parent.alias AS parentAlias
					FROM nav -- child nav element
					LEFT JOIN parents
					ON nav.idparents=parents.idparents
					LEFT JOIN pages child -- for page alias
					ON nav.idpages=child.idpages
					LEFT JOIN pages parent -- for parent alias
					ON parents.idpages=parent.idpages
					WHERE nav.idpages = $page

					UNION -- for those only in parents table
					SELECT name, null, pages.alias
					FROM parents, pages
					WHERE parents.idpages = $page
					AND parents.idpages = pages.idpages";
			
			$result = $dbh->query($sql);
			$count = $result->rowCount();
			$breadcrumbs = $result->fetch(PDO::FETCH_OBJ);

			echo ' / <li typeof="v:Breadcrumb">';
			if($count == 1) { // the page has a unique location in the nav bar
				echo '<a href="/'.$breadcrumbs->parentAlias.'" rel="v:url" property="v:title">'.
				strtolower($breadcrumbs->name).'</a>';
				if($breadcrumbs->childAlias != '') { // not a parent
					echo '</li> /
					<li typeof="v:Breadcrumb">
					<a href="/'.$breadcrumbs->childAlias.'" rel="v:url" property="v:title">'.
					strtolower($breadcrumbs->childAlias).'</a>';
				}
			}
			elseif($count > 1) { // page appears in multiple dropdowns
				echo '<a href="/'.$breadcrumbs->childAlias.'" rel="v:url" property="v:title">'.
				strtolower($breadcrumbs->childAlias).'</a>';
			}
			else { // page not in nav
				$page = $dbh->query("SELECT alias FROM pages WHERE idpages = $page LIMIT 1")->fetch(PDO::FETCH_OBJ);
				echo '<a href="/'.$page->alias.'" rel="v:url" property="v:title">'.
				strtolower($page->alias).'</a>';
			}
			echo '</li>';
			if($blog) {
				echo ' / <li typeof="v:Breadcrumb">
				<a href="/blog/'.$blog.'" rel="v:url">post</a>
				</li>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}

		echo '</ul>';
	}

	function globalContentBlock($name) {
	// outputs the global content block with the name

		global $dbh;

		try {
			$gcbsth = $dbh->prepare('SELECT `content`
				FROM gcb
				WHERE `name` = ? LIMIT 1');
			$gcbsth->bindValue(1, $name, PDO::PARAM_STR);
			$gcbsth->execute();

			$gcb = $gcbsth->fetch(PDO::FETCH_OBJ);
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}

		$count = $gcbsth->rowCount();
		if($count) outputContent($gcb->content,false); // allow functions in GCBs
		else echo "The global content block, '$name', was not found";
	}

	function currentYear() {
		global $dbh;
		try {
			return $dbh->query('SELECT year 
				FROM settings 
				WHERE year = (SELECT MAX(year) 
				FROM settings)
				LIMIT 1')->fetch(PDO::FETCH_OBJ)->year;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function currentPhoto() {
		global $dbh;
		try {
			return $dbh->query('SELECT image 
				FROM settings 
				WHERE year = '.currentYear().'
				LIMIT 1')->fetch(PDO::FETCH_OBJ)->image;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function currentHead() {
		global $dbh;
		try {
			return $dbh->query('SELECT specialhead 
				FROM settings 
				WHERE year = (SELECT MAX(year) 
				FROM settings)
				LIMIT 1')->fetch(PDO::FETCH_OBJ)->specialhead;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function outputBlog($page=1) { // output list of posts | page indexed to 1: page 1 is first page
		global $dbh;

		if(is_numeric($page) && $page > 0) {
			$page--;
			try {
				$sql = "SELECT blog.*, councillors.name, councillors.image AS profile, councillors_roles.rolename
						FROM blog
						LEFT JOIN councillors ON blog.assoc_councillor = councillors.idcouncillors
						LEFT JOIN councillors_roles ON councillors_roles.idroles = councillors.role
						WHERE blog.active
						AND blog.date < NOW() -- has been posted
						OR (NOT blog.updated OR blog.updated < NOW()) -- not updated or updated passed
						ORDER BY (CASE WHEN blog.updated IS NULL THEN blog.date ELSE blog.updated END) DESC
						LIMIT 5";
				if($page) $sql .= " OFFSET ".($page*5);
				if($dbh->query($sql)->rowCount()) { // there are blog posts to show
					foreach($dbh->query($sql) as $row) {
						echo '<div class="post">
						<h3>'.date('j M y',strtotime($row['updated'] ? $row['updated'] : $row['date'])).'</h3>
						<h2><a href="/blog/'.$row['alias'].'">'.$row['title'].'</a></h2>';
						if($row['image']) {
							echo '<a href="/blog/'.$row['alias'].'" class="image"><img src="'.$row['image'].'" /></a>';
						}
						if($row['name']) {
							echo '<img src="'.$row['profile'].'" class="thumb med" />
							<h3>'.$row['name'].'</h3>
							<h5>'.$row['rolename'].'</h5>
							<div class="clearfix"></div>';
						}
						echo '<p>'.
						htmlentities($row['desc'] ? $row['desc'] : 
						(strlen($row['content']) > 200 ? substr($row['content'],0,200).'...' : $row['content'])).
						'</p>
						<p class="right"><b><a href="/blog/'.$row['alias'].'" class="more">Read more &#187;</a></b></p>
						</div>';
					}

					$total = $dbh->query("SELECT count(*) AS total 
										FROM blog 
										WHERE blog.active 
										AND blog.date < NOW()
										OR (NOT blog.updated OR blog.updated < NOW())")->fetch(PDO::FETCH_OBJ)->total;

					if($total > 5) { // more than five posts
						echo '<p class="page-nav center">';
						if($page) { // not first page
							echo '<a href="/blog/page/'.($page).'">&#171; Previous</a> ';
						}
						for ($i=1; $i <= ceil($total / 5) ; $i++) { 
							echo '<a href="/blog/page/'.$i.'">'.$i.'</a> ';
						}
						if(($page+1) < ceil($total / 5)) { // not last page
							echo '<a href="/blog/page/'.($page+2).'">Next &#187;</a> ';
						}
						echo '</p>';
					}

					if(!$page) { // output on first page
						echo '<!-- load in more posts on scroll down -->
<script type="text/javascript">
	$(document).ready(function () {

		var page = 2;
		var waiting = false;

		if($(window).width() > 1000) {
			$(".page-nav").hide();

			$(window).scroll(function (event) {
				if($(window).scrollTop() > ($(document).height()-$("footer").height()-$(window).height())) {
					if(!waiting) loadPage();
				}
			});
		}

		function loadPage() {
			waiting = true;
			$.ajax({
				type: "GET",
				url: "/js/blog.php",
				data: { blog: page }
			}).done(function(msg) {
				if(msg != "<p>There currently aren\'t any blog posts</p>") {
					$(".page-content").append(msg); // add posts to the bottom of page
					$(".page-nav").hide(); // remove new page nav
					page++;
					waiting = false;
				}
			});
		}
	});
</script>';
					}
				}
				else echo "<p>There currently aren't any blog posts</p>";
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
		else {
			echo '<p class="error">The requested page was not a number</p>';
		}
	}

	function sendMessage($name,$email,$counc,$subject,$message) { // send message from contact form
		global $dbh, $recaptcha_secret;

		if(!empty($message) && strlen($message)<5000) { // message not blank
			if(strlen($name)<=60 && strlen($email)<=100 && strlen($subject)<=60) {
				$looksth = $dbh->prepare("SELECT shortname, email, COUNT(*) AS count 
										FROM councillors 
										WHERE idcouncillors = :counc");
				$looksth->bindValue(':counc',$counc, PDO::PARAM_INT);
				$looksth->execute();

				$result = $looksth->fetch(PDO::FETCH_OBJ);

				if($result->count || $counc == 0) { // councillor they selected exists
					$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".
						$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
					$response = json_decode($response, true);
					if($response["success"] === true) { // not a bot
						$to = ($counc ? $result->shortname : 'The Council').
							' <'.($counc ? $result->email : 'studentcouncil14@hillsroad.ac.uk').'>';
						$sub = 'New message for '.($counc ? $result->shortname : 'the council');

						$content = '
						<html>
						<head>
							<title>'.$subject.'</title>
						</head>
						<body>
							<p>Hi '.($counc ? $result->shortname : 'council').'</p>

							<p>A person with the name "'.$name.'" and email "'.$email.'" sent 
							<a href="http://myhrsfc.co.uk/admin/message.php">this message</a>:</p>

							<p><b>Subject: '.$subject.'</b></p>
							<p>'.nl2br($message).'</p>
						</body>
						</html>
						';

						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

						// send email
						mail($to, $sub, $content, $headers);

						// store in db
						$sth = $dbh->prepare("INSERT INTO contact VALUES (null,:name,:email,:counc,:subject,:message,0)");
						$sth->bindValue(':name',$name, PDO::PARAM_STR);
						$sth->bindValue(':email',$email, PDO::PARAM_STR);
						$sth->bindValue(':counc',$counc, PDO::PARAM_INT);
						$sth->bindValue(':subject',$subject, PDO::PARAM_STR);
						$sth->bindValue(':message',$message, PDO::PARAM_STR);
						$sth->execute();

						if($sth->rowCount()) { // went into db successfully
							echo '<p class="success">Message successfully sent :) Thanks for getting in touch</p>';
							return true;
						}
						else {
							echo '<p class="error">There was an internal error, please try again</p>';
							return false;
						}
					}
					else {
						echo '<p class="error">We think you\'re a bot, please try again</p>';
						return false;
					}
				}
				else {
					echo '<p class="error">The councillor you selected does not exist, please try again</p>';
					return false;
				}
			}
			else {
				echo '<p class="error">Your name must be less than 60 characters, the email less than 100
				and the subject less than 60, please try again</p>';
				return false;
			}
		}
		else {
			echo '<p class="error">The message you sent was blank or was greater than 5000 characters, 
			please try again</p>';
			return false;
		}
	}
?>