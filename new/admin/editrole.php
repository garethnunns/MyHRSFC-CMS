<?php
	include 'checklogin.php';

	if($sudo) {
		if(validString('rolename',$_POST['value'])) {
			try {
				$sth = $dbh->prepare("UPDATE councillors_roles 
					SET rolename = :value 
					WHERE idroles = :id");
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
	else echo '<p class="error">You don\'t have permission to edit roles</p>';
?>