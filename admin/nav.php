<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Manage Navigation | MyHRSFC Admin</title>

		<?php globalContentBlock('head'); ?>
		
	</head>
	
	<body lang="en" ontouchstart="">

		<?php navbar(); ?>

		<!-- MAIN -->
		<div id="main">
				
			<?php globalContentBlock('social'); globalContentBlock('sidewriting'); ?>
			
			<!-- Content -->
			<div id="content">
			
				<!-- masthead -->
				<div id="masthead">
					<span class="head"><a href="/admin">Admin</a></span><span class="subhead">Manage Navigation</span>
					<ul class="breadcrumbs">
						<li><a href="/admin/logout.php">logout</a></li>
					</ul>
				</div>
				<!-- ENDS masthead -->
				
				
				
				<!-- page content -->
				<div class="page-content hasaside">	        	
					
					<h1>Manage Navigation</h1>
<?php
	try {
		if(isset($_POST['add'])) { // adding parent item
			if(validString('parent name',$_POST['name']) // validate name
			&& validString('parent subheader',$_POST['subheader']) // validate subheader
			&& pageExists($_POST['page'])) { // check page id relates to a page in the database
				// check to see page isn't aready in the parents table
				$lookupsql = 'SELECT name
				FROM parents
				WHERE idpages = :pageid';

				$lookupsth = $dbh->prepare($lookupsql);
				$lookupsth->bindValue(':pageid',$_POST['page'], PDO::PARAM_INT);
				$lookupsth->execute();

				$count = $lookupsth->rowCount();

				if(!$count) {
					$sql = 'INSERT INTO parents (idpages,name,subheader,position,special)
					SELECT :pageid,:name,:subheader,MAX(position)+1,:special
					FROM parents';
					$sth = $dbh->prepare($sql);
					$sth->bindValue(':pageid',$_POST['page'], PDO::PARAM_INT);
					$sth->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
					$sth->bindValue(':subheader',$_POST['subheader'], PDO::PARAM_STR);
					$sth->bindValue(':special',($_POST['special'] ? 1 : 0), PDO::PARAM_INT);
					$sth->execute();

					echo '<p class="success">Top level link successfully added</p>';
				}
				else echo '<p class="error">That page is already a top level link</p>';
			}
		}

		// nav bar sorter
		$navresult = $dbh->query("SELECT parents.idpages as parentpage, parents.idparents, 
			parents.name, parents.position AS parentpos,
			nav.idpages, nav.idlinks, nav.position AS childpos, pages.title, links.name AS link
			FROM parents
			LEFT JOIN nav
			ON nav.idparents=parents.idparents
			LEFT JOIN pages
			ON pages.idpages=nav.idpages
			LEFT JOIN links
			ON links.idlinks=nav.idlinks
			ORDER BY parentpos, childpos");
		$navcount = $navresult->rowCount();

		if($navcount) {
			echo '<ol class="sorting" id="navsort">';
			$pos = -1;
			foreach ($navresult as $nav) {
				if(($nav['parentpos'] > $pos) && ($pos>=0)) echo '</ol></li>';
				if($nav['parentpos'] > $pos) { // different parent to before
					echo '<li data-id="'.$nav['idparents'].'" data-name="'.$nav['parentpage'].'" class="parent">
					<img src="../img/icons/close.png" alt="x"/>'.$nav['name'].'<ol>';
				}
				if($nav['childpos']!='') {// child element
					echo '<li data-id="'.
					($nav['title'] ? ($nav['idpages'].'" data-name="page"') : $nav['idlinks'].'" data-name="link"').
					'"><img src="../img/icons/close.png" alt="x"/>'.($nav['title'] ? $nav['title'] : $nav['link']).'</li>';
				}

				$pos = $nav['parentpos'];
			}
			echo '</ol></ol>'; // closing main list
		}
		else {
			echo "<p>The nav bar is currently empty</p>";
		}

		echo '<input type="submit" value="Save changes &#187;" onclick="save()" />
		<div id="output"><p></p></div>';

		echo '<aside><h3>Pages</h3>';
		echo '<p><a href="pages.php">Manage pages &#187;</a></p>';
		// list of pages
		$pagesresult = $dbh->query("SELECT idpages, title, alias FROM pages ORDER BY title");
		if($pagesresult->rowCount()) {
			echo '<ol class="sorting">';
			foreach ($pagesresult as $page) {
				echo '<li data-id="'.$page['idpages'].'" data-name="page"><img src="../img/icons/close.png" alt="x"/>'.
				($page['title'] ? $page['title'] : $page['alias']).'</li>';
			}
			echo '</ol>';
		}
		else echo '<p class="error">There are no pages in the database</p>';

		echo '<h3>Links</h3>';
		echo '<p><a href="link.php">Manage links &#187;</a></p>';
		//list of links
		$linksresult = $dbh->query("SELECT idlinks, name FROM links ORDER BY name");
		if($linksresult->rowCount()) {
			echo '<ol class="sorting">';
			foreach ($linksresult as $link) {
				echo '<li data-id="'.$link['idlinks'].'" data-name="link">
				<img src="../img/icons/close.png" alt="x"/>'.$link['name'].'</li>';
			}
			echo '</ol>';
		}
		else echo '<p class="error">There are no links in the database</p>';

		echo '</aside>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>

					<h3>Add top level page link</h3>
					<form method="post">
<?php
	try {
		// output dropdown lost of pages that are not parents
		$parentresult = $dbh->query("SELECT idpages, title
			FROM pages
			WHERE idpages NOT IN
			(SELECT idpages 
			FROM parents)
			ORDER BY title");
		
		$parentcount = $parentresult->rowCount();

		if($parentcount) {
			echo '<p>Page: <select name="page">';
			foreach ($parentresult as $parent) {
				echo '<option value="'.$parent['idpages'].'">'.$parent['title'].'</option>';
			}
			echo '</select></p>';
?>
						<p>Name: <input type="text" name="name" placeholder="Main text for link" />
						<p>Sub heading: <input type="text" name="subheader" placeholder="Text below name" />
						<p>Special: <input type="checkbox" name="special" value="1" /></p>
						<p><input type="submit" name="add" value="Add &#187;" /></p>
<?php
		} // end parents
		else echo '<p class="error">No pages currently stored or they are all already top level page links</p>';
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
					</form>
				</div>
				<!-- ENDS page content -->

<style type="text/css">
	aside ol img {
		display: none; /* hide close buttons for lists of pages and links */
	}
</style>
<script src='../js/jquery-sortable.js'></script>
<script type="text/javascript">
	var oldContainer;
	$("#navsort").sortable({
		group: 'nav',
		isValidTarget: function (item, container) {
			if((item.is(".parent")) && (container.el[0] != item.parent("ol")[0])) {
				return false; // parents can not go into submenus
			}
			else if((!item.is(".parent")) && (container.el[0] == document.getElementById('navsort'))) {
				return false; // children can't become parents
			}
			else {
				return true; // other than that an element can be moved anywhere
			}
		},
		onDragStart: function ($item, container, _super) {
			oldContainer = null;
			// Duplicate pages/link dropped in from side lists so they can be added again
			if(!container.options.drop) {
				$item.clone().insertAfter($item)
				_super($item)
			}

			_super($item); // default styling changes made on drag start
		},
		afterMove: function (placeholder, container) {
			if(oldContainer != container) { // put a border round the one being dropped into
				if(oldContainer) {
					container.el.addClass("active");
					oldContainer.el.removeClass("active");
				}
			  
				oldContainer = container;
			}
		},
		onDrop: function (item, container, _super) {
			container.el.removeClass("active");

			$("#navsort img").on('click touchstart', function () { // add event listener to any new ones added
				$(this).parent().remove(); // delete the surrounding ol
				$('#output').html('<p class="error">There are unsaved changes</p>');
			});

			$('#output').html('<p class="error">There are unsaved changes</p>');

			_super(item,container); // perform default behaviour for on drop
		}
	});
	$("aside ol").sortable({ // lists of pages and links
		group: 'nav',
		drop: false
	});
	$("#navsort img").on('click touchstart', function () {
		$(this).parent().remove(); // delete the surrounding ol
		$('#output').html('<p class="error">There are unsaved changes</p>');
	});
	function save () {
		$('#output').html('<p>Updating...</p>');

		var data = $("#navsort").sortable("serialize").get(); // get sorted nav as JSON

		$.ajax({
			type: 'post',
			url: 'editnav.php',
			data: {nav: data},
		}).done(function(msg) {
			$('#output').html(msg); // give feedback to user
		});
	}
</script>
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>