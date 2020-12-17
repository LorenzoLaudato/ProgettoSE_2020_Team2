<?php

/**FRONT-END FUNCTIONS USED BY SYSTEM ADMINISTRATOR */




/**This functions shows the Maintainers skills.
 * Params: $ret: return value of the query done to the DB  about the skills of each Maintainer;
 * $username: username of the system administrator logged;
 * $code: it's the code of the Activity;
 * $name: it's Maintainer's name;
 * $email: it's Maintainer's email.
 * */
function showMaintainerSkills($ret, $username, $code, $name, $email)
{
    if ($ret != False) {
        //row[0] contiene l'id di ogni skill
        while ($row = pg_fetch_row($ret)) {
            //serializzo l'array in formato string
            $serialized = "";
            for ($i = 0; $i < count($row); $i++) {
                $serialized .= "'" . $row[$i] . "',";
            }
            //valorizzo l'array
            echo "<script>var riga = [" . $serialized . "];</script>";
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?username=' . $username . '&action=delete&code=' . $code . '&name=' . $name . '&email=' . $email . '&sid=' . $row[0] . '"><input class="button" type="button" value="DELETE"></a></td>';
            echo "</tr>";
        }
    };
}


/**This functions shows the Maintainer skills in a checkbox list.
 * Params: $ret: return value of the query done to the DB  about all the skills.
 * */
function showCheckListSkills($ret)
{
    if ($ret != False) {
        //row[0] contiene l'id.
        while ($row = pg_fetch_row($ret)) {
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
                    <td style='text-align:left;'>$row[1]</td>
                    <td><input type="checkbox" id="skill-id" name="skill_to_add[]" value="$row[0]"></td>
                    </tr>

                _HTML;
        }
    };
}


/**This functions shows the registry of all the Maintainers.
 * Params: $ret: return value of the query done to the DB  about all the Maintainers;
 * $username: username of the system administrator logged.
 * */
function showMaintainersRegistry($ret, $username)
{
    if ($ret != False) {
        //row[0] contiene l'id.
        while ($row = pg_fetch_row($ret)) {
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
                        <td>$row[1]</td>
                        <td>$row[2]</td>
                        <td><a href="maintainerSkills.php?username=$username&code=$row[0]&name=$row[1]&email=$row[2]"><input class="button" type="button" value="SELECT"></a></td>                            </tr>
                    _HTML;
        }
    }
}


/**This functions shows all the skills present in the DB.
 * Params: $ret: return value of the query done to the DB  about all the skills;
 * $username: username of the system administrator logged.
 * */
function showAllSkills($ret, $username)
{
    if ($ret != False) {
        //row[0] contiene l'id.
        while ($row = pg_fetch_row($ret)) {
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
                        <td style='text-align:left;'>$row[1]</td>
                        <td><a href="editSkill.php?sid=$row[0]&skill=$row[1]&username=$username"><input class="button" type="button" value="EDIT"></a></td>
                        </tr>
                    _HTML;
        }
    }
}


/**This functions shows all the users present in the DB without the current logged user.
 * Params: $ret: return value of the query done to the DB  about all the users present in the DB without the current logged user;
 * $username: username of the system administrator logged.
 * */
function showUsers($ret, $username)
{
    if ($ret != False) {
        //row[0] contiene l'id.
        while ($row = pg_fetch_row($ret)) {
            //serializzo l'array in formato string
            $serialized = "";
            for ($i = 0; $i < count($row); $i++) {
                $serialized .= "'" . $row[$i] . "',";
            }
            //valorizzo l'array
            echo "<script>var riga = [" . $serialized . "];</script>";
            echo <<<_HTML
            <tr> 
            <td>$row[3]</td>
            <td>$row[0]</td>
            <td>$row[1]</td>
            <td><a href="editUsers.php?role=$row[3]&name=$row[0]&username1=$row[1]&username=$username"><input class="button" type="button" value="SELECT"></a></td>
        _HTML;
            echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?username=' . $username . '&action=delete&username1=' . $row[1] . '"> <input class="button" type="button" value="DELETE"> </a></td>';
            echo "</tr>";
        }
    }
}


/**This functions shows all the assigned Activities to every Maintainer .
 * Params: $ret: return value of the query done to the DB about all the planned assigned Activities to every Maintainer;
 * */
function showAllAssignedActivities($ret)
{

    while ($row = pg_fetch_row($ret)) {
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
        <td>$row[1]</td>
        <td>$row[2]</td>
        <td>$row[3]</td>
        <td>$row[4]</td>
    _HTML;
    }
}
