<?php
include("core.php");

$active['list'] = 'class="list"';

$page_title = "List";
$id = $sub1;

include("header.php");


// echo "<pre>"; print_r($login); echo "</pre>";
?>
<div class="container">
	<div class="col-md-6">
		<?php
show_movie($id);
		?>
	</div>
	<div class="col-md-6">
		<h3>Won against</h3>
		<?php
$sql = "SELECT * FROM eiga_duels WHERE winner = :winner";
$statement = $dbh->prepare($sql);
$statement->bindParam(":winner", $id);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_OBJ);

foreach($result as $movie){
	show_movie($movie->loser, "small");
}
		?>

		<h3>Lost against</h3>
		<?php
$i = 0;

$sql = "SELECT * FROM eiga_duels WHERE loser = :loser";
$statement = $dbh->prepare($sql);
$statement->bindParam(":loser", $id);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_OBJ);

foreach($result as $movie){
	show_movie($movie->winner, "small");
}
		?>

	</div>

</div>

<?php

include("footer.php");
