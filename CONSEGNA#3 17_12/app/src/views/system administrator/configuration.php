<?php
require_once("../../controller/systemAdministrator/sa_functions.php");
require_once("../../controller/ServiceFactory.php");
if (!empty($_GET['username'])) {
    $username1 = $_GET['username'];
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
        <label style='padding-right:1000px;color:#173450;'><a href='../general/logout.php'><img id="icon-logout" src="../../resources/images/logout.png"></a></label>
        <h3 id="title"><b>SYSTEM ADMINISTRATION</b></h3>
        <img id="icon-page1" src="../../resources/images/system.png">
        <div style='padding-top:20px;'>
            <input class='button' type='submit' value='Skill assignment' onclick="location.href='viewMaintainers.php?username=<?php echo $username1; ?>'">
            <br><br>
            <input class='button' type='submit' value='Management of skills' onclick="location.href='viewSkills.php?username=<?php echo $username1; ?>'">
            <br><br>
            <input class='button' type='submit' value='Procedure assignment' onclick="location.href='viewProcedure.php?username=<?php echo $username1; ?>'">
            <br><br>
            <input class='button' type='submit' value='Users Management' onclick="location.href='viewUsers.php?username=<?php echo $username1; ?>'">
        </div>


    </div>

</body>

</html>