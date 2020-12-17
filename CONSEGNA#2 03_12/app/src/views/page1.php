<!DOCTYPE html>

<?php
require_once ("../controller/functions.php");
require_once ("../controller/DatabaseConnection.php");
require_once("../controller/Service.php");
require_once ("../controller/logindb.php");
?>

<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/functionJavaScript.js"></script>
</head>

<body>
  <div class="container">
    <h3 id="title"><b>THE MANAGER OF THE MAINTAINERS</b></h3>
    <img id="icon-page1" src="../resources/images/icona.png">
    <div>
      <div class="row justify-content-start" style="padding-top: 10px">
        <div class="col-sm-4" style="padding-top: 10px">

          <?php $weekNumber = setWeekNumber(); ?>

          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" metod="GET">
            <label style="font-size:25px;" for="weekNumber">Week n°:</label>
            <input type="number" min="1" style="width:43px;" id="weekNumber" name="weekNumber" value="<?php echo $weekNumber ?>">
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
      //CONNESSIONE AL DATABASE
      $database = new DatabaseConnection($host, $db, $username, $password);
      $database->getDB();
      $micro = new Service($database);
      $ret = $micro->getActivities($weekNumber);
      $database->closeDB();

      if ( $ret!= False ){
          echo "<script> setVisibility()</script>";
          //row[0] contiene l'id.
          while ($row = pg_fetch_row($ret)) {
              $code = $row[0];
              $code = (int)$code;
              //serializzo l'array in formato string
              $serialized = "";
              for ($i = 0; $i < count($row); $i++) {
                  $serialized .= "'" . $row[$i] . "',";
              }
              //valorizzo l'array
              echo "<script>var riga = [" . $serialized . "];</script>";
              echo <<<_HTML
                            <tr> 
                            <td>$row[0]</td>
                            <td>$row[2]</td>
                            <td>$row[1]</td>
                            <td>$row[3]</td>
                            <td><a href="page2.php?code=$code&weekNumber=$weekNumber"><input class="button" type="button" value="SELECT"></a></td>
                            </tr>
                        _HTML;
          }
          echo "</thead>";
          echo "</table>";
      };



      ?>

    </div>
  </div>
</body>

</html>