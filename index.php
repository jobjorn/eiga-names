<?php
include("core.php");

if(isset($module)){
	if(file_exists("modules/" . $module . ".php")){
		include("modules/" . $module . ".php");
	}
	else{
		include("modules/splash.php");
	}
}
else{
	include("modules/splash.php");
}

