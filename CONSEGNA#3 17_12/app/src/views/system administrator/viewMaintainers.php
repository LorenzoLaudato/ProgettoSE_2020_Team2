<?php

require_once("../../controller/systemAdministrator/sa_functions.php");
require_once("../../controller/ServiceFactory.php");

if (!empty($_GET['username'])) {
  $username2 = $_GET['username'];
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
</head>

<body>
  <div class="container">

    <a href='configuration.php?username=<?php echo $username2; ?>'><img id='icon-back' src='../../resources/images/back.png'></a>

    <h3 id="title"><b>SYSTEM ADMINISTRATION</b></h3>
    <img id="icon-page1" src="../../resources/images/system.png">
    <div>
      <br>
      <!--VISUALIZZAZIONE DEI MANUTENTORI-->
      <table id='maintainersView' class="table table-striped" border="1">
        <thead>
          <tr style="background-color:#FFF8C6;">
            <th scope="col">CODE</th>
            <th scope="col">NAME</th>
            <th scope="col">E-MAIL</th>
          </tr>

          <?php
          $micro = ServiceFactory::create();
          $ret = $micro->getMaintainers();
          showMaintainersRegistry($ret, $username2);
          ?>

        </thead>
      </table>
    </div>
  </div>
</body>

</html>