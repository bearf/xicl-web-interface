<!DOCTYPE html>
<html><head>

	<title>Результаты XV открытого командного турнира по программированию среди студентов и школьников Татарстана</title>

	<link rel="stylesheet" href="css/online.2015.01.16.css">

	<script src="js/handlebars-v1.3.0.js"></script>
	<script src="js/jquery-1.6.2.min.js"></script>
	<script src="js/online.2015.01.16.js"></script>
	
	<?php
		require_once('./config/require.php');
		// todo: +-admin authorize
		// todo: +-frozen
		// todo: +-update time when no submits fetched
		// todo: +-beautiful WAs
		// todo: scroll
		if (!isset($contest)) { $contest = $curcontest; }
	?>

	<script>
		$( function() {
			xicl.reveal( <?=$contest?>, {
				student:[4, 5, 4, 5],
				school:[3, 2, 4, 4]
			} );
		} );

		function adminMode() {
			return <?php echo !!$is_admin; ?>
		}
	</script>

</head><body>

	<div id="mtable" class="turtle-scroll">
	</div>	
	<header>
		<div id="time">
		</div>
		<section>
		</section>
	</header>

</body></html>