<?php
include("core.php");

$active['list'] = 'class="active"';

$page_title = "List";

include("header.php");

$winners = array();
$i = 0;
while(true){
	$i++;

	if($i == 1){
		$sql = "SELECT DISTINCT id AS winner FROM eiga_grades WHERE id NOT IN (SELECT loser FROM eiga_duels) ORDER BY winner";
	}
	else{
		$list = implode($winners, ", ");
		$sql = "SELECT DISTINCT id AS winner FROM eiga_grades WHERE id NOT IN (SELECT loser FROM eiga_duels WHERE winner NOT IN (" . $list . ")) ORDER BY winner";
	}

	$statement = $dbh->prepare($sql);
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_OBJ);

	$i2 = 0;
	foreach($result as $winner){
		if(!in_array($winner->winner, $winners)){
			$i2++;
			$winners[] = $winner->winner;

			$update_sql = "UPDATE eiga_grades SET position = :position WHERE id = :id";
			$update_statement = $dbh->prepare($update_sql);
			$update_statement->bindParam(":position", $i);
			$update_statement->bindParam(":id", $winner->winner);
			$update_statement->execute();
		}
	}
	if($i2 == 0){
		break;
	}
}

// echo "<pre>"; print_r($login); echo "</pre>";
?>
<div class="container">
	<div class="col-md-12">
		<h1>The list</h1>
<?php

$position = 0;

$sql = "SELECT * FROM eiga_grades ORDER BY CASE WHEN position = 0 THEN (SELECT MAX(position) FROM eiga_grades) + 1 ELSE position END, id";
$statement = $dbh->prepare($sql);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_OBJ);
$i = 0;
$i2 = 0;
foreach($result as $movie){
	$i++;
	if($i == 1){
		echo "<table class='table-striped' id='list'>";
	}
	if($movie->position != $position){
		$i2++;
		if($i > 1){
			echo "</td></tr>";
		}
		$position = $movie->position;
		echo "<tr>";
		echo "<th><h2>" . $position . "</h2></th>";
		echo "<td>";
	}
	$movie_details = get_movie($movie->id);
	//echo "<pre>"; print_r($movie_details); echo "</pre>";
	//show_movie($movie->id, "small");
	echo "<a href='" . $movie_details->url . "'><img src='" . $movie_details->poster_small . "' title='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' alt='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' /></a>";
}
if($i > 0){
	echo "</td></tr></table>";
}

?>
	</div>

</div>

<?php

include("footer.php");
