<?php
	include 'checklogin.php';

	if($sudo) {
		$field = $_POST['field'];
		$allowed = array('name','URL','email');

		if(in_array($field,$allowed)) {
			if (($field == 'name' && validString('link name',$_POST['value'])) ||
				($field == 'URL' && validString('link URL',$_POST['value'])) ||
				($field == 'email' && ($_POST['value'] == 0 || $_POST['value'] == 1))) {

				try {
					$sth = $dbh->prepare("UPDATE links 
						SET $field = :value 
						WHERE idlinks = :id
						LIMIT 1");
					$sth->bindValue(':id',$_POST['id'], PDO::PARAM_STR);
					$sth->bindValue(':value',$_POST['value'], PDO::PARAM_STR);
					$sth->execute();

					echo 'Success';
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
		else echo '<p class="error">The parameter you\'re trying to edit doesn\'t exist</p>';
	}
	else echo '<p class="error">You don\'t have permission to edit tutors</p>';
?>