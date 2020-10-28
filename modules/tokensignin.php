<?php

// Get $id_token via HTTPS POST.
if($_POST['idtoken']){
	$id_token = $_POST['idtoken'];
	verify_and_refresh_jwt($id_token);
}
