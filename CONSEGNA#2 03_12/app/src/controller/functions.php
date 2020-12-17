<?php
    //qui vengono implementate le funzioni di utility
    function setWeekNumber(){ //prelievo weeknumber 
        if (isset($_GET['weekNumber']))
            $weekNumber = $_GET['weekNumber'];
          else
            $weekNumber = "";

        return $weekNumber;
    }
    

    ?>