<?php


include("core.php");

$active['home'] = 'class="active"';


$page_title = "Hem";
include("header.php");


?>
<div class="container">

<?php

$position_limits = array();
$position_limits[1] = 25;
$position_limits[2] = 68;
$position_limits[3] = 144;
$position_limits[4] = 238;
$position_limits[5] = 306;
$position_limits[6] = 306;
$position_limits[7] = 238;
$position_limits[8] = 144;
$position_limits[9] = 68;
$position_limits[10] = 25;

$position = 0;
$count = 0;

$sql = "SELECT position, COUNT(position) AS count FROM eiga_grades GROUP BY position ORDER BY position ASC";
$statement = $dbh->prepare($sql);
$statement->execute();

$result = $statement->fetchAll(PDO::FETCH_OBJ);
foreach($result as $position_count){
	if($position_count->count > $position_limits[$position_count->position]){
		$position = $position_count->position;
		$count = $position_count->count;
		break;
	}
}

if($count > 1){
	?>
			<div class="col-md-12"><h1>Vilken av dessa är bäst?</h1><p>
	<?php
	echo "Du går igenom <strong>position ". $position . "</strong>. Där finns <strong>" . $count . " filmer</strong>, men max är <strong>" . $position_limits[$position] . "</strong>.";
	?>
			</p></div>
	<?php
	$sql = "SELECT id FROM eiga_grades WHERE position = :position ORDER BY (SELECT COUNT(*) FROM eiga_duels WHERE winner = eiga_grades.id), RAND() LIMIT 2";
	$statement = $dbh->prepare($sql);
	$statement->bindParam(":position", $position);
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_OBJ);

	$movie1 = get_movie($result[0]->id);
	$movie2 = get_movie($result[1]->id);

	?>
			<div class="col-md-6">
	<?php

	echo "<h2>" . $movie1->title . " <small>(" . $movie1->year . ")</small></h2>";
	echo "<h4>" . show_grade($movie1->grade) . " / " . $movie1->vote_average . "</h4>";

	echo "<div class='effect2'><a href='" . $root_uri . "/duel/" . $movie1->id . "/" . $movie2->id . "/'>";
	echo "<img src='" . $movie1->poster_large . "' title='" . htmlentities($movie1->title, ENT_QUOTES) . " (" . $movie1->year . ")' alt='" . htmlentities($movie1->title, ENT_QUOTES) . " (" . $movie1->year . ")' />";
	echo "</a></div>";
	echo "<p class='overview'>" . $movie1->overview . "</p>";

	?>
			</div>
			<div class="col-md-6">
	<?php

	echo "<h2>" . $movie2->title . " <small>(" . $movie2->year . ")</small></h2>";
	echo "<h4>" . show_grade($movie2->grade) . " / " . $movie2->vote_average . "</h4>";

	echo "<div class='effect2'><a href='" . $root_uri . "/duel/" . $movie2->id . "/" . $movie1->id . "/'>";
	echo "<img src='" . $movie2->poster_large . "' title='" . htmlentities($movie2->title, ENT_QUOTES) . " (" . $movie2->year . ")' alt='" . htmlentities($movie2->title, ENT_QUOTES) . " (" . $movie2->year . ")' />";
	echo "</a></div>";
	echo "<p class='overview'>" . $movie2->overview . "</p>";

	?>
			</div>
	<?php
}
else{
	?>
	<div class="col-md-12">
		<h1>Slut!</h1>
		<p>Nu har du duellerat klart.</p>
	</div>
	<?php
}
?>
</div>



<?php

include("footer.php");
