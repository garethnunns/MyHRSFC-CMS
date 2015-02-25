<?php
	include 'checklogin.php';

	$field = strtolower($_POST['field']);
	$allowed = array('name','desc','progress');
	if($sudo) array_push($allowed, 'assoc_councillor');

	if(in_array($field,$allowed)) {
		if (validString('policy '.$field,$_POST['value'])
			&& ($field != 'progress' || ($field == 'progress' && validProgress($_POST['value'])))) {

			try {
				$sth = $dbh->prepare("UPDATE policies 
					SET `$field` = :value 
					WHERE idpolicies = :id
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
?>