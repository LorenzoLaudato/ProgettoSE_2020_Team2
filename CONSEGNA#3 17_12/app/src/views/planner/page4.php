<!DOCTYPE html>
<?php

require_once("../../controller/ServiceFactory.php");
require_once("../../controller/planner/functions_page4.php");
require_once("../../controller/general/general_functions.php");

$micro = ServiceFactory::create();


if (!empty($_GET['code']) && !empty($_GET['weekNumber']) && !empty($_GET['nome']) && !empty($_GET['giorno']) && !empty($_GET['percentuale']) && !empty($_GET['matricola']) && !empty($_GET['skills'])) {
    //Prima controllo se il code e il weekNumber sono stati passati
    $code = $_GET['code'];
    $weekNumber = $_GET['weekNumber'];
    $nome = $_GET['nome'];
    $giorno = $_GET['giorno'];
    $percentuale = $_GET['percentuale'];
    $matricola = $_GET['matricola'];
    $skills = $_GET['skills'];
    $ret = $micro->getMaintainerEmail($matricola);
    $row = pg_fetch_row($ret);
    $maintainer_email = $row[0];
    $ret = $micro->infoActivity($code);
    // La pagina2 deve mostrare le informazioni dell'attività selezionata dall'utente. Con questa chiamata a funzione richiediamo le informazioni della tabella Attività presente nel db.

    if ($row = pg_fetch_row($ret)) {
        $area = $row[2];
        $type = $row[1];
        $time = $row[3];
        $info = $row[4];
        $note = $row[5];
        $pdf = $row[6];
    }
}

if (!empty($_GET['action']) && $_GET['action'] == 'update') {
    $micro->updateAssignedActivity($code, $matricola);
    echo " <script> window.location.href='page4.php?code=$code&weekNumber=$weekNumber&giorno=$giorno&matricola=$matricola&nome=$nome&percentuale=$percentuale&skills=$skills' </script>";
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
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="../../js/functionJavaScript.js"></script>
    <script src="../../js/planner/page4.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">

        <input type="image" id='icon-info' style='left:80px; top:23px; width:33px;' data-toggle="modal" data-target="#basicExampleModal" src="../../resources/images/info.png"></input>
        <a href="page3.php?code=<?php echo $code; ?>&weekNumber=<?php echo $weekNumber; ?>"><img id="icon-back" src="../../resources/images/back.png"></a>
        <?php echo "<a href='page1.php?code=$code&weekNumber=$weekNumber'><img id='icon-home' src='../../resources/images/home.png'></a>"; ?>
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
                <div class="col-sm-2" style="padding-top: 12px;">
                    <label style="font-size:20px;">Activity to assign</label>
                </div>
                <div class="col-sm-3 row1">
                    <b style="font-size:15px"><?php echo  $code . ' - ' . $area . ' - ' . $type ?></b>
                </div>
                <div class="col-sm-4 row1 " style='background-color:yellow;'>
                    <b style="font-size:20px"><?php echo 'Required TIME:' ?></b>
                    <br>
                    <b id='total' style="font-size:18px"><?php echo $time ?></b>
                    <b style="font-size:18px"> min</b>


                </div>

            </div>

            <!--RIGA 2-->
            <div class="row" style="padding-top: 12px;">
                <div class="col-sm-2" style="padding-top: 10px;">
                    <label style="font-size:22px;"><?php echo getDayName($giorno); ?></label>
                </div>
                <div class="col-sm-1 row1">
                    <b id='numDay'><?php echo "<script>document.getElementById('numDay').innerHTML= getDayFromWeek($giorno, $weekNumber);</script>" ?></b>
                </div>
                <div class="col-sm-2">
                </div>
                <div class="col-sm-3">
                </div>
                <div class="col-sm-2 row1" style='background-color:#59bd7a;'>
                    <b style="font-size:15px"><?php echo 'Assigned TIME:' ?></b>
                    <br>
                    <b id='count1' style="font-size:18px">0</b>
                    <b style="font-size:18px"> min</b>

                </div>
                <div class="col-sm-2 row1" style="background-color:#c83d2d;">
                    <b style="color:yellow;font-size:15px"><?php echo 'TIME to be assigned:' ?></b>
                    <br>
                    <b id='count2' style="color:yellow;font-size:18px"><?php echo $time ?></b>
                    <b style="color:yellow;font-size:18px"> min</b>
                </div>
            </div>
            <br>
            <!--RIGA 3-->
            <div class="row" style="padding-top: 10px">
                <div class="col-sm-3 row2">
                    <label style="font-size:20px;"><b>Workspace Notes</b></label>
                </div>
                <div class="col-sm-9 row2">
                    <label style="font-size:20px;"><b>AVAILABILITY <?php echo $nome ?> <?php echo "<label id='perc'>$percentuale</label><label id='percSymbol'> %</label>" ?></b></label>
                </div>

            </div>
            <!--RIGA 4-->
            <div class="row" style="padding-top: 10px">
                <!--WORKSPACE NOTE-->
                <div class="col-sm-3">
                    <textarea readonly class='textarea' name="note" id="note" rows="5" cols="30"><?php echo $note ?></textarea>
                </div>

                <div class="col-sm-9 row3">
                    <!--VISUALIZZAZIONE ORARI DEL MAINTAINER SELEZIONATO-->
                    <table id='maintainerTable' class="table table-striped" border="1">
                        <thead>
                            <tr style="background-color:#FFF8C6;font-size:12px;text-align:center;">
                                <th scope="col">Maintainer</th>
                                <th scope="col">Skills</th>
                                <th scope="col">Availab. <label style="font-size:8px;">8:00-9:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">9:00-10:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">10:00-11:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">11:00-12:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">12:00-13:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">14:00-15:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">15:00-16:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">16:00-17:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">17:00-18:00</label></th>
                                <th scope="col">Availab. <label style="font-size:8px;">18:00-19:00</label></th>
                            </tr>
                            <?php
                            $ret = $micro->getTimeslot($matricola, $giorno);
                            $ret1 = $micro->getMaintainerEmail($matricola);
                            showMaintainerTimeslots($ret, $nome, $skills, $ret1);

                            ?>
                    </table>
                </div>

            </div>
            <!--RIGA 5-->
            <div class="row" style="padding-top: 10px">
                <div class="col-8" style="padding-top: 10px;padding-left:545px;">
                    <button onclick="<?php $micro->updateAssignedActivity($code, $matricola); ?>" id="send-button" class="button" disabled style='background-color: #cccccc;color: #666666;'><a id='a-send' href='#' value="mailto:<?php echo $maintainer_email; ?>?subject=Assignment of a planned activity&cc=production.managerscrum2020@gmail.com
                    &body=Dear <?php echo $nome; ?>,%0Daccording to your availibility, I have assigned to you the intervention with the following characteristics:
                    %0DArea - <?php echo $area; ?>;%0DType - <?php echo $type; ?>;%0DNote - <?php echo $note; ?>;%0DDescription of the intervention - <?php echo $info; ?>;">SEND</a></button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">VIEW OF THE AVAILABLE TIME-SLOTS OF THE SELECTED MAINTAINER</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Here you can select the Maintainer and the time and send the communication to him!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>