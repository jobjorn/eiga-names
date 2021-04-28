<?php


require_once("core.php");
require_once("connection.php");

if ($logged_in && is_numeric($sub1) && is_numeric($sub2)) {
	$insert_sql = "INSERT INTO eiga_duels (user_id, winner, loser) VALUES (:user_id, :winner, :loser)";
	$insert_statement = $dbh->prepare($insert_sql);
	$insert_statement->bindParam(":user_id", $logged_in_user->id);
	$insert_statement->bindParam(":winner", $sub1);
	$insert_statement->bindParam(":loser", $sub2);
	$insert_statement->execute();


	$winners = array();
	$i = 0;
	while (true) {
		$i++;

		if ($i == 1) {
			$sql = "SELECT DISTINCT movie_id AS winner FROM eiga_grades WHERE user_id = :user_id1 AND movie_id NOT IN (SELECT loser FROM eiga_duels WHERE user_id = :user_id2) ORDER BY winner";
		} else {
			$list = implode(", ", $winners);
			$sql = "SELECT DISTINCT movie_id AS winner FROM eiga_grades WHERE user_id = :user_id1 AND movie_id NOT IN (SELECT loser FROM eiga_duels WHERE user_id = :user_id2 AND winner NOT IN (" . $list . ")) ORDER BY winner";
		}

		$statement = $dbh->prepare($sql);
		$statement->bindParam(":user_id1", $logged_in_user->id);
		$statement->bindParam(":user_id2", $logged_in_user->id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_OBJ);

		$i2 = 0;
		foreach ($result as $winner) {
			if (!in_array($winner->winner, $winners)) {
				$i2++;
				$winners[] = $winner->winner;

				$update_sql = "UPDATE eiga_grades SET position = :position WHERE movie_id = :movie_id AND user_id = :user_id";
				$update_statement = $dbh->prepare($update_sql);
				$update_statement->bindParam(":position", $i);
				$update_statement->bindParam(":movie_id", $winner->winner);
				$update_statement->bindParam(":user_id", $logged_in_user->id);
				$update_statement->execute();
			}
		}
		if ($i2 == 0) {
			break;
		}
	}
}

header("Location: " . $root_uri);
