<?php

/*
function show_movie($id, $size = "large", $duel = false){
	global $dbh;
	global $api_key;
	global $configuration;
	global $root_uri;




	$sql = "SELECT * FROM eiga_grades WHERE id = :id AND year > 0";
	$statement = $dbh->prepare($sql);
	$statement->bindParam(":id", $id);
	$statement->execute();

	$result = $statement->fetchAll(PDO::FETCH_OBJ);

	if(count($result) == 0){
		$imdb_id = "tt" . str_pad($id, 7, "0", STR_PAD_LEFT);
		$api_url = "http://api.themoviedb.org/3/find/" . $imdb_id . "?external_source=imdb_id&api_key=" . $api_key;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response);

		$movie['title'] = $response->movie_results[0]->original_title;
		$movie['year'] = substr($response->movie_results[0]->release_date, 0, 4);
		$movie['poster'] = $response->movie_results[0]->poster_path;
		$movie['overview'] = $response->movie_results[0]->overview;
		$movie['vote_average'] = $response->movie_results[0]->vote_average;


		$update_sql = "UPDATE eiga_grades SET title = :title, year = :year, poster = :poster, overview = :overview, vote_average = :vote_average WHERE id = :id";
		$update_statement = $dbh->prepare($update_sql);
		$update_statement->bindParam(":title", $movie['title']);
		$update_statement->bindParam(":year", $movie['year']);
		$update_statement->bindParam(":poster", $movie['poster']);
		$update_statement->bindParam(":overview", $movie['overview']);
		$update_statement->bindParam(":vote_average", $movie['vote_average']);
		$update_statement->bindParam(":id", $id);
		$update_statement->execute();

	}
	elseif(count($result) == 1){
		$movie['title'] = $result[0]->title;
		$movie['year'] = $result[0]->year;
		$movie['poster'] = $result[0]->poster;
		$movie['overview'] = $result[0]->overview;
	}

	if($size == "large"){
		echo "<h2>" . $movie['title'] . " <small>(" . $movie['year'] . ")</small></h2>";

		echo "<p><a href='" . $root_uri . "/movie/" . $id . "/'><img src='" . $configuration->images->base_url . "w500" . $movie['poster'] . "' title='" . htmlentities($movie['title'], ENT_QUOTES) . " (" . $movie['year'] . ")' alt='" . htmlentities($movie['title'], ENT_QUOTES) . " (" . $movie['year'] . ")' /></a></p>";
		echo "<p>" . $movie['overview'] . "</p>";
	}
	elseif($size == "small"){
		echo "<a href='" . $root_uri . "/movie/" . $id . "/'><img src='" . $configuration->images->base_url . "w92" . $movie['poster'] . "' title='" . htmlentities($movie['title'], ENT_QUOTES) . " (" . $movie['year'] . ")' alt='" . htmlentities($movie['title'], ENT_QUOTES) . " (" . $movie['year'] . ")' /></a>";
	}
}
*/

function get_name($name_id)
{
	global $dbh;
	global $root_uri;

	$sql = "SELECT name
		FROM eiga_names
		WHERE eiga_names.id = :name_id";
	$statement = $dbh->prepare($sql);
	$statement->bindParam(":name_id", $name_id);
	$statement->execute();

	$result = $statement->fetchAll(PDO::FETCH_OBJ);

	$name = new stdClass();
	$name->id = $name_id;

	if (count($result) == 0) {
		$name->error = "No result found";
		return $name;
	} else {

		$name->name = $result[0]->name;
		$name->url = $root_uri . "name/" . $name->id . "/";

		return $name;
	}
}

function show_grade($grade)
{
	$i = 0;
	$grade_string = "";
	while ($i < 5) {
		$i++;
		if ($grade >= $i) {
			$grade_string .= ' <span class="glyphicon glyphicon-film"></span>';
		} else {
			$grade_string .= ' <span class="glyphicon glyphicon-film" style="color: #ddd;"></span>';
		}
	}

	return $grade_string;
}

function tablify_sql($result)
{
	$i = 0;
	$rows = "";
	$headers = "";
	foreach ($result as $key => $row) {
		$i++;


		$rows .= "<tr>";
		if ($i == 1) {
			$headers .= "<th>#</th>";
		}

		$rows .= "<td>" . $i . "</td>";
		foreach ($row as $key => $cell) {
			if ($i == 1) {
				$headers .= "<th>" . $key . "</th>";
			}
			$rows .= "<td>" . $cell . "</td>";
		}

		$rows .= "</tr>";
	}
	if ($i > 0) {
		$headers = "<tr>" . $headers . "</tr>";
		$complete_table = "<table class='table table-striped table-condensed table-bordered table-tweets'>" . $headers . $rows . "</table>";

		return $complete_table;
	} else {
		return false;
	}
}

function verify_and_refresh_jwt($id_token)
{
	global $client;
	global $dbh;

	$payload = $client->verifyIdToken($id_token);

	if ($payload) {
		$google_id = $payload['sub'];
		$email = $payload['email'];
		$name = $payload['name'];
		$picture = $payload['picture'];
		$sql = "SELECT * FROM eiga_users WHERE google_id = :google_id";

		$statement = $dbh->prepare($sql);
		$statement->bindParam(":google_id", $google_id);
		$statement->execute();

		$result = $statement->fetchAll(PDO::FETCH_OBJ);

		if (count($result) == 0) {
			// ingen användare - registrera en
			$sql = "INSERT INTO eiga_users (google_id, email, name, picture) VALUES (:google_id, :email, :name, :picture)";
			$statement = $dbh->prepare($sql);
			$statement->bindParam(":google_id", $google_id);
			$statement->bindParam(":email", $email);
			$statement->bindParam(":name", $name);
			$statement->bindParam(":picture", $picture);
			$statement->execute();

			$logged_in = new stdClass();
			$logged_in->id = $dbh->lastInsertId();
			$logged_in->name = $name;
			$logged_in->picture = $picture;
		} elseif (count($result) == 1) {
			$logged_in = new stdClass();
			$logged_in->id = $result[0]->id;
			$logged_in->name = $result[0]->name;
			$logged_in->picture = $result[0]->picture;
		} else {
			die("Vad i helvete");
		}
		$options = array("expires" => time() + 1800, "path" => "/eiga-names", "httponly" => TRUE, "samesite" => "Strict");
		setcookie("logged_in_user", json_encode($logged_in), $options);

		return true;
	}
}

function cumnormdist($x)
{
	$b1 =  0.319381530;
	$b2 = -0.356563782;
	$b3 =  1.781477937;
	$b4 = -1.821255978;
	$b5 =  1.330274429;
	$p  =  0.2316419;
	$c  =  0.39894228;

	if ($x >= 0.0) {
		$t = 1.0 / (1.0 + $p * $x);
		return (1.0 - $c * exp(-$x * $x / 2.0) * $t *
			($t * ($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
	} else {
		$t = 1.0 / (1.0 - $p * $x);
		return ($c * exp(-$x * $x / 2.0) * $t *
			($t * ($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
	}
}
function distribution_count($position, $total, $max = 10)
{
	$divider = $max / 5;
	$count = round((cumnormdist(($position + 0.5 - $max / 2) / $divider - 0.5 / $divider) - cumnormdist(($position - 0.5 - $max / 2) / $divider - 0.5 / $divider)) / 0.9875806693 * $total);

	return $count;
}
