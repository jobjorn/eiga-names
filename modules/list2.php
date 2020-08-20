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


$winners = array();
$i = 0;
$position = 0;
while($i < 50){
	$i++;
	if($i == 1){
		echo "<table>";
	}

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
			$winners[] = $winner->winner;

			$i2++;
			if($i2 == 1){
				echo "</td></tr>";
				echo "<tr>";
				echo "<th><h2>" . $i . "</h2></th>";
				echo "<td>";
			}
			show_movie($winner->winner, "small");
		}
	}
	if($i2 == 0){
		break;
	}
}
if($i > 0){
	echo "</td></tr></table>";
}
?>
        </div>

    </div>

    <?php

include("footer.php");
