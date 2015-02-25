<?php
	include 'checklogin.php';

	$field = strtolower($_POST['field']);
	$allowed = array('society','name','email','desc');

	if(in_array($field,$allowed)) {
		if (($field == 'society' && validString('society name',$_POST['value'])) ||
			($field == 'name' && validString('society leader name',$_POST['value'])) ||
			($field == 'email' && validString('society leader email',$_POST['value'])) ||
			($field == 'desc' && validString('society description',$_POST['value']))) {

			try {
				$sth = $dbh->prepare("UPDATE societies 
					SET `$field` = :value 
					WHERE idsocieties = :id");
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