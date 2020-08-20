<?php


require_once("core.php");
require_once("connection.php");

$insert_sql = "INSERT INTO eiga_duels (winner, loser) VALUES (:winner, :loser)";
$insert_statement = $dbh->prepare($insert_sql);
$insert_statement->bindParam(":winner", $sub1);
$insert_statement->bindParam(":loser", $sub2);
$insert_statement->execute();

$winners = array();
$i = 0;
while(true){
	$i++;

	if($i == 1){
		$sql = "SELECT DISTINCT winner FROM eiga_duels WHERE winner NOT IN (SELECT loser FROM eiga_duels) ORDER BY winner";
	}
	else{
		$list = implode($winners, ", ");
		$sql = "SELECT DISTINCT winner FROM eiga_duels WHERE winner NOT IN (SELECT loser FROM eiga_duels WHERE winner NOT IN (" . $list . ")) ORDER BY winner";
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

header("Location: " . $root_uri);
