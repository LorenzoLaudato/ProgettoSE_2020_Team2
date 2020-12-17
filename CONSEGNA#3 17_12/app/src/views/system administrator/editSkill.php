<?php
require_once("../../controller/systemAdministrator/sa_functions.php");
require_once("../../controller/ServiceFactory.php");

$micro = ServiceFactory::create();
if (!empty($_GET['sid']) && !empty($_GET['skill'])) {
    $sid = $_GET['sid'];
    $skill_to_edit = $_GET['skill'];
}

if (!empty($_GET['username'])) {
    $username2 = $_GET['username']; //username corrente
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
        <a href="viewSkills.php?username=<?php echo $username2; ?>"><img id="icon-back" src="../../resources/images/back.png"></a>

        <?php
        echo "<a href='configuration.php?username=$username2'><img id='icon-home' src='../../resources/images/home.png'></a>";
        ?>
        <h3 id="title"><b>SYSTEM ADMINISTRATION</b></h3>

        <br>
        <form id="form" method="POST">
            <textarea style='position:center;' name="skill" id="skill" rows="5" cols="50"><?php echo $skill_to_edit ?></textarea>
            <br>
            <br>
            <input id="edit-submit" type="submit" name="edit-submit" value="EDIT">
        </form>
        <?php
        $micro->updateSkill($sid);
        ?>

    </div>
</body>

</html>