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

	function navBar() {
		require_once dirname(__FILE__).'/header.php';
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
		else echo "Function '$name' not found";
	}

?>