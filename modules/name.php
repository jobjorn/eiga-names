<?php
include("core.php");

$active['list'] = 'class="list"';

$page_title = "List";
$id = $sub1;

include("header.php");

?>
<div class="container">
	<div class="col-md-6">
		<?php
		$name1 = get_name($id);

		echo "<h2>" . $name1->name . "</h2>";

		?>
	</div>
	<div class="col-md-6" id="against">
		<h3>Won against</h3>
		<?php
		$sql = "SELECT * FROM eiga_duels WHERE winner = :winner AND user_id = :user_id";
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":winner", $id);
		$statement->bindParam(":user_id", $logged_in_user->id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_OBJ);

		$i = 0;
		foreach ($result as $duel) {
			$i++;
			if ($i > 1) {
				echo ", ";
			}
			$name_details = get_name($duel->loser);
			echo "<a class='list-name' href='" . $name_details->url . "'>" . $name_details->name . "</a>";
		}
		?>

		<h3>Lost against</h3>
		<?php
		$sql = "SELECT * FROM eiga_duels WHERE loser = :loser AND user_id = :user_id";
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":loser", $id);
		$statement->bindParam(":user_id", $logged_in_user->id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_OBJ);

		$i = 0;
		foreach ($result as $duel) {
			$i++;
			if ($i > 1) {
				echo ", ";
			}
			$name_details = get_name($duel->winner);
			echo "<a class='list-name' href='" . $name_details->url . "'>" . $name_details->name . "</a>";
		}
		?>

	</div>

</div>

<?php

include("footer.php");
