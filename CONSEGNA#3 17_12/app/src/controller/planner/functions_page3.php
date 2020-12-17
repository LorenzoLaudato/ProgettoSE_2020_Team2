<?php

require_once("functions_page4.php");

/*FRONT-END FUNCTIONS PAGE 3*/



/**This functions shows the Maintainers that have at least a skill to carry out the selected activity.
 * Params: $ret: return value of the query done to the DB  about every Maintainer that have at least a skill;
 * $code: code used to identify uniquely the selected activity;
 * $weekNumber: it's the weekNumber selected by user;
 * $micro: object of the class Service, used to query some services to the DB.
 * */
function showMaintainerTable($ret, $code, $weekNumber, $micro)
{
    $i = 0;
    while ($row = pg_fetch_row($ret)) {
        $count2 = $micro->countSkillsNeeded($code); //conta le skills necessarie
        $count1 = $micro->countSkillsEffective($code, $row[3]); //conta le skills effettive
        $toShow = '';
        $toShow .= $count1 . '/' . $count2; //è calcolato questo rapporto per rendere nota al planner delle competenze necessarie possedute da ciascun manutentore
        if ($count1 != False) {
            if ($i == 0) {
                echo "<tr><td>$row[2]</td>"; //nome
                echo "<td>$toShow</td>"; //Confronto skills effettivamente possedute - skills necessarie
            }
            //colori diversi a seconda della disponibilità del manutentore:
            $c = "<td class='perc' style='background-color:";
            if ($row[0] != 0) {
                if ($row[0] <= 20) {
                    $c .= "#FF8C00"; //perc lunedi
                }
                if ($row[0] > 20 and $row[0] < 50) {
                    $c .= "yellow"; //perc lunedi
                }
                if ($row[0] >= 50 and $row[0] != 100) {
                    $c .= "#59bd7a"; //perc lunedi
                }
                if ($row[0] == 100) {
                    $c .= "#2e8b57"; //perc lunedi
                }
                $c .= ";'><a href='page4.php?code=$code&weekNumber=$weekNumber&giorno=$row[1]&matricola=$row[3]&nome=$row[2]&percentuale=$row[0]&skills=$toShow";
            } else {
                $c .= "#c83d2d;";
            }
            $c .= "'>$row[0]%</td>";
            echo $c;
            if ($i == 6) {
                echo "</tr>"; //chiudi riga
                $i = 0;
            } else {
                $i++;
            }
        }
    }
}



// FRONT-END PAGE 3 EWO

/**This functions shows the Maintainers availibility time slot that have at least a skill to carry out the selected activity.
 * Params: $ret_maintainers: return value of the query done to the DB  about all the Maintainers;
 * $code: code used to identify uniquely the selected activity;
 * $giorno: it's the day selected by user;
 * $micro: object of the class Service, used to query some services to the DB.
 * */
function showMaintainerEWOTimeSlot($ret_maintainers, $micro, $giorno, $code)
{
    while ($row = pg_fetch_row($ret_maintainers)) {
        $matricola = $row[0];
        $nome = $row[1];
        $ret = $micro->getTimeslot($matricola, $giorno);
        $count2 = $micro->countSkillsNeeded($code); //conta le skills necessarie
        $count1 = $micro->countSkillsEffective($code, $matricola); //conta le skills effettive
        $skills = '';
        if ($count1 != false) {
            $skills .= $count1 . '/' . $count2;
            $ret1 = $micro->getMaintainerEmail($matricola);
            showMaintainerTimeslots($ret, $nome, $skills, $ret1);
        }
    }
}
