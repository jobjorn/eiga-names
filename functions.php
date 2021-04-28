<?php

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
		$options = array("expires" => time() + 1800, "path" => "/", "httponly" => TRUE, "samesite" => "Strict");
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
