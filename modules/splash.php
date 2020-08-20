<?php


include("core.php");

$active['home'] = 'class="active"';


$page_title = "Hem";
include("header.php");


?>
<div class="container">

<?php


$sql = "SELECT position, COUNT(position) AS count FROM eiga_grades GROUP BY position ORDER BY count DESC, position ASC";
$statement = $dbh->prepare($sql);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_OBJ);
$position = $result[0]->position;
$count = $result[0]->count;

if($count > 1){

	$sql = "SELECT id FROM eiga_grades WHERE position = :position ORDER BY RAND() LIMIT 2";
	$statement = $dbh->prepare($sql);
	$statement->bindParam(":position", $position);
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_OBJ);

	$movie1 = $result[0]->id;
	$movie2 = $result[1]->id;

	?>
			<div class="col-md-6">
	<?php

	show_movie($movie1);

	echo "<a class='btn btn-default' href='" . $root_uri . "/duel/" . $movie1 . "/" . $movie2 . "/'>Den här är bättre</a>";
	?>
			</div>
			<div class="col-md-6">
	<?php

	show_movie($movie2);
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
