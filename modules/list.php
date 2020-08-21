<?php
include("core.php");

$active['list'] = 'class="active"';

$page_title = "List";

include("header.php");


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
	show_movie($movie->id, "small");
}
if($i > 0){
	echo "</td></tr></table>";
}

?>
	</div>

</div>

<?php

include("footer.php");
