<?php


require_once("core.php");
require_once("connection.php");

if ($sub1 == "really") {
	$delete_statement = $dbh->prepare("DELETE FROM eiga_grades WHERE user_id = :user_id");
	$delete_statement->bindParam(":user_id", $logged_in_user->id);
	$delete_statement->execute();

	$delete_statement = $dbh->prepare("DELETE FROM eiga_duels WHERE user_id = :user_id");
	$delete_statement->bindParam(":user_id", $logged_in_user->id);
	$delete_statement->execute();

	header("Location: " . $root_uri);
} else {
	$page_title = "Verkligen?";
	include("header.php");

?>
	<div class="container">
		<div class="col-md-12">
			<h1>Verkligen?</h1>
			<p><a class="btn btn-default" href=" <?php echo $root_uri; ?>reset/really/">Ja, rensa alla dueller och alla filmer</a></p>
		</div>
	</div>
<?php

	include("footer.php");
}
