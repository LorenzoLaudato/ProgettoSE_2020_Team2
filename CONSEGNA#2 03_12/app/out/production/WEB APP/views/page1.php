<!DOCTYPE html>

<?php
include("../controller/functions.php");
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
      
      <!--Ogni azione nella pagina HTML riflette una particolare chiamata a funzione. Dal momento che l'architettura presenta
        la dataServiceInterface, ogni azione chiamerà una sola funzione qui detta dataServiceInterface. Quest'ultima sarà implementata richiamando il microservizio appropriato-->

      <!--VISUALIZZAZIONE DELLE ATTIVITA' DELLA SETTIMANA SELEZIONATA-->
      <?php dataService_interface("SEARCH ACTIVITIES BY WEEK", $weekNumber); ?>
      
    </div>
  </div>
</body>

</html>