<?php


include("core.php");

$active['home'] = 'class="active"';


$page_title = "Hem";
include("header.php");


?>
<div class="container">

	<div class="g-signin2" data-onsuccess="onSignIn"></div>
	<?php
	print_r($_COOKIE);

	 ?>
</div>


<?php

include("footer.php");
