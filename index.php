<?php
require_once("core.php");

$allowed_logged_out = array("login", "logout", "tokensignin", "splash");

if ($logged_in || in_array($module, $allowed_logged_out)) {

	if (isset($module)) {
		if (file_exists("modules/" . $module . ".php")) {
			include("modules/" . $module . ".php");
		} else {
			include("modules/home.php");
		}
	} else {
		include("modules/home.php");
	}
} else {
	include("modules/splash.php");
}
