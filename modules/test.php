<?php


include("core.php");

$active['test'] = 'class="active"';


$page_title = "Test";
include("header.php");

?>
<div class="container">
	<div class="col-md-12">
		<h1>Test</h1>
<?php

$year = 2014;
$title = "Interstellar";

$api_url = "http://api.themoviedb.org/3/search/movie" . "?api_key=" . $api_key . "&year=" . $year . "&query=" . htmlentities($title);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response);

/*
	$id = 50083;
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
*/
echo $api_url;

echo "<pre>"; print_r($response); echo "</pre>";

?>
	</div>
</div>



<?php

include("footer.php");
