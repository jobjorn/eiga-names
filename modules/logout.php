<?php


$options = array("path" => "/eiga-names", "expires" => time() - 5);
setcookie("logged_in_user", "", $options);

header("Location: " . $root_uri);
