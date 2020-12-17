<?php

class MS_Skills
{
    
    
    /**This method returns all the Skills needed to carry out the selected Activity
     * Params:
     * $code: it represents the identifier of the selected Activity;
     * $db: it represents the resource Database.
     */
    public static function get_skillsNeeded($code, $db)
    {
        $sql = "SELECT S.tipo FROM skills S, richiesta R WHERE S.Sid = R.skill AND R.attivita = $code;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            exit;
        }
        return $ret;
    }


    /**This method returns all the Skills present in the DB.
     */
    public static function get_skills($db)
    {
        $sql = "SELECT * FROM skills ORDER BY sid;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }


    /**This method returns the Skills not already associated to an Activity.
     * Params:
     * $code: it represents the identifier of the activity;
     * $db: it represents the resource Database.
     */
    public static function get_not_associated_skills($db, $code)
    {
        $sql = "SELECT * FROM skills  where sid not in (select skill from richiesta where attivita=$code)ORDER BY sid ;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }


    /**This method returns the number of the skills needed to carry out the selected Activity.
     * Params:
     * $code: it represents the identifier of the selected Activity;
     * $db: it represents the resource Database.
     */
    public static function skillsCountNeeded($code, $db)
    {
        $sql = "SELECT count(*) FROM skills S, richiesta R WHERE S.Sid = R.skill AND R.attivita = $code;";

        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            exit;
        }
        $row = pg_fetch_row($ret);
        $count = $row[0];
        return $count;
    }


    /**This method returns the number of the skills owned by the Maintainers to carry out the selected Activity.
     * Params:
     * $code: it represents the identifier of the selected Activity;
     * $matricola: it represents the identifier of the Maintainer;
     * $db: it represents the resource Database.
     */
    public static function skillsCountEffective($code, $matricola, $db)
    {
        $sql = "SELECT count(*) FROM Skills, Possesso, Manutentore WHERE Skills.tipo IN( SELECT S.tipo FROM skills S, richiesta R WHERE S.Sid = R.skill AND R.attivita = $code) AND manutentore.matricola = possesso.manutentore AND possesso.skill =skills.sid AND manutentore.matricola = '$matricola';";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            exit;
        }
        $row = pg_fetch_row($ret);
        $count = $row[0];
        if ($count == 0) {
            return false;
        } else {
            return $count;
        }
    }


    /**This method updates a Skill present in the DB.
     * Params:
     * $sid: it represents the identifier of the skill;
     * $db: it represents the resource Database.
     */
    public static function update_skill($sid, $db)
    {
        if (!empty($_POST['skill'])) {
            $skill = $_POST['skill']; //prelevo la nuova skill
            $sql_update = "UPDATE skills SET tipo = $1 WHERE sid=$sid;";
            $prep = pg_prepare($db->database, "UpdateSkill", $sql_update);
            if (!$prep) {
                echo pg_last_error($db->database);
            } else {
                $ret_update = pg_execute($db->database, "UpdateSkill", array($skill));
                if (!$ret_update) {
                    $skill = "";
                } else {
                    echo "<script>okEdit('skill','edited','form');</script>";
                    return true;
                }
            }
        }
    }


    /**This method returns the max identifier of the skills present in the DB.
     * Params:
     * $db: it represents the resource Database.
     */
    public static function take_skill($db)
    {
        if (!empty($_POST['skill-input'])) {
            $sql_id = "SELECT max(sid) FROM skills;";
            $ret = pg_query($db->database, $sql_id);
            if (!$ret) {
                exit;
            }
            return $ret;
        }
    }


    /**This method inserts a new Skill in the DB.
     * Params:
     * $maxsid: it represents the max identifier of the skills present in the DB;
     * $db: it represents the resource Database.
     */
    public static function insert_skill($db, $maxsid)
    {
        if (!empty($_POST['skill-input'])) {
            $new_skill = $_POST['skill-input']; //prelevo la nuova skill
            $row = pg_fetch_row($maxsid);
            $sid_max = $row[0]; //sid max esistente nella tabella delle skills
            $new_sid = (int)$sid_max + 1;
            $sql_insert = "INSERT INTO skills(sid,tipo) VALUES ($new_sid,'$new_skill');";
            $ret = pg_query($db->database, $sql_insert);
            if (!$ret) {
                echo "ERRORE QUERY";
                exit;
            } else {
                return true;
            }
        }
    }
}
