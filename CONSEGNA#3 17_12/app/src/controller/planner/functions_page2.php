<?php


/*FRONT-END FUNCTIONS PAGE 2*/


/**This functions shows the skills inserted in the DB through a checkBox.
 * Params: $ret: return value of the query done to the DB  about all skills present in the DB*/
function showSkillsToCheck($ret)
{
    // le competenze in $ret sono tutte visualizzate e possono essere selezionate dall'utente mediante opportune checkbox
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
                                    <input type="checkbox" id="skill-id" name="skill_to_add[]" value="$row[0]">     
                                    $row[1]
                                    <br>
                                        
                                    _HTML;
        }
    };
}
