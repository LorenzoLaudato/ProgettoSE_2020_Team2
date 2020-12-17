<?php


class MS_Activity
{


    /**This method returns all the Informations of the selected Activity
     * Params:
     * $code: it represents the identifier of the selected Activity;
     * $db: it represents the resource Database.
     */
    public static function info_activity($code, $db)
    {
        
        $sql = "SELECT * FROM Attivita WHERE code = $code ORDER BY code;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            exit;
        }
        return $ret;
    }


    /**This method returns all the Activities scheduled in the selectedWeekNumber
     * Params:
     * $weekNumber: it represents the weekNumber selected by user;
     * $db: it represents the resource Database.
     */
    public static function get_activities($weekNumber, $db)
    {
        $sql = "SELECT * FROM Attivita WHERE nsettimana = $weekNumber ORDER BY code;";
        $ret = pg_query($db->database, $sql);
        if (pg_num_rows($ret) == 0) {
            echo "NO MAINTENANCE ACTIVITIES IN THIS WEEK";
            exit;
        }
        return $ret;
    }


    /**This method updates the Note attribute in the DB.
     * Params:
     * $code: it represents the code of the selected Activity;
     * $db: it represents the resource Database.
     */
    public static function update_note($code, $db)
    {
        if (!empty($_POST['note'])) {
            $note = $_POST['note'];
            $sql_update = "UPDATE Attivita SET note = $1 WHERE code=$code;";
            $prep = pg_prepare($db->database, "UpdateAttività", $sql_update);
            if (!$prep) {
                echo pg_last_error($db->database);
            } else {
                $ret_update = pg_execute($db->database, "UpdateAttività", array($note));
                //return $ret_update;
                if (!$ret_update) {
                    $note = "";
                } else {
                    echo "<script>okEdit('note','edited','form');</script>";
                }
            }
        }
    }


    /**This method add a Skill to the Activity.
     * Params:
     * $code: it represents the identifier of the activity by updating;
     * $sid: it represents the identifier of the new Skill to assign to the Activity;
     * $db: it represents the resource Database.
     */
    public static function add_skill_to_activity($code, $sid, $db)
    {

        //ASSOCIO UNA NUOVA SKILL ALL'ATTIVITA CON ID $code
        $sql_insert = "INSERT INTO richiesta(attivita,skill) VALUES ($code,$sid);";
        $ret = pg_query($db->database, $sql_insert);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return true;
    }


    /**This method updates the description of the intevention of an Activity.
     * Params:
     * $code: it represents the identifier of the activity;
     * $db: it represents the resource Database.
     */
    public static function update_intervention_description($code, $db)
    {

        if (!empty($_POST['info'])) {
            $info = $_POST['info'];
            $sql_update = "UPDATE Attivita SET descrizione = $1 WHERE code=$code;";
            $prep = pg_prepare($db->database, "UpdateAttività", $sql_update);
            if (!$prep) {
                echo pg_last_error($db->database);
            } else {
                $ret_update = pg_execute($db->database, "UpdateAttività", array($info));
                //return $ret_update;
                if (!$ret_update) {
                    $info = "";
                } else {
                    echo "<script>okEdit('form-info','edited','div-info');</script>";
                }
            }
        }
    }


    /**This method updates the time of the intevention of an Activity.
     * Params:
     * $code: it represents the identifier of the activity;
     * $db: it represents the resource Database.
     */
    public static function update_estimated_time($code, $db)
    {

        if (!empty($_POST['estimated-time'])) {
            $time = $_POST['estimated-time'];
            $sql_update = "UPDATE Attivita SET tempointervento = $1 WHERE code=$code;";
            $prep = pg_prepare($db->database, "UpdateAttività", $sql_update);
            if (!$prep) {
                echo pg_last_error($db->database);
            } else {
                $ret_update = pg_execute($db->database, "UpdateAttività", array($time));
                //return $ret_update;
                if (!$ret_update) {
                    $time = "";
                } else {
                    echo "<script>okEdit('form-time','edited','div-time');</script>";
                }
            }
        }
    }


    /**This method returns the Activities already assigned to different Maintainers.
     * Params:
     * $db: it represents the resource Database.
     */
    public static function get_AssignedActivities($db)
    {
        $sql = " SELECT svolgimento.manutentore, manutentore.nome, svolgimento.attivita, attivita.area, attivita.tipo FROM SVOLGIMENTO, ATTIVITA, MANUTENTORE
        WHERE svolgimento.manutentore = manutentore.matricola AND svolgimento.attivita = attivita.code;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }


    /**This method assign an Activities to a specific Maintainer.
     * Params:
     * $code: it represents the identifier of the activity;
     * $matricola: it represents the identifier of the Maintainer;
     * $db: it represents the resource Database.
     */
    public static function update_assigned_activity($code, $matricola, $db)
    {
        //CONTROLLO CHE L'ATTIVITA' NON SIA STATA GIA' ASSEGNATA (CIOè IL SUO CODICE NON SIA PRESENTE NELLA TABELLA SVOLGIMENTO)
        $sql = "SELECT attivita FROM Svolgimento WHERE attivita = $code;";
        $ret = pg_query($db->database, $sql);
        if (pg_num_rows($ret) == 0) { //ATTIVITA' NON ANCORA ASSEGNATA

            $sql = "INSERT INTO SVOLGIMENTO(attivita, manutentore) VALUES($code, '$matricola');";
            $ret = pg_query($db->database, $sql);
            return true;
        } else {
            return false; //ATTIVITA GIA' ASSEGNATA
        }
    }
}
