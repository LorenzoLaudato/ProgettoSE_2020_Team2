<!DOCTYPE html>
<?php

require_once("../../controller/ServiceFactory.php");
require_once("../../controller/planner/functions_page2.php");
require_once("../../controller/general/general_functions.php");

if (!empty($_GET['code']) && !empty($_GET['weekNumber'])) {
    //Prima controllo se il code e il weekNumber sono stati passati
    $code = $_GET['code'];
    $weekNumber = $_GET['weekNumber'];
    $micro = ServiceFactory::create();


    $ret = $micro->infoActivity($code);

    if ($row = pg_fetch_row($ret)) {
        $area = $row[2];
        $type = $row[1];
        $time = $row[3];
        $info = $row[4];
        $note = $row[5];
        $pdf = $row[6];
        $giorno = $row[9];
    }
}

//CONTROLLO SE CI SONO DA AGGIUNGERE NUOVE SKILLS ALL'ATTIVITA'
if (!empty($_GET['action']) && $_GET['action'] == 'add' && !empty($_POST['skill_to_add'])) {

    $skills = $_POST['skill_to_add']; //prelievo delle skills selezionate dall'utente
    $N = count($skills); // N skills da aggiungere
    //echo("You selected $N skill(s): ");
    for ($i = 0; $i < $N; $i++) {
        $ret = $micro->addSkillToActivity($code, $skills[$i]);
    }

    echo " <script> window.location.href='page2ewo.php?code=$code&weekNumber=$weekNumber'</script>";
}


?>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->

    <title>THE MANAGER OF THE MAINTAINERS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../js/functionJavaScript.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <input type="image" id='icon-info' style='left:80px; top:23px; width:33px;' data-toggle="modal" data-target="#basicExampleModal" src="../../resources/images/info.png"></input>
        <a href="page1.php?code=<?php echo $code; ?>&weekNumber=<?php echo $weekNumber; ?>"><img id="icon-back" src="../../resources/images/back.png"></a>
        <h3 id="title"><b>THE MANAGER OF THE MAINTAINERS</b></h3>
        <div>
            <!--RIGA 1-->
            <div class="row" style="padding-top: 10px;">
                <div class="col-sm-2" style="padding-top: 10px;">
                    <label style="font-size:25px;">Week n°:</label>
                </div>
                <div class="col-sm-1 row1">
                    <b><?php echo $weekNumber ?></b>
                </div>
                <div class="col-sm-3" style="padding-top: 12px;">
                    <label style="background-color:#c83d2d;color:yellow;font-size:24px;">Activity to assign</label>
                </div>
                <div class="col-sm-6 row1">
                    <b style="font-size:20px"><?php echo 'EWO ' . $code . ' - ' . $area . ' - ' . $type ?></b>
                </div>

            </div>
            <br>
            <!--RIGA 2-->
            <div class="row" style="padding-top: 12px;">
                <div class="col-sm-2" style="padding-top: 10px;">
                    <label style="font-size:22px;"><?php echo getDayName($giorno); ?></label>
                </div>
                <div class="col-sm-1 row1">
                    <b id='numDay'><?php echo "<script>document.getElementById('numDay').innerHTML= getDayFromWeek($giorno, $weekNumber);</script>" ?></b>
                </div>
            </div>
            <br>
            <!--RIGA 3-->
            <div class="row" style="padding-top: 10px">
                <div class="col-sm-4 row2">
                    <label style="font-size:20px;"><b>Workspace Notes</b></label>
                </div>
                <div class="col-sm-4 row2">
                    <label style="font-size:20px;"><b>Intervention description</b></label>
                </div>
                <div class="col-sm-4 row2">
                    <label style="font-size:20px;"><b>Skills to add</b></label>
                </div>
            </div>
            <!--RIGA 4-->
            <div class="row" style="padding-top: 10px">
                <div class="col-sm-4 row3">
                    <?php echo $note ?>
                </div>
                <!--DESCRIZIONE DELL'INTERVENTO-->
                <div class="col-sm-4">
                    <div id='div-info'>
                        <form id="form-info" method="POST">
                            <textarea class='textarea' name="info" id="info" rows="5" cols="39"><?php echo $info ?></textarea>
                            <br>
                            <input id="edit-submit" type="submit" name="edit-submit" value="ADD">
                            <br>
                        </form>
                    </div>
                </div>
                <?php
                $micro->updateInterventionDescription($code);
                ?>
                <!--LISTA DELLE SKILLS NECESSARIE-->
                <div class="col-sm-4 row3">

                    <form id="form-skills" action="<?php echo "page2ewo.php?action=add&code=$code&weekNumber=$weekNumber" ?>" method='POST'>
                        <ul id="skills" name="skills" style="padding-left: 10px;">
                            <?php
                            $ret = $micro->getNotAssociatedSkills($code);
                            showSkillsToCheck($ret);
                            ?>
                            <input id="add-submit" name="add-submit" class='button' type='submit' value='ADD SKILLS'>
                            <?php if (pg_numrows($ret) == 0) {
                                echo "<script> document.getElementById('add-submit').style.display = 'none';
                                </script>";
                            }
                            ?>
                        </ul>

                    </form>
                </div>
            </div>
            <!--RIGA 5-->
            <div class="row" style="padding-top: 10px">
                <div class="col-sm-4 row1">
                    <label style="font-size:20px;"><b>Skills Needed</b></label>
                    <ul id="skills-needed" name="skills-needed" style="padding-left: 10px;">
                        <?php
                        $ret = $micro->getSkillsNeeded($code);
                        showSkillsNeeded($ret);
                        ?>
                    </ul>
                </div>
                <div class="col-sm-4 row4">
                    <div id='div-time'>
                        <form id="form-time" method="POST">
                            <label for='estimated-time' style="font-size:15px;"><b>Estimated time required:</b></label>
                            <input type="number" min="10" style="width:43px;" id="estimated-time" name="estimated-time" value='<?php echo $time ?>'>
                            <label>min</label>
                            <br>
                            <input class="button" id="edit-submit" type="submit" name="edit-submit" value="EDIT">
                        </form>
                    </div>
                    <br>
                </div>
                <?php
                $micro->updateEstimatedTime($code);
                ?>

                <div class="col-sm-4">
                    <?php
                    echo <<<_HTML
                    <a href="page3ewo.php?code=$code&weekNumber=$weekNumber&giorno=$giorno"><input class="button" type="button" value="FORWARD"></a>
                _HTML;
                    ?>
                </div>
            </div>


        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">VIEW OF THE SELECTED ACTIVITY</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Here you can verify, from the intervention information, the skills and the resources needed for the intervention. Moreover, you may update the notes relating to the selected Activity!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>