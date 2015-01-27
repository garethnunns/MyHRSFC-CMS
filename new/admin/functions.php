<?php
	function sudoOnly($allowed) {
		if(!$allowed) {
			header('/admin');
			exit();
		}
	}

	function uploadProfilePic($picture,$id) {
		global $dbh, $sudo, $user;
		if(($sudo) || ($user == $id)) {

			$toUpload = true;
			$relFolder = '/img/profiles/';
			$picturesFolder = '..'.$relFolder;
			$pictureExt = end((explode(".", $picture["name"])));


			if(!file_exists($picturesFolder)) { // check to see if folder exists
				if (!mkdir($picturesFolder, 0777, true)) { // tries to create folder
					die('<p class="error">Failed to create the folder to store the photo in</p>');
					$toUpload = false;
				}
			}

			if($picture["size"] > 625000) { // check to see if > 5MB
				echo '<p class="error">Sorry, the uploaded file is larger than 5MB</p>';
				$toUpload = false;
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
				$newName = 'councillors'.$id.'.'.$pictureExt;
				if(move_uploaded_file($picture['tmp_name'], $picturesFolder.$newName)) {
					echo $newName;
					try {
						$picsth = $dbh->prepare("UPDATE councillors 
							SET image = :imgpath
							WHERE idcouncillors = :id");
						$picsth->bindValue(':imgpath',$relFolder.$newName, PDO::PARAM_STR);
						$picsth->bindValue(':id',$id, PDO::PARAM_INT);
						$picsth->execute();
					}
					catch (PDOException $e) {
						echo $e->getMessage();
					}
				}
				else echo '<p class="error">Sorry, there was an error uploading your photo, please try again</p>';
			}
		}
		else echo '<p class="error">You don\'t have permission to edit that profile picture</p>';
	}

	function cryptPassword ($password) {
		$cost = 10;
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		$hash = crypt($password, $salt);
	}

	function roleSelect($id) {
		global $dbh;

		$rolesql = "SELECT * FROM councillors_roles ORDER BY rolename";
		foreach($dbh->query($rolesql) as $role) {
			echo '<option value="'.$role["idroles"].'" ';
			if($id == $role["idroles"]) echo 'selected';
			echo '>'.$role["rolename"].'</option>';
		}
	}

	function tutorSelect($tutor) {
		global $dbh;

		$tutsql = "SELECT * FROM tutors ORDER BY name";
		foreach($dbh->query($tutsql) as $tut) {
			echo '<option value="'.$tut["initials"].'" ';
			if($tutor == $tut["initials"]) echo 'selected';
			echo '>'.$tut["name"].'</option>';
		}
	}		
?>