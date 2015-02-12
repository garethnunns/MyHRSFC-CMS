<?php
	include 'checklogin.php';

	if(($sudo) || ($_POST['id'] == $user)) {
		$field = strtolower($_POST['field']);

		$allowed = array('name','shortname','email','tutor','subjects','bio');
		if($sudo) array_push($allowed, 'active','sudo','role');

		if(in_array($field,$allowed)) {
			if($field != 'email' || ($field == 'email' && validEmail($_POST['value'],$_POST['id'])))  {
				try {
					$sth = $dbh->prepare("UPDATE councillors 
						SET $field = :value 
						WHERE idcouncillors = :id");
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
		else echo '<p class="error">Permission denied to edit this parameter</p>';
	}
	else echo '<p class="error">Permission denied to edit this user</p>';
?>