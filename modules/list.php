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

		$sql = "SELECT * FROM eiga_grades WHERE user_id = :user_id1 ORDER BY CASE WHEN position = 0 THEN (SELECT MAX(position) FROM eiga_grades WHERE user_id = :user_id2 ) + 1 ELSE position END, id";
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":user_id1", $logged_in_user->id);
		$statement->bindParam(":user_id2", $logged_in_user->id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_OBJ);
		$i = 0;
		$i2 = 0;
		foreach ($result as $movie) {
			$i++;
			if ($i == 1) {
				echo "<table class='table-striped' id='list'>";
			}
			if ($movie->position != $position) {
				$i2++;
				if ($i > 1) {
					echo "</td></tr>";
				}
				$position = $movie->position;
				echo "<tr>";
				echo "<th><h2>" . $position . "</h2></th>";
				echo "<td>";
			}
			$movie_details = get_movie($movie->movie_id);
			//echo "<pre>"; print_r($movie_details); echo "</pre>";
			//show_movie($movie->id, "small");
			echo "<a href='" . $movie_details->url . "'><img src='" . $movie_details->poster_small . "' title='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' alt='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' /></a>";
		}
		if ($i > 0) {
			echo "</td></tr></table>";
		}

		?>
	</div>

</div>

<?php

include("footer.php");
