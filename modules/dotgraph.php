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
$sql = "SELECT eiga_grades_winner.title AS winner_title, eiga_grades_winner.year AS winner_year, eiga_grades_loser.title AS loser_title, eiga_grades_loser.year AS loser_year FROM `eiga_duels` JOIN eiga_grades AS eiga_grades_winner ON eiga_duels.winner = eiga_grades_winner.id JOIN eiga_grades AS eiga_grades_loser ON eiga_duels.loser = eiga_grades_loser.id ORDER BY winner_title, loser_title";
$statement = $dbh->prepare($sql);
$statement->execute();

$result = $statement->fetchAll(PDO::FETCH_OBJ);
foreach($result as $duel){
	echo "\t\"" . $duel->winner_title . " (" . $duel->winner_year . ")\" -> \"" . $duel->loser_title . " (" . $duel->loser_year . ")\";\n";
}

?>}</pre>
	</div>
</div>



<?php

include("footer.php");
