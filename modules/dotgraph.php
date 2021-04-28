<?php


include("core.php");

$active['dotgraph'] = 'class="active"';


$page_title = "DOT graph";
include("header.php");

?>
<div class="container">
	<div class="col-md-12">
		<h1>DOT digraph</h1>
		<p>To generate a PNG graph from this, copy and paste the below into a text file, save it as <i>input.dot</i> and run:</p>
		<pre>dot -Tpng -o output.png input.dot</pre>
		<pre>digraph "Eiga graph" {
<?php
$sql = "SELECT
	eiga_names_winner.name AS winner_name,
	eiga_names_loser.name AS loser_name
	FROM `eiga_duels`
	JOIN eiga_names AS eiga_names_winner ON eiga_duels.winner = eiga_names_winner.id
	JOIN eiga_names AS eiga_names_loser ON eiga_duels.loser = eiga_names_loser.id
	WHERE eiga_duels.user_id = :user_id1
	ORDER BY winner_name, loser_name";
$statement = $dbh->prepare($sql);
$statement->bindParam(":user_id1", $logged_in_user->id);
$statement->execute();

$result = $statement->fetchAll(PDO::FETCH_OBJ);
foreach ($result as $duel) {
	echo "\t\"" . $duel->winner_name . "\" -> \"" . $duel->loser_name . "\";\n";
}

?>}</pre>
	</div>
</div>



<?php

include("footer.php");
