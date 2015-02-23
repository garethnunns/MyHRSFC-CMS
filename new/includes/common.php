<?php

	require_once dirname(__FILE__).'/secure.php';
	require_once dirname(__FILE__).'/parsedown.php';

	$parsedown = new Parsedown();

	function write($content) {
	// outputting markdown, allowing HTML tags but encoding special characters, like £, etc.
		global $parsedown;

		echo htmlspecialchars_decode(htmlentities($parsedown->text($content)));
	}

	function line($content) {
	// outputting markdown, allowing only inline HTML tags but encoding special characters, like £, etc.
		global $parsedown;

		echo htmlspecialchars_decode(htmlentities($parsedown->line($content)));
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
				if($count == 1) {
					$funcrow = $funcsth->fetch(PDO::FETCH_OBJ);
					
					$funcName = $funcrow->name;
					$funcCode = $funcrow->content;

					if(preg_match('/\((.*?)\)/',$funcName,$params)) { // if there are arguments
						$params = explode(",",substr($params[0],1,-1));
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

	function breadcrumbs($page) { // output breadcrumb links to $page (id of page)
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
		if($count) echo $gcb->content;
		else echo "The global content block, '$name', was not found";
	}

?>