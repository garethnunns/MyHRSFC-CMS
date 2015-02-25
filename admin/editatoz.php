<?php
	include 'checklogin.php';

	$field = strtolower($_POST['field']);
	$allowed = array('name','desc');

	if(in_array($field,$allowed)) {
		if (($field == 'name' && validString('A to Z name',$_POST['value'])) ||
			($field == 'desc' && validString('A to Z description',$_POST['value']))) {

			try {
				$sth = $dbh->prepare("UPDATE AtoZ 
					SET `$field` = :value 
					WHERE idAtoZ = :id");
				$sth->bindValue(':id',$_POST['id'], PDO::PARAM_INT);
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