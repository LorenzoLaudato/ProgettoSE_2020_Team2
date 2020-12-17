<?php
require_once("../../controller/ServiceFactory.php");
?>

<html>

<head>
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
		<p style='padding-right:90%;color: #173450;'>Go to <b><a href='login.html' style='color: #173450;'>login</a></b></p>
		<?php
		if (isset($_POST['nome']))
			$nome = $_POST['nome'];
		else
			$nome = "";
		if (isset($_POST['username']))
			$username1 = $_POST['username'];
		else
			$username1 = "";
		if (isset($_POST['password']))
			$password1 = $_POST['password'];
		else
			$password1 = "";
		if (isset($_POST['repassword']))
			$repassword = $_POST['repassword'];
		else
			$repassword = "";
		if (isset($_POST['email']))
			$email = $_POST['email'];
		else
			$email = "";
		if (isset($_POST['user-select']))
			$role = $_POST['user-select'];
		else
			$role = "";


		//CHECK PASSWORD
		if (!empty($password1)) {
			if ($password1 != $repassword) {
				echo "<p style='color: #173450;'>The password you have entered is invalid. Try again!</p>";
				$password1 = "";
			} else {
				$micro = ServiceFactory::create();
				//CONTROLLO SE L'UTENTE GIA' ESISTE
				if ($micro->usernameExist($username1)) {
					echo "<p style='color: #173450;'> The username $username1 already exists. Try again!</p>";
				} else {
					//ORA posso inserire il nuovo utente nel db
					if ($micro->insertUtente($nome, $username1, $password1, $email, $role)) {
						echo "<p style='color: #173450;'> User $username1 registered successfully. <b><a href=\"login.html\" style='color: #173450;'>Login</a></b></p>";
					} else {
						echo "<p style='color: #173450;'> An error occurred while processing your request. Try again!</p>";
					}
				}
			}
		}


		?>

		<p>
			<h1 id='title'><b>SIGN UP</b></h1>
		</p>
		<form method="post" action="registrati.php" style="text-align:center;">
			<table align="center" style="border-collapse: separate; border-spacing:10px;">
				<tr style="background-color: #afdcec">
					<td><label for="nome" style='color: #173450;'>Name</label></td>
					<td><input type="text" name="nome" id="nome" required /></td>
				</tr>
				<tr style="background-color: #afdcec">
					<td><label for="username" style='color: #173450;'>Username</label></td>
					<td><input type="text" name="username" id="username" required /></td>
				</tr>
				<tr style="background-color: #afdcec">
					<td><label for="password" style='color: #173450;'>Password</label></td>
					<td><input type="password" name="password" id="password" required /></td>
				</tr>
				<tr style="background-color: #afdcec">
					<td><label for="repassword" style='color: #173450;'>Repeat password</label></td>
					<td><input type="password" name="repassword" id="repassword" required /></td>
				</tr>
				<tr style="background-color: #afdcec">
					<td><label for="cars" style='color: #173450;'>Role</label></td>
					<td>
						<select required name="user-select" id="user-select">
							<option disabled selected value>--Please choose an option--</option>
							<option value="Planner">Planner</option>
							<option value="Maintainer">Maintainer</option>
							<option value="System Administrator">System Administrator</option>
						</select>
					</td>
				</tr>
				<tr style="background-color: #afdcec">
					<td><label for="email">E-mail</label></td>
					<td><input type="email" name="email" id="email" required /></td>
				</tr>

			</table>
			<p>
				<input class='button' type="submit" name="registra" value="SIGN UP" />
			</p>
		</form>
	</div>

</body>

</html>