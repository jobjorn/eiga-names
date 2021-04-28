<?php


include("core.php");

$active['home'] = 'class="active"';


$page_title = "Hem";
include("header.php");


?>
<div class="container">

	<?php

	$position = 0;
	$count = 0;

	$sql = "SELECT position, COUNT(position) AS count FROM eiga_grades WHERE user_id = :user_id GROUP BY position ORDER BY position ASC";
	$statement = $dbh->prepare($sql);

	$statement->bindParam(":user_id", $logged_in_user->id);
	$statement->execute();

	$result = $statement->fetchAll(PDO::FETCH_OBJ);
	foreach ($result as $position_count) {
		if ($position_count->count > 1) {
			$position = $position_count->position;
			$count = $position_count->count;
			break;
		}
	}

	if ($count > 1) {
	?>
		<div class="col-md-12">
			<h1>Vilken av dessa är bäst?</h1>
			<p>
				<?php
				echo "Du går igenom <strong>position " . $position . "</strong>. Där finns <strong>" . $count . " namn</strong>, men max är <strong>1</strong>.";
				?>
			</p>
		</div>
		<?php
		$sql = "SELECT name_id FROM eiga_grades WHERE user_id = :user_id1 AND position = :position ORDER BY (SELECT COUNT(*) FROM eiga_duels WHERE winner = eiga_grades.name_id AND user_id = :user_id2), RAND() LIMIT 2";
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":position", $position);
		$statement->bindParam(":user_id1", $logged_in_user->id);
		$statement->bindParam(":user_id2", $logged_in_user->id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_OBJ);

		$name1 = get_name($result[0]->name_id);
		$name2 = get_name($result[1]->name_id);

		?>
		<div class="col-md-6 duel">
			<?php


			echo "<a href='" . $root_uri . "duel/" . $name1->id . "/" . $name2->id . "/'><h2>" . $name1->name . "</h2></a>";

			?>
		</div>
		<div class="col-md-6 duel">
			<?php

			echo "<a href='" . $root_uri . "duel/" . $name2->id . "/" . $name1->id . "/'><h2>" . $name2->name . "</h2></a>";


			?>
		</div>
	<?php
	} else {
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
