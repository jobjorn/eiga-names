		<div class="container">
			<hr>

			<footer>
				<p>Jobjörn Folkesson - <a href="https://twitter.com/jobjorn">@jobjorn</a> - <a href="http://www.jobjorn.se/">jobjorn.se</a></p>
				<pre>
				<?php

				print_r($_COOKIE);
				print_r(json_decode($_COOKIE['payload']));
				?>
			 	</pre>
				<?php
				echo date("Y-m-d H:i:s", $_COOKIE['jwt_expiry']);
				?>
			</footer>
		</div> <!-- /container -->


		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


		</body>

		</html>