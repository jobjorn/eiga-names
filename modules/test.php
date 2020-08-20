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
$sql = "SELECT position, COUNT(position) AS count FROM eiga_grades GROUP BY position ORDER BY count DESC";
$statement = $dbh->prepare($sql);
$statement->execute();

$result = $statement->fetchAll(PDO::FETCH_OBJ);

echo tablify_sql($result);

?>
	</div>
</div>



<?php

include("footer.php");
