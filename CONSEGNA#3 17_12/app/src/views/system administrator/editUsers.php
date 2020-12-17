<?php

require_once("../../controller/systemAdministrator/sa_functions.php");
require_once("../../controller/ServiceFactory.php");

if (!empty($_GET['role']) && !empty($_GET['name']) && !empty($_GET['username1']) && !empty($_GET['username'])) {
    $role = $_GET['role'];
    $name = $_GET['name'];
    $username1 = $_GET['username1'];
    $username2 = $_GET['username'];
}

?>

<html lang="en">

<head>
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
        echo "<a href='viewUsers.php?username=$username2'><img id='icon-back' src='../../resources/images/back.png'></a>";
        ?>
        <?php
        echo "<a href='configuration.php?username=$username2'><img id='icon-home' src='../../resources/images/home.png'></a>";
        ?>
        <h3 id="title"><b>SYSTEM ADMINISTRATION</b></h3>
        <img id="icon-page1" style="right:70px;" src="../../resources/images/manage.png">

        <br>

        <div>
            <form id="form" method="POST">
                <br>
                <legend>Write here to edit the selected user:</legend>
                <br>
                <label for="role-input">Role:</label>
                <select required name="userType" id="user-select">
                    <option disabled selected value>--Please choose an option--</option>
                    <option value="Planner">Planner</option>
                    <option value="Maintainer">Maintainer</option>
                </select>
                <label for="username-input">Username:</label>
                <input type="text" id="username-input" name="username-input" value="<?php echo $username1 ?>" required>
                <br><br>
                <input id="edit-submit" type="submit" name="edit-submit" value="EDIT">
            </form>

        </div>

        <?php
        $micro = ServiceFactory::create();
        $micro->updateUser($username1);
        ?>

    </div>
</body>

</html>