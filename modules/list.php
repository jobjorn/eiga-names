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
		$i3 = 0;
		foreach ($result as $name) {
			$i++;
			if ($i == 1) {
				echo "<table class='table-striped' id='list'>";
			}
			if ($name->position != $position) {
				$i2++;
				if ($i > 1) {
					echo "</td></tr>";
				}
				$position = $name->position;
				echo "<tr>";
				echo "<th><h2>" . $position . "</h2></th>";
				echo "<td>";
				$i3 = 0;
			}
			$i3++;
			$name_details = get_name($name->name_id);
			if ($i3 > 1) {
				echo ", ";
			}
			echo "<a class='list-name' href='" . $name_details->url . "'>" . $name_details->name  . "</a>";
		}
		if ($i > 0) {
			echo "</td></tr></table>";
		}

		?>
	</div>

</div>

<?php

include("footer.php");
