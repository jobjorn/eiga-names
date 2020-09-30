<?php

// Get $id_token via HTTPS POST.
if($_POST['idtoken']){
	$id_token = $_POST['idtoken'];
	$payload = $client->verifyIdToken($id_token);
	echo $id_token;
	if($payload){
		$google_id = $payload['sub'];
		$email = $payload['email'];
		$name = $payload['name'];
		$picture = $payload['picture'];
	/*
	(
    [iss] => accounts.google.com
    [azp] => 240748276416-599kn4hneadk3e9ulick7lor4nnefjs4.apps.googleusercontent.com
    [aud] => 240748276416-599kn4hneadk3e9ulick7lor4nnefjs4.apps.googleusercontent.com
    [sub] => 104137162787605911168
    [email] => jobjorn@gmail.com
    [email_verified] => 1
    [at_hash] => 5qo10qeuHgcb7jMfrRabPA
    [name] => Jobjörn Folkesson
    [picture] => https://lh3.googleusercontent.com/a-/AOh14GjNMhz4x4TTpNt2Wuuv2kA06vkAYRpxoDGoSHgwew=s96-c
    [given_name] => Jobjörn
    [family_name] => Folkesson
    [locale] => sv
    [iat] => 1601489086
    [exp] => 1601492686
    [jti] => 16c4ea08f31cb2cdc1da8e3cc491084da0b3abaf

	*/
	// If request specified a G Suite domain:
	//$domain = $payload['hd'];



	$sql = "SELECT * FROM eiga_users WHERE google_id = :google_id";
	$statement = $dbh->prepare($sql);
	$statement->bindParam(":google_id", $google_id);
	$statement->execute();

	$result = $statement->fetchAll(PDO::FETCH_OBJ);

	if(count($result) == 0){
		// ingen användare - registrera en
		$sql = "INSERT INTO eiga_users (google_id, email, name, picture) VALUES (:google_id, :email, :name, :picture)";
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":google_id", $google_id);
		$statement->bindParam(":email", $email);
		$statement->bindParam(":name", $name);
		$statement->bindParam(":picture", $picture);
		$statement->execute();
	}
	$options = array("expires" => $payload['exp'], "httponly" => TRUE, "samesite" => "Strict");
	setcookie("jwt", $id_token, $payload['exp']);


	  echo $name;
	}
}
