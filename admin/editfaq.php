<?php
	include 'checklogin.php';

	$field = strtolower($_POST['field']);
	$allowed = array('question','answer');

	if(in_array($field,$allowed)) {
		if (validString('FAQ '.$field,$_POST['value'])) {

			try {
				$sth = $dbh->prepare("UPDATE faqs 
					SET $field = :value 
					WHERE idfaqs = :id");
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