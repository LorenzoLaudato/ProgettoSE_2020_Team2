<!DOCTYPE html>

<?php
require_once("../../controller/planner/functions_page1.php");
require_once("../../controller/ServiceFactory.php"); ?>

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
  <title>THE MANAGER OF THE MAINTAINERS</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="container">
    <input type="image" id='icon-info' data-toggle="modal" data-target="#basicExampleModal" src="../../resources/images/info.png"></input>
    <a href="../general/logout.php"><img id="icon-logout" src="../../resources/images/logout.png"></a>
    <h3 id="title"><b>THE MANAGER OF THE MAINTAINERS</b></h3>
    <img id="icon-page1" src="../../resources/images/icona.png">
    <div>
      <div class="row justify-content-start" style="padding-top: 10px">
        <div class="col-sm-4" style="padding-top: 10px">

          <?php $weekNumber = setWeekNumber(); ?>

          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" metod="GET">
            <label style="font-size:25px;" for="weekNumber">Week n°:</label>
            <input type="number" min="1" max="52" style="width:43px;" id="weekNumber" name="weekNumber" value="<?php echo $weekNumber ?>">
            <input class="button" type="submit" value="SEARCH">
          </form>
        </div>
      </div>

      <br>


      <!--VISUALIZZAZIONE DELLE ATTIVITA' DELLA SETTIMANA SELEZIONATA-->
      <table id='activitiesTable' class="table table-striped" border="1" style="visibility: hidden">
        <thead>
          <tr style="background-color:#FFF8C6;">
            <th scope="col">ID</th>
            <th scope="col">AREA</th>
            <th scope="col">TYPE</th>
            <th scope="col">ESTIMATED INTERVENTION TIME [min]</th>
          </tr>

          <?php
          $micro = ServiceFactory::create();
          $ret = $micro->getActivities($weekNumber);

          showInfoActivities($ret, $weekNumber);
          ?>
        </thead>
      </table>

    </div>

  </div>

  <!-- Modal -->

  <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">MAINTENANCE ACTIVITIES OF THE WEEK</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Here you can choose a week by insert the number in the input area to see relative activity maintenance!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


</body>

</html>