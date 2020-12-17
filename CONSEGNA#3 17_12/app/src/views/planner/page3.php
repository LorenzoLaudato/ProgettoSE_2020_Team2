<!DOCTYPE html>
<?php


require_once("../../controller/ServiceFactory.php");
require_once("../../controller/planner/functions_page3.php");
require_once("../../controller/general/general_functions.php");

if (!empty($_GET['code']) && !empty($_GET['weekNumber'])) {
    //Prima controllo se il code e il weekNumber sono stati passati
    $code = $_GET['code'];
    $weekNumber = $_GET['weekNumber'];

    $micro = ServiceFactory::create();

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
    <script src="../../js/planner/page3.js"></script>

    <title>THE MANAGER OF THE MAINTAINERS</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <input type="image" id='icon-info' style='left:80px; top:23px; width:33px;' data-toggle="modal" data-target="#basicExampleModal" src="../../resources/images/info.png"></input>
        <?php
        echo "<a href='page2.php?code=$code&weekNumber=$weekNumber'><img id='icon-back' src='../../resources/images/back.png'></a>";
        echo "<a href='page1.php?code=$code&weekNumber=$weekNumber'><img id='icon-home' src='../../resources/images/home.png'></a>";
        ?>
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
                    <label style="font-size:24px;">Activity to assign</label>
                </div>
                <div class="col-sm-6 row1">
                    <b style="font-size:20px"><?php echo $code . ' - ' . $area . ' - ' . $type . ' - ' . $time . ' min' ?></b>
                </div>

            </div>
            <br>
            <!--RIGA 2-->
            <div class="row" style="padding-top: 10px">
                <div class="col-sm-3 row2">
                    <label style="font-size:20px;"><b>Skills needed</b></label>
                </div>
                <div class="col-sm-9 row2">
                    <label style="font-size:20px;"><b>Maintainers AVAILABILITY</b></label>
                </div>

            </div>
            <!--RIGA 3-->
            <div class="row" style="padding-top: 10px">
                <!--LISTA DELLE SKILLS NECESSARIE-->
                <div class="col-sm-3 row3">
                    <ul style="padding-left: 10px;">
                        <!--Tra le informazioni da mostrare nella pag2 ci sono anche le competenze richieste per svolgere l'attività. -->
                        <?php
                        $ret = $micro->getSkillsNeeded($code);
                        showSkillsNeeded($ret);
                        ?>
                    </ul>
                </div>

                <div class="col-sm-9 row3">
                    <!--VISUALIZZAZIONE DISPONIBILITA' DEI MAINTAINER-->
                    <table id='maintainersTable' class="table table-striped" border="1">
                        <thead>
                            <tr style="background-color:#FFF8C6;font-size:12px;text-align:center;">
                                <th scope="col">Maintainer</th>
                                <th scope="col">Skills</th>
                                <th scope="col">Availab. Mon</th>
                                <th scope="col">Availab. Tue</th>
                                <th scope="col">Availab. Wed</th>
                                <th scope="col">Availab. Thu</th>
                                <th scope="col">Availab. Fri</th>
                                <th scope="col">Availab. Sat</th>
                                <th scope="col">Availab. Sun</th>
                            </tr>
                            <?php
                            $ret = $micro->showMaintainers();
                            showMaintainerTable($ret, $code, $weekNumber, $micro);
                            ?>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">VIEW OF THE AVAILABLE MAINTAINERS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Here you can select the Maintainer based on his availibility!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</body>

</html>