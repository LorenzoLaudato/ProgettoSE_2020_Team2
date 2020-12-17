<?php
require_once("../../controller/ServiceFactory.php");
require_once("../../controller/general/general_functions.php");

?>

<html>

<head>
	<title>Gestione Login</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
	<div class="container">
		<?php
		$micro = ServiceFactory::create();
		manageLogger();

		?>
	</div>
</body>

</html>