<?php
	function sudoOnly($allowed) {
		if(!$allowed) {
			header('Location: /admin');
			exit();
		}
	}

	function uploadProfilePic($picture,$id) { // compresses and stores image then updates database
		global $dbh, $sudo, $user;
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

	function cryptPassword($password) { // crypts password
		$cost = 10;
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		return crypt($password, $salt);
	}

	function validEmail($email,$id=null) { // check to see if it is an email and if no other user has the same
		global $dbh;

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { // invalid email
			echo '<p class="error">Invalid email address</p>';
			return false;
		}
		else {
			try { // see if there is already a user with that email address
				$sql = 'SELECT name
					FROM councillors
					WHERE email = :email';
				if(!is_null($id)) $sql .= ' AND idcouncillors <> :id';
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

	function getIDfromEmail($email) { // get idcouncillors from email address
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
		}
	}

	function addTutor($initials,$name) {
		global $sudo;
		if($sudo) {
			if(trim($initials) != "" && trim($name) != "") { // not blank
				if(strlen(trim($initials)) == 3) {
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
				else echo '<p class="error">There must be three letters in the intials</p>';
			}
			else echo '<p class="error">One of the fields for adding a tutor was left blank, please try again</p>';
		}
		else echo '<p class="error">You don\'t have permission to add tutors</p>';
	}

	function addRole($role) {
		global $sudo;
		if($sudo) {
			if(trim($role) != "") { // role not blank
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
			else echo '<p class="error">The role name can not be blank, please try again</p>';
		}
		else echo '<p class="error">You don\'t have permission to add roles</p>';
	}

	function isSpecial($alias) {
		$special = array('index','404');
		return in_array($alias,$special);
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

	function tutorSelect($tutor) {
		global $dbh;

		try {
			$tutsql = "SELECT * FROM tutors ORDER BY name";
			foreach($dbh->query($tutsql) as $tut) {
				echo '<option value="'.$tut["initials"].'" ';
				if($tutor == $tut["initials"]) echo 'selected';
				echo '>'.$tut["name"].'</option>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function councSelect($counc) {
		global $dbh;

		try {
			$councsql = "SELECT * FROM councillors ORDER BY name";
			foreach($dbh->query($councsql) as $councillor) {
				echo '<option value="'.$councillor["idcouncillors"].'" ';
				if($counc == $councillor["idcouncillors"]) echo 'selected';
				echo '>'.$councillor["name"].'</option>';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}	
?>