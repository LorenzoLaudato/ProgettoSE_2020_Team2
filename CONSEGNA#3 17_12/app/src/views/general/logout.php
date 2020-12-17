<html>

<head>
	<title>Logout</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="../../css/styles.css">
	<script src="../../js/functionJavaScript.js"></script>
</head>

<body>
	<div class="container">
		<?php
		/* attiva la sessione */
		session_start();
		/* sessione attiva, la distrugge */
		$sname = session_name();
		session_destroy();
		/* ed elimina il cookie corrispondente */
		if (isset($_COOKIE['login'])) {
			setcookie($sname, '', time() - 3600, '/');
		}
		echo "<p> Logout successfull. Bye " . $_SESSION['username'] . " </p>";
		echo '<img src="../../resources/images/byebye1.png" style="width:600px; height: 300px;" />';
		echo "<p>Go <b><a href=\"login.html\">Home</a></b></p>";

		?>
	</div>
</body>

</html>