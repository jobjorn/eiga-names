<?php
require_once("connection.php");
require_once("functions.php");

require_once("libraries/google-api-php-client/vendor/autoload.php");
$client = new Google_Client(['client_id' => $google_client_id]);
$client->setApplicationName("Eiga Film Sorter (core)");
$client->setAccessType("offline");
$client->setApprovalPrompt("consent");
$client->setDeveloperKey($google_api_key);




// Module and submodule variables
if (isset($_GET['module'])) {
	$module = trim(str_replace("/", "", $_GET['module']));
	if (isset($_GET['sub1'])) {
		$sub1 = trim(str_replace("/", "", $_GET['sub1']));
		if (isset($_GET['sub2'])) {
			$sub2 = trim(str_replace("/", "", $_GET['sub2']));
			if (isset($_GET['sub3'])) {
				$sub3 = trim(str_replace("/", "", $_GET['sub3']));
				if (isset($_GET['sub4'])) {
					$sub4 = trim(str_replace("/", "", $_GET['sub4']));
					if (isset($_GET['sub5'])) {
						$sub5 = trim(str_replace("/", "", $_GET['sub5']));
						if (isset($_GET['sub6'])) {
							$sub6 = trim(str_replace("/", "", $_GET['sub6']));
						}
					}
				}
			}
		}
	}
}

// api configuration
$api_url = "http://api.themoviedb.org/3/configuration?api_key=" . $api_key;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ch);
curl_close($ch);
$configuration = json_decode($response);

// Database connection
try {
	$dbh = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo $e->getMessage();
	die();
}


// Cookie handling
if (isset($_COOKIE['logged_in_user'])) {
	$logged_in_user = json_decode($_COOKIE['logged_in_user']);

	$options = array("expires" => time() + 1800, "path" => "/", "httponly" => TRUE, "samesite" => "Strict");
	setcookie("logged_in_user", json_encode($logged_in_user), $options);
	$logged_in = true;
} else {
	$logged_in = false;
}
