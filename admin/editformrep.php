<?php
	include 'checklogin.php';

	$field = $_POST['field'];
	$allowed = array('tutor','upper','rep1','rep2');

	if(in_array($field,$allowed)) {
		if (validString('form '.$field,$_POST['value'])) {

			try {
				$sth = $dbh->prepare("UPDATE form_reps 
					SET $field = :value 
					WHERE idform_reps = :id
					LIMIT 1");
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