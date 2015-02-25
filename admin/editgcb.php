<?php
	include 'checklogin.php';

	if($sudo) {
		$field = strtolower($_POST['field']);
		$allowed = array('name','content');

		if(in_array($field,$allowed)) {
			if (validString('GCB '.$field,$_POST['value'])) {

				try {
					$sth = $dbh->prepare("UPDATE gcb 
						SET $field = :value 
						WHERE idgcb = :id
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
	else echo '<p class="error">You don\'t have permission to edit Global Content Blocks</p>';
?>