<?php
	include 'checklogin.php';
	if($sudo) {
		if(isset($_POST['nav'])) {
			try {
				foreach ($_POST['nav'][0] as $parpos => $parent) {
					$sql = 'UPDATE parents
							SET position=:position
							WHERE idpages=:parentpage';
					$sth = $dbh->prepare($sql);
					$sth->bindValue(':parentpage',$parent['name'], PDO::PARAM_INT);
					$sth->bindValue(':position',$parpos, PDO::PARAM_INT);
					$sth->execute();
					$newParents[]=$parent['id']; // populate list of new parents with idparents

					// delete all that parent's children
					$delsql = 'DELETE FROM nav 
								WHERE idparents = :parentid';
					$delsth = $dbh->prepare($delsql);
					$delsth->bindValue(':parentid',$parent['id'], PDO::PARAM_INT);
					$delsth->execute();

					if(isset($parent['children'])) { // parent has children
						foreach ($parent['children'][0] as $childpos => $child) {
							$childsql = 'INSERT INTO nav
										VALUES (null,:idparents,:idpages,:idlinks,:position)';
							$childsth = $dbh->prepare($childsql);
							$childsth->bindValue(':idparents',$parent['id'], PDO::PARAM_INT);
							if($child['name'] == 'page') { // child is a page
								$childsth->bindValue(':idpages',$child['id'], PDO::PARAM_INT);
								$childsth->bindValue(':idlinks',null, PDO::PARAM_INT);
							}
							elseif($child['name'] == 'link') { // child is link
								$childsth->bindValue(':idpages',null, PDO::PARAM_INT);
								$childsth->bindValue(':idlinks',$child['id'], PDO::PARAM_INT);
							}
							else {
								echo '<p class="error">Invalid child type encountered</p>';
								exit();
							}
							$childsth->bindValue(':position',$childpos, PDO::PARAM_INT);
							$childsth->execute();
						}
					}
				}

				foreach($dbh->query('SELECT idparents FROM parents ORDER BY position') as $dbParent) {
					$curParents[]=$dbParent['idparents']; // fill array of current parents
				}

				foreach($curParents as $current) {
					if(!in_array($current, $newParents)) { // delete any old parents that aren't in the new parents
						$sql = 'DELETE from parents
						WHERE idparents=:parentpage';
						$sth = $dbh->prepare($sql);
						$sth->bindValue(':parentpage',$current, PDO::PARAM_INT);
						$sth->execute();
					}
				}
				echo '<p class="success">All changes saved</p>';
			}
			catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
		else { // someone goes to page directly in their browser without sending data or AJAX error
			echo '<p class="error">An error occurred and the navigation could not be updated</p>';
		}
	}
?>