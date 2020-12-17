<?php

require_once("../../controller/systemAdministrator/sa_functions.php");
require_once("../../controller/ServiceFactory.php");

if (!empty($_GET['username'])) {
    $username2 = $_GET['username'];
}

if (!empty($_GET['code']) && !empty($_GET['name'])) {
    $code = $_GET['code'];
    $name = $_GET['name'];
    $email = $_GET['email'];
}

if ($email == '') {
    $email = 'Not Found';
}

$micro = ServiceFactory::create();

if (!empty($_GET['action']) && $_GET['action'] == 'delete') {
    //Prima controllo se l'id che si intende cancellare esiste
    $sid = $_GET['sid']; //id della skill da cancellare
    $micro->deleteMaintainerSkill($code, $sid);
}

//CONTROLLO SE CI SONO DA AGGIUNGERE NUOVE SKILLS AL MAINTAINER
if (!empty($_GET['action']) && $_GET['action'] == 'add' && !empty($_POST['skill_to_add'])) {

    $skills = $_POST['skill_to_add']; //prelievo delle skills selezionate dall'utente
    $N = count($skills); //skills da aggiungere
    //echo("You selected $N skill(s): ");
    for ($i = 0; $i < $N; $i++) {
        $micro->addSkillToMaintainer($code, $skills[$i]);
    }
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
    <script src="../../js/systemAdministrator/system.js"></script>
</head>

<body>
    <div class="container">
        <?php
        echo "<a href='viewMaintainers.php?username=$username2'><img id='icon-back' src='../../resources/images/back.png'></a>";
        ?>
        <?php
        echo "<a href='configuration.php?username=$username2'><img id='icon-home' src='../../resources/images/home.png'></a>";
        ?>
        <h3 id="title"><b>SYSTEM ADMINISTRATION</b></h3>

        <div>
            <!--RIGA 1-->
            <div class="row" style="padding-top: 10px;">
                <div class="col-sm-2" style="padding-top: 10px;">
                    <label style="font-size:25px;">Code:</label>
                </div>
                <div class="col-sm-1 row1">
                    <b style="font-size:15px;"><?php echo $code ?></b>
                </div>
                <div class="col-sm-2" style="padding-top: 12px;">
                    <label style="font-size:24px;">Maintainer:</label>
                </div>
                <div class="col-sm-2 row1">
                    <b style="font-size:20px"><?php echo $name ?></b>
                </div>
                <div class="col-sm-2" style="padding-top: 12px;">
                    <label style="font-size:24px;">Email:</label>
                </div>
                <div class="col-sm-3 row1">
                    <b style="font-size:18px"><?php echo $email ?></b>
                </div>

            </div>

            <br>

            <!--VISUALIZZAZIONE DELLE SKILLS DEL MANUTENTORE SELEZIONATO-->
            <table id='maintainersView' class="table table-striped" border="1">
                <thead>
                    <tr style="background-color:#FFF8C6;">
                        <th scope="col" style='text-align:center;'><?php echo $name ?>'S SKILLS</th>
                    </tr>

                    <?php
                    $ret = $micro->getMaintainerSkills($code); //$code è matricola
                    showMaintainerSkills($ret, $username2, $code, $name, $email);
                    ?>
                </thead>
            </table>
            <?php echo "<a href='addSkills.php?username=$username2&code=$code&name=$name&email=$email'><input class='button' type='button' value='ASSIGN A NEW SKILL'>"; ?>
        </div>
    </div>
</body>

</html>