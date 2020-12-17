<?php
/*FUNCTIONS FRONT-END PAGE 4*/



/**This functions shows the Maintainers availibility time slot that have at least a skill to carry out the selected activity.
 * Params: $ret: return value of the query done to the DB  about the time slots of the selected Maintainer into that day;
 * $nome: name of the selected Maintainer;
 * $skills: it's the ratio between the skills "Effective" and skills Needed to carry out the selected Activity;
 * $ret1: return value of the query done to the DB getting the Maintainer's email.
 * */
function showMaintainerTimeslots($ret, $nome, $skills, $ret1)
{
    $count = 2;
    $row1 = pg_fetch_row($ret1);
    $email = $row1[0];
    echo "<tr><td>$nome</td>"; //nome
    echo "<td>$skills</td>"; //skills
    while ($row = pg_fetch_row($ret)) {
        $c = "<td name='$count' class='time' value='$email' id='$nome' style='background-color:";
        if ($row[0] == 0)
            $c .= "#c83d2d"; //rosso
        if ($row[0] != 0 && $row[0] < 30)
            $c .= "#FF8C00"; //arancione
        if ($row[0] >= 30 && $row[0] < 50)
            $c .= "yellow"; //giallo
        if ($row[0] >= 50 && $row[0] < 60)
            $c .= "#59bd7a"; //verde chiaro
        if ($row[0] == 60)
            $c .= "#2e8b57"; //verde scuro
        $c .= "'>$row[0] min</td>";
        echo $c;
        $count++;
    }
    echo "</tr>";
}
