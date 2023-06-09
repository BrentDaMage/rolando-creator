<?php
include('_header.php');

// SET DEFAULT PAGE NUMBER
if(!isset($_GET['p'])) {
	$p=1;
} else {
	$p = $_GET['p'];
}

// HOW MANY ROLANDOS PER PAGE?
$per_page = 5;

// HOW MANY ROLANDOS IN THE DB
if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'hof') $rows = mysql_num_rows(mysql_query("SELECT * FROM rolando where fame=1"));
else $rows = mysql_num_rows(mysql_query("SELECT * FROM rolando"));
// FINAL PAGE NUMBER
$last_page_num = ceil($rows/$per_page);

// PAGE NUMBER ERROR HANDLING
if($p < 1) {
	$p = 1;
} else if($p > $last_page_num) {
	$p = $last_page_num;
}

// SET MYSQL LIMIT
$limit = 'limit ' .($p - 1) * $per_page .',' .$per_page;

// SORTING OPTIONS
$order = "DESC";
$sort_by = "newest";
$sort_by_sql = "created_at";
$where='';
if(isset($_GET['sort_by']))  {
	switch($_GET['sort_by']) {
		case "hof":
			$sort_by = $_GET['sort_by'];
			$sort_by_sql = "fame_sort";
			$where = "where FAME=1";
			$order = 'ASC';
			break;
		case "votes":
			$sort_by = $_GET['sort_by'];
			$sort_by_sql = "votes";
			break;
		case "name":
			$sort_by = $_GET['sort_by'];
			$sort_by_sql = "name";
			$order = "ASC";
			break;
		case "oldest": 
			$sort_by = $_GET['sort_by'];
			$sort_by_sql = "created_at";
			$order = "ASC";
			break;
		default:
			$sort_by = "newest";
			$sort_by_sql = "created_at";
			$order = "DESC";
			break;
	}
}

?>
<div id="content">
	<div class="iphone_container">
		
			<p id="view-by-list">
				<a href="view_rolandos.php?sort_by=hof" <?phpif($sort_by == "hof") echo 'class="active"'?>>Hall of Fame</a> |
				<a href="view_rolandos.php?sort_by=votes" <?phpif($sort_by == "votes") echo 'class="active"'?>>Most Popular</a> |
<?php/*				<a href="view_rolandos.php?sort_by=name" <?phpif($sort_by == "name") echo 'class="active"'?>>Alphabetically</a> | */?>
				<a href="view_rolandos.php?sort_by=newest" <?phpif($sort_by == "newest") echo 'class="active"'?>>Newest</a> |
				<a href="view_rolandos.php?sort_by=oldest" <?phpif($sort_by == "oldest") echo 'class="active"'?>>Oldest</a>
			</p>
<?php
// CHECK FOR 0 ROLANDOS IN DB
if($rows != 0) {
	// GET ROLANDOS
	$SQL = "SELECT name, token, description, bitly, votes from rolando $where order by $sort_by_sql $order $limit";
	$QUERY = mysql_query($SQL,$conn) or die(mysql_error());

	while($rolando = mysql_fetch_array($QUERY)) {
		$rolando['description'] = cleanup($rolando['description']);
		// FIND VOTE VALUE
		if($rolando['votes'] > 0)
			$vote_class = " positive";
		else if($rolando['votes'] < 0)
			$vote_class = " negative";
		else
			$vote_class = "";
			
		echo <<<END
				<div id="rolando-$rolando[token]" class="rolando-listing">
					<div class="rolando-listing-header">
						<h2>$rolando[name]</h2>
						<div class="rolando-listing-vote">
END;
		//HANDLE SHOWING ALREADY VOTED ON THUMB IMAGES
		if(isset($_COOKIE[$rolando['token']])) {
			if($_COOKIE[$rolando['token']] == 1) {
				// ROLANDO WAS VOTED UP
				echo '<img src="images/thumbs_up_inactive.png" class="btn-thumbs-up"  title="Thumb Up" alt="Thumb Up" />';

			} else {
				// ROLANDO WAS VOTED DOWN
				echo '<img src="images/thumbs_up_inactive.png" class="btn-thumbs-up"  title="Thumb Up" alt="Thumb Up" />';
			}
		} else {
			// WAS NEVER VOTED ON
			echo '<img src="images/thumbs_up.png" class="btn-thumbs-up" onclick="vote(\''.$rolando['token'].'\',1)" title="Thumb Up" alt="Thumb Up" />';
		}
		echo <<<END
						</div>
					</div>
					<div class="rolando-listing-container">
						<div class="rolando-info">
							<img class="rolando-thumbnail" src="rolandos/thumbs/$rolando[token]-small.png" title="$rolando[name]"><br />
END;
		// PLURALIZE THE WORD VOTE
		/*($rolando['votes'] == 1 || $rolando['votes'] == -1) ? $plural = '' : $plural='s';
		echo '<p><span class="votes'.$vote_class.'">'.$rolando['votes'].'</span> vote<span class="vote_pluralization">'.$plural.'</span></p>';*/
		echo <<<END
						</div>
						<div class="rolando-description">
							<p>$rolando[description]</p>
							<p><a href="rolando.php?id=$rolando[token]">Share Me</a></p>
						</div>
					</div>
				</div>
END;

	} // END WHILE
} else {
	// HANDLER FOR NO ROLANDOS IN DB
	echo <<<END
	<p>No Rolandos!</p>
END;
}
?>
		<p>Page:  of </p>
		
		<div id="bottom-nav">
<?php
		if($p > 1) {
			echo '<a href="view_rolandos.php?p='.($p-1).'&sort_by='.$sort_by.'"><img src="images/btn-prev.gif" style="float:left" /></a>';
		}
		if($p != $last_page_num) {
			echo '<a href="view_rolandos.php?p='.($p+1).'&sort_by='.$sort_by.'"><img src="images/btn-next.gif" style="float:right" /></a>';
		}
?>
		</div>
		<a href="/rolando-creator"><img src="images/create.jpg" width="320" height="72" /></a>
	</div>
</div>

<?php
include('_footer.php');
?>