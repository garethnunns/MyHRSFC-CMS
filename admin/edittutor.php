<?php
	include 'checklogin.php';

	if($sudo) {
		$initials = strtoupper($_POST['initials']);

		$field = strtolower($_POST['field']);
		$allowed = array('initials','name');

		if(in_array($field,$allowed)) {
			if (($field == 'initials' && validInitials($_POST['value'],$initials)) || 
				($field == 'name' && validString('tutor name',$_POST['value']))) {

				$lookupsth = $dbh->prepare("SELECT idcouncillors 
					FROM councillors
					WHERE tutor = :initials
					UNION
					SELECT idform_reps
					FROM form_reps
					WHERE tutor = :initials");
				$lookupsth->bindValue(':initials',$_POST['value'], PDO::PARAM_STR);
				$lookupsth->execute();

				$lookupcount = $lookupsth->rowCount();

				if(($field == 'initials' && $lookupcount) || $field=='name') {
					try {
						$sth = $dbh->prepare("UPDATE tutors 
							SET $field = :value 
							WHERE initials = :initials");
						$sth->bindValue(':initials',$initials, PDO::PARAM_STR);
						$sth->bindValue(':value',$_POST['value'], PDO::PARAM_STR);
						$sth->execute();
						echo 'Success';
					}
					catch (PDOException $e) {
						echo $e->getMessage();
					}
				}
				else echo '<p class="error">There are councillors or form reps with that tutor, 
					and so the initias were not updated</p>';
			}
		}
		else echo '<p class="error">The parameter you\'re trying to edit doesn\'t exist</p>';
	}
	else echo '<p class="error">You don\'t have permission to edit tutors</p>';
?>