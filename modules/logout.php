<?php


$options = array("path" => "/", "expires" => time() - 5);
setcookie("logged_in_user", "", $options);

header("Location: " . $root_uri);
