<!DOCTYPE html>
<html><head>

	<meta charset=cp1251>

	<title>������-���������� XV ��������� ���������� ������� �� ���������������� ����� ��������� � ���������� ����������</title>

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
			xicl.monitor( <?=$contest?> );
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