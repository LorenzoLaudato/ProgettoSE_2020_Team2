<?php

require_once("../../controller/systemAdministrator/sa_functions.php");
require_once("../../controller/ServiceFactory.php");
if (!empty($_GET['username'])) {
    $username2 = $_GET['username']; //username corrente
}
$micro = ServiceFactory::create();

if (!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['username1'])) {
    //Prima controllo se l'id che si intende cancellare esiste
    $user_to_delete = $_GET['username1']; //id della skill da cancellare
    $ret = $micro->deleteUser($user_to_delete);
    echo " <script> window.location.href='viewUsers.php?username=$username2' </script>";
}

if (!empty($_GET['action']) && $_GET['action'] == 'add') {
    $role = $_POST['userType'];
    $nome = $_POST['name-input'];
    $username1 = $_POST['username-input'];
    $password1 = $_POST['password-input'];
    if (!$micro->usernameExist($username1)) {
        $micro->insertUtente($nome, $username1, $password1, null, $role);
    } else {
        echo "<p>L'utente $username1 è gia esistente.</p>";
    }
    echo " <script> window.location.href='viewUsers.php?username=$username2' </script>";
}
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
        <?php
        echo "<a href='configuration.php?username=$username2'><img id='icon-back' src='../../resources/images/back.png'></a>";
        ?>
        <h3 id="title"><b>SYSTEM ADMINISTRATION</b></h3>
        <img id="icon-page1" src="../../resources/images/user-group.png">
        <div>
            <br>
            <!--VISUALIZZAZIONE DEGLI UTENTI-->
            <table id='usersView' class="table table-striped" border="1">
                <thead>
                    <tr style="background-color:#FFF8C6;">
                        <th scope="col">ROLE</th>
                        <th scope="col">NAME</th>
                        <th scope="col">USERNAME</th>
                    </tr>

                    <?php

                    $ret = $micro->getUsers($username2); //gli passo lo username corrente cosi non me lo mostra
                    showUsers($ret, $username2);
                    ?>

                </thead>
            </table>
            <div>
                <form action="viewUsers.php?action=add&username=<?php echo $username2; ?>" method="POST">
                    <br>
                    <legend>Write here to add a new user:</legend>
                    <br>
                    <label for="role-input">Role:</label>
                    <select required name="userType" id="user-select">
                        <option disabled selected value>--Please choose an option--</option>
                        <option value="Planner">Planner</option>
                        <option value="Maintainer">Maintainer</option>
                    </select>
                    <label for="username-input">Name:</label>
                    <input type="text" id="name-input" name="name-input" required>
                    <label for="username-input">Username:</label>
                    <input type="text" id="username-input" name="username-input" required>
                    <label for="password-input">Password:</label>
                    <input type="password" id="password-input" name="password-input" autocomplete="new-password" required>
                    <br><br>
                    <input class='button' type="submit" value="ADD USER">
                </form>

            </div>
        </div>

    </div>
</body>

</html>