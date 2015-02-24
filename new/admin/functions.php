<?php
	function sudoOnly($allowed) {
		if(!$allowed) {
			header('Location: /admin');
			exit();
		}
	}

	function uploadProfilePic($picture,$id) { // compresses and stores image then updates database
		global $dbh, $sudo, $user;

		if(!empty($picture["name"])) {

			if(($sudo) || ($user == $id)) {

				$toUpload = true;
				$absFolder = '/img/profiles/';
				$relFolder = '..'.$absFolder;
				$pictureExt = end((explode(".", $picture["name"])));


				if(!file_exists($relFolder)) { // check to see if folder exists
					if (!mkdir($relFolder, 0777, true)) { // tries to create folder
						die('<p class="error">Failed to create the folder to store the photo in</p>');
						$toUpload = false;
					}
				}

				$allowedType = array("jpg","jpeg","png","gif");
				if(!in_array(strtolower($pictureExt),$allowedType)) { // check to see if allowed file type
					echo '<p class="error">Sorry, only ';
					foreach ($allowedType as $ext) {
						echo '.'.$ext.', ';
					}
					echo 'are allowed</p>';
					$toUpload = false;
				}

				if($toUpload) {
					$newName = 'councillor'.$id.'.'.$pictureExt;
					if(file_exists($relFolder.$newName)) unlink($relFolder.$newName);
					require_once dirname(__FILE__).'/../includes/simpleimage.php';
					$image = new SimpleImage();
					$image->load($picture['tmp_name']);
					$image->resizeToWidth(100);
					if($image->getHeight() <= 150) { // no greater than 2:3
						$image->save($relFolder.$newName);

						try {
							$picsth = $dbh->prepare("UPDATE councillors 
								SET image = :imgpath
								WHERE idcouncillors = :id");
							$picsth->bindValue(':imgpath',$absFolder.$newName, PDO::PARAM_STR);
							$picsth->bindValue(':id',$id, PDO::PARAM_INT);
							$picsth->execute();

							echo '<p class="success">
							The <a href="'.$absFolder.$newName.'" target="_blank">image</a> was successfully uploaded, 
							<i>large files may take a moment to process</i></p>';
						}
						catch (PDOException $e) {
							echo $e->getMessage();
						}
					}
					else echo '<p class="error">Sorry, the image you uploaded was too tall</p>';
				}
			}
			else echo '<p class="error">You don\'t have permission to edit that profile picture</p>';
		}
	}

	function cryptPassword($password) { // crypts password
		$cost = 10;
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		return crypt($password, $salt);
	}

	function pageExists($pageid) { // checks to see if a page with that ID exists and returns the alias if it does
		global $dbh;
		try {
			$sql = 'SELECT alias
					FROM pages
					WHERE idpages = :pageid';

			$sth = $dbh->prepare($sql);
			$sth->bindValue(':pageid',$pageid, PDO::PARAM_INT);
			$sth->execute();

			$count = $sth->rowCount();

			if($count) {
				$result = $sth->fetch(PDO::FETCH_OBJ);
				return $result->alias;
			}
			else {
				echo '<p class="error">There is no page in the database with the ID '.$pageid.'</p>';
				return false;
			}
		}
		catch (PDOException $e) {
			return false;
			echo $e->getMessage();
		}
	}

	function validAlias($alias) {
		if(validString('page alias',$alias)) {
			if(ctype_alnum($alias)) return true;
			else {
				echo '<p class="error">The page alias can only be letter and numbers</p>';
				return false;
			}
		}
	}

	function validString($name,$string) { // basic validation: checking inputted string's length is within bounds
		$default = array(60,0); // max, min

		// field valid length parameters
		// in the form $fields['field name'] = array(max length, min length (optional))
		// setting min length to -1 allows it to be blank
		$fields['A to Z name'] = array(60);
		$fields['A to Z description'] = array(2000);
		$fields['councillor name'] = array(50);
		$fields['councillor shortname'] = array(30);
		$fields['councillor email'] = array(100);
		$fields['councillor password'] = array(150);
		$fields['councillor subjects'] = array(150,-1);
		$fields['councillor bio'] = array(1000,-1);
		$fields['FAQ question'] = array(100);
		$fields['FAQ answer'] = array(2500);
		$fields['function name'] = array(60);
		$fields['function description'] = array(750);
		$fields['function content'] = array(10000); // almost limitless
		$fields['GCB name'] = array(45);
		$fields['GCB content'] = array(5000);
		$fields['link name'] = array(60);
		$fields['link URL'] = array(100);
		$fields['page alias'] = array(45);
		$fields['page title'] = array(60,-1);
		$fields['page subtitle'] = array(45,-1);
		$fields['page meta title'] = array(100,-1);
		$fields['page special head'] = array(5000,-1);
		$fields['page content'] = array(10000);
		$fields['page sidebar'] = array(5000,-1);
		$fields['page description'] = array(200,-1);
		$fields['page social image'] = array(80,-1);
		$fields['parent name'] = array(15);
		$fields['parent subheader'] = array(45);
		$fields['rolename'] = array(60);
		$fields['tutor name'] = array(60);

		if(isset($fields[$name])) { // look up field name
			$length = $fields[$name][0];
			if(isset($fields[$name][1])) $min = $fields[$name][1];
			else $min = $default[1]; // if not defined set to default
		}
		else { // field name not defined - use defaults
			$length = $default[0];
			$min = $default[1];
		}

		$string = trim($string);
		$strlen = strlen($string);
		if($strlen > $length) {
			echo '<p class="error">The '.$name.' must be less than '.$length.' characters long</p>';
			return false;
		}
		elseif($strlen==0 && $min==0) {
			echo '<p class="error">The '.$name.' can not be left blank</p>';
			return false;
		}
		elseif($strlen < $min) {
			echo '<p class="error">The '.$name.' must be greater than '.$min.' characters long</p>';
			return false;
		}
		else {
			return true;
		}
	}

	function validInitials($initials,$current=null) { // checks to see if 3 letters and doesn't already exist
		if(trim($initials) != "") { // not blank
			if((strlen(trim($initials)) == 3) && ctype_alpha($initials)) { // 3 letters
				$initials = strtoupper($initials);
				$current = strtoupper($current);

				global $dbh;

				$sql = 'SELECT name
					FROM tutors
					WHERE initials = :initials';
				if(!is_null($current) && $initials==$current) $sql .= ' LIMIT 1, 1'; // if the tutor is currently in the table

				$sth = $dbh->prepare($sql);
				$sth->bindValue(':initials',$initials, PDO::PARAM_STR);
				$sth->execute();

				$count = $sth->rowCount();

				if(!$count) { 
					return true;
				}
				else { // a tutor with these initals already exists
					$result = $sth->fetch(PDO::FETCH_OBJ);
					echo '<p class="error">A tutor ('.$result->name.'), with those initials ('.$initials.') already exists</p>';
					return false;
				}
			}
			else {
				echo '<p class="error">The initials must be 3 letters, containing no numbers or symbols</p>';
				return false;
			}
		}
		else {
			echo '<p class="error">The initials can not be blank</p>';
			return false;
		}
	}

	function validEmail($email,$id=null) { // check to see if it is an email and if no other user has the same
		global $dbh;

		if(!validString('councillor email',$email)) {
			return false;
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { // invalid email
			echo '<p class="error">Invalid email address</p>';
			return false;
		}
		else {
			try { // see if there is already a user with that email address
				$sql = 'SELECT name
					FROM councillors
					WHERE email = :email';
				if(!is_null($id)) $sql .= ' AND idcouncillors <> :id'; // not updating their own to be the same
				$sth = $dbh->prepare($sql);
				$sth->bindValue(':email',$email, PDO::PARAM_STR);
				if(!is_null($id)) $sth->bindValue(':id',$id, PDO::PARAM_INT);
				$sth->execute();

				$count = $sth->rowCount();

				if($count) { // email not unique
					$result = $sth->fetch(PDO::FETCH_OBJ);
					echo '<p class="error">"'.$result->name.'" has already used this email address</p>';
					return false;
				}
				else return true;
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	function getIDfromEmail($email) { // return id of councillors from email address
		global $dbh;
		try { 
			$sth = $dbh->prepare('SELECT idcouncillors
				FROM councillors
				WHERE email = :email');
			$sth->bindValue(':email',$email, PDO::PARAM_STR);
			$sth->execute();

			$count = $sth->rowCount();

			if($count) { // found councillor with that email
				$result = $sth->fetch(PDO::FETCH_OBJ);
				return $result->idcouncillors;
			}
			else return false;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
	}

	function addTutor($initials,$name) {
		global $sudo;
		if($sudo) {
			if(validInitials($initials) && validString('tutor name',$name)) { // valid inputs
				$initials = strtoupper($initials);

				global $dbh;

				try {
					// see if that tutor already exists
					$lookupsth = $dbh->prepare('SELECT initials
						FROM tutors
						WHERE initials LIKE ?');
					$lookupsth->bindValue(1,'%'.$initials.'%', PDO::PARAM_STR);
					$lookupsth->execute();

					$count = $lookupsth->rowCount();

					if(!$count) { // doesn't already exist
						$sth = $dbh->prepare("INSERT INTO tutors(initials,name) 
							VALUES (:initials,:name)");
						$sth->bindValue(':initials',$initials, PDO::PARAM_STR);
						$sth->bindValue(':name',$name, PDO::PARAM_STR);
						$sth->execute();
					}
					else echo '<p class="error">A tutor with those initials already, please try again</p>';
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
		else echo '<p class="error">You don\'t have permission to add tutors</p>';
	}

	function addRole($role) {
		global $sudo;
		if($sudo) {
			if(validString('rolename',$role)) { // valid role
				global $dbh;

				try {
					$sth = $dbh->prepare("INSERT INTO councillors_roles (rolename) 
						VALUES (:role)");
					$sth->bindValue(':role',$role, PDO::PARAM_STR);
					$sth->execute();
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
		else echo '<p class="error">You don\'t have permission to add roles</p>';
	}

	function isSpecial($alias) { // is a special page that can't be edited or url that can't be set
		$special = array('index','404','icons','profiles','site','year'); // ones that are special

		$rootfull = glob(dirname(__FILE__).'/../*' , GLOB_ONLYDIR); // directories in /
		foreach ($rootfull as $path) {
			$root[] = basename($path);
		}

		$notallowed = array_merge($special,$root);
		return in_array($alias,$notallowed);
	}

	function roleSelect($id) {
		// outputs list of <option>s for <select> for choosing role of a councillor
		// in the form <option value="role id">Name of role</option>

		global $dbh;

		try {
			$rolesql = "SELECT * FROM councillors_roles ORDER BY rolename";
			foreach($dbh->query($rolesql) as $role) {
				echo '<option value="'.$role["idroles"].'" ';
				if($id == $role["idroles"]) echo 'selected';
				echo '>'.$role["rolename"].'</option>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function tutorSelect($tutor) { // list of tutor <option>s for <select>
		global $dbh;

		try {
			$tutsql = "SELECT * FROM tutors ORDER BY name";
			foreach($dbh->query($tutsql) as $tut) {
				echo '<option value="'.$tut["initials"].'" title="'.$tut["initials"].'" ';
				if($tutor == $tut["initials"]) echo 'selected';
				echo '>'.htmlentities($tut["name"]).'</option>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function councSelect($counc) { // list of all councillor <option>s for <select>
		global $dbh;

		try {
			$councsql = "SELECT * FROM councillors ORDER BY active DESC, name";
			foreach($dbh->query($councsql) as $councillor) {
				echo '<option value="'.$councillor["idcouncillors"].'" ';
				if($counc == $councillor["idcouncillors"]) echo 'selected';
				echo '>'.htmlentities($councillor["name"]).'</option>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function colourSelect($col) { // outputs list of colour <option>s for <select>
		global $dbh;

		try {
			foreach($dbh->query('SELECT * FROM colours ORDER BY name') as $colour) {
				echo '<option value="'.$colour["idcolours"].'" title="CSS class: .'.$colour["class"].'" ';
				if($col == $colour["idcolours"]) echo 'selected';
				echo '>'.htmlentities($colour["name"]).'</option>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function isColour($col) { // check if there is a colour with that id in the colours table
		global $dbh;

		$sth = $dbh->prepare("SELECT COUNT(*) AS count FROM colours WHERE idcolours = :colour");
		$sth->bindValue(':colour',$col, PDO::PARAM_INT);
		$sth->execute();

		$result = $sth->fetch(PDO::FETCH_OBJ);

		if($result->count || $col == 0) return true; // finds colour with that id
		else { // doesn't find colour with that id
			echo '<p class="error">The colour you selected does not exist</p>';
			return false;
		}
	}
?>