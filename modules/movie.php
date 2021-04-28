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
		$movie1 = get_movie($id);

		echo "<h2>" . $movie1->title . " <small>(" . $movie1->year . ")</small></h2>";
		echo "<h4>" . show_grade($movie1->grade) . " / " . $movie1->vote_average . "</h4>";

		echo "<div class='effect2'>";
		echo "<img src='" . $movie1->poster_large . "' title='" . htmlentities($movie1->title, ENT_QUOTES) . " (" . $movie1->year . ")' alt='" . htmlentities($movie1->title, ENT_QUOTES) . " (" . $movie1->year . ")' />";
		echo "</div>";
		echo "<p class='overview'>" . $movie1->overview . "</p>";
		echo "<div class='link_letterboxd'><a href='" . $movie1->letterboxd_uri . "'>Letterboxd</a></div>";
		echo "<div class='link_tmdb'><a href='https://www.themoviedb.org/movie/" . $movie1->tmdb_id . "'>TMDb</a></div>";
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

		foreach ($result as $duel) {
			$movie_details = get_movie($duel->loser);
			echo "<a href='" . $movie_details->url . "'><img src='" . $movie_details->poster_small . "' title='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' alt='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' /></a>";
		}
		?>

		<h3>Lost against</h3>
		<?php
		$i = 0;

		$sql = "SELECT * FROM eiga_duels WHERE loser = :loser AND user_id = :user_id";
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":loser", $id);
		$statement->bindParam(":user_id", $logged_in_user->id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_OBJ);

		foreach ($result as $duel) {
			$movie_details = get_movie($duel->winner);
			echo "<a href='" . $movie_details->url . "'><img src='" . $movie_details->poster_small . "' title='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' alt='" . htmlentities($movie_details->title, ENT_QUOTES) . " (" . $movie_details->year . ")' /></a>";
		}
		?>

	</div>

</div>

<?php

include("footer.php");
