<?php
	include 'checklogin.php';
	sudoOnly($sudo);
?><!doctype html>
<html lang="en-gb">

	<!-- HEADER -->
	<head>

		<title>Navigation | Hills Road Sixth Form College</title>

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
		// nav bar sorter
		$navresult = $dbh->query("SELECT parents.name, parents.position AS parentpos, nav.idpages, 
			nav.idlinks, nav.position AS childpos, pages.title, links.name AS link
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
			$pos = 0;
			foreach ($navresult as $nav) {
				if(($nav['parentpos'] > $pos) && $pos) echo '</ol></li>';
				if($nav['parentpos'] > $pos) echo '<li>'.$nav['name'].'<ol>';
				if($nav['childpos']) echo '<li>'.($nav['title'] ? $nav['title'] : $nav['link']).'</li>';
				$pos = $nav['parentpos'];
			}
			echo '</ol></ol>'; // closing main list
		}
		else {
			echo "<p>The nav bar is currently empty</p>";
		}

		echo '<aside><h3>Pages</h3>';
		$pagesresult = $dbh->query("SELECT idpages, title, alias FROM pages ORDER BY title");
		if($pagesresult->rowCount()) {
			echo '<ol class="sorting" id="pages">';
			foreach ($pagesresult as $page) {
				echo '<li>'.($page['title'] ? $page['title'] : $page['alias']).'</li>';
			}
			echo '</ol>';
		}
		else echo '<p class="error">There are no pages in the database</p>';

		echo '<h3>Links</h3>';
		$linksresult = $dbh->query("SELECT idlinks, name FROM links ORDER BY name");
		if($linksresult->rowCount()) {
			echo '<ol class="sorting" id="pages">';
			foreach ($linksresult as $link) {
				echo '<li>'.$link['name'].'</li>';
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
				</div>
				<!-- ENDS page content -->

<script src='../js/jquery-sortable.js'></script>
<script type="text/javascript">
	$(document).ready(function () {
		var oldContainer;
		$("#navsort").sortable({
			group: 'nav',
			onDragStart: function (item, container, _super) {
			    // Duplicate pages/link dropped in from lists
				if(!container.options.drop) {
					item.clone().insertAfter(item)
					_super(item)
				}
			},
			afterMove: function (placeholder, container) {
				if(oldContainer != container){
					if(oldContainer) {
						oldContainer.el.removeClass("active");
						container.el.addClass("active");
					}
				  
					oldContainer = container;
				}
			},
			onDrop: function (item, container, _super) {
				container.el.removeClass("active");
				_super(item);
			}
		});
		//$("#navsort ol").sortable({
			//drop: false
		//});
		$("#pages, #links").sortable({
			group: 'nav',
			drop: false
		})
	});
</script>
			
			</div>
			<!-- ENDS content -->
			
			<div class="clearfix"></div>
			<div class="shadow-main"></div>
		</div>
	
	<?php globalContentBlock('footer'); $dbh = null; ?>

	</body>
</html>