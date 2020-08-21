<?php


require_once("core.php");
require_once("connection.php");

if($sub1 == "really"){
	$update_statement = $dbh->prepare("UPDATE eiga_grades SET position = 1");
	$update_statement->execute();
	$truncate_statement = $dbh->prepare("TRUNCATE eiga_duels");
	$truncate_statement->execute();

	header("Location: " . $root_uri);
}
else{
	$page_title = "Verkligen?";
	include("header.php");

	?>
	<div class="container">
		<div class="col-md-12">
			<h1>Verkligen?</h1>
			<p><a class="btn btn-default" href=" <?php echo $root_uri; ?>reset/really/">Ja, rensa alla dueller</a></p>
		</div>
	</div>
	<?php

	include("footer.php");
}
