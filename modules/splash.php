<?php


include("core.php");

$active['home'] = 'class="active"';


$page_title = "Hem";
include("header.php");


?>
<div class="container">

<?php

$position_limits = array();
$position_limits[1] = 1;
$position_limits[2] = 1;
$position_limits[3] = 2;
$position_limits[4] = 4;
$position_limits[5] = 5;
$position_limits[6] = 5;
$position_limits[7] = 4;
$position_limits[8] = 2;
$position_limits[9] = 1;
$position_limits[10] = 1;

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

	echo $position . "-" . $count . "-" . $position_limits[$position];

	$sql = "SELECT id FROM eiga_grades WHERE position = :position ORDER BY (SELECT COUNT(*) FROM eiga_duels WHERE winner = eiga_grades.id), RAND() LIMIT 2";
	$statement = $dbh->prepare($sql);
	$statement->bindParam(":position", $position);
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_OBJ);

	$movie1 = $result[0]->id;
	$movie2 = $result[1]->id;

	?>
			<div class="col-md-6">
	<?php

	show_movie($movie1, "large", true);

	echo "<a class='btn btn-default' href='" . $root_uri . "/duel/" . $movie1 . "/" . $movie2 . "/'>Den här är bättre</a>";
	?>
			</div>
			<div class="col-md-6">
	<?php

	show_movie($movie2, "large", true);
	echo "<a class='btn btn-default' href='" . $root_uri . "/duel/" . $movie2 . "/" . $movie1 . "/'>Den här är bättre</a>";
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
