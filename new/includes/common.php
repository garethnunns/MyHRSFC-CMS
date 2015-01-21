<?php
	include 'includes/parsedown.php';
	include 'includes/secure.php';

	$parsedown = new Parsedown();

	function write($content) { // outputting markdown, allowing HTML tags but encoding special characters, like £, etc.
		global $parsedown;

		echo htmlspecialchars_decode(htmlentities($parsedown->text($content)));
		//echo $parsedown->text($content);
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

?>