<?php

/*FRONT-END FUNCTIONS PAGE 1*/


/**This functions takes the weekNumber, if selected, from the URL and returns it */
function setWeekNumber()
{
    if (isset($_GET['weekNumber']))
        $weekNumber = $_GET['weekNumber'];
    else
        $weekNumber = "";
    return $weekNumber;
}


/**This functions shows the information about the activities. 
 * Params: $ret: return value of the query done to the DB  about all the activities scheduled during the weeknumber selected; 
 * $weekNumber: it's the weekNumber selected by user.*/
function showInfoActivities($ret, $weekNumber)
{
    //il risultato della query corrispondente è posto in $ret, che in questa funzione viene processato per essere visualizzato correttamente
    if ($ret != False) {
        echo "<script> setVisibility()</script>";
        //row[0] contiene l'id.
        while ($row = pg_fetch_row($ret)) {
            $code = $row[0];
            //$code = (int)$code;
            //serializzo l'array in formato string
            $serialized = "";
            for ($i = 0; $i < count($row); $i++) {
                $serialized .= "'" . $row[$i] . "',";
            }
            //valorizzo l'array
            echo "<script>var riga = [" . $serialized . "];</script>";
            echo <<<_HTML
                        <tr id = '$row[0]' > 
                        <td>$row[0]</td>
                        <td>$row[2]</td>
                        <td>$row[1]</td>
                        <td id='time-$row[0]'>$row[3]</td>
                       
                    _HTML;

            $tipoAttivita = $row[8];
            if ($tipoAttivita == 'planned') {
                echo  "<td><a href='page2.php?code=$code&weekNumber=$weekNumber'><input class='button' type='button' value='SELECT'></a></td>";
                echo "</tr>";
            } elseif ($tipoAttivita == 'ewo') {
                if ($row[3] == '') {
                    echo "<script>document.getElementById('time-$row[0]').innerHTML= 'EWO';</script>";
                }
                echo "<script>document.getElementById('$row[0]').style.color='#c83d2d';
                          document.getElementById('$row[0]').style.font='bold';</script>";
                echo  "<td><a href='page2ewo.php?code=$code&weekNumber=$weekNumber'><input class='button' type='button' value='SELECT'></a></td>";
                echo "</tr>";
            }
        }
    }
}
