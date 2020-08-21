<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<?php
			if($page_title){
				echo "<title>" . $page_title . " | Eiga</title>";
			}
			else{
				echo "<title>Eiga</title>";
			}
		?>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

		<!-- Custom additions -->
		<link href="<?php echo $root_uri; ?>style.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<link rel="shortcut icon" href="/favicon.png" />

	</head>


	<body role="document">

		<!-- Fixed navbar -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo $root_uri; ?>">Eiga</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li <?php if(isset($active['home'])){ echo $active['home']; } ?>><a href="<?php echo $root_uri; ?>"><span class="glyphicon glyphicon-film"></span> Home</a></li>
						<li <?php if(isset($active['list'])){ echo $active['list']; } ?>><a href="<?php echo $root_uri; ?>list/"><span class="glyphicon glyphicon-th-list"></span> List</a></li>
						<li <?php if(isset($active['dotgraph'])){ echo $active['dotgraph']; } ?>><a href="<?php echo $root_uri; ?>dotgraph/"><span class="glyphicon glyphicon-option-vertical"></span> DOT digraph</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
