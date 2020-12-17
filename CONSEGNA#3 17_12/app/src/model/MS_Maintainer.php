<?php

class MS_Maintainer
{
    
    
    /**This method returns alle the Maintainers available in that week.
     * Params:
     * $db: it represents the resource Database.
     */
    public static function show_maintainers($db)
    { 

        $sql = "SELECT percGiornata, giorno, nome, matricola FROM giorni JOIN manutentore ON giorni.manutentore = manutentore.matricola ORDER BY (nome,giorno);";
        $ret = pg_query($db->database, $sql);

        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            exit;
        }
        return $ret;
    }


    /**This method returns the availibility minutes of a Maintainer on a specific day.
     * Params:   
     * $matricola: it represents the identifier of the Maintainer;
     * $giorno: it represents the day by checking;
     * $db: it represents the resource Database.
     */
    public static function get_timeslot($matricola, $giorno, $db)
    {
        $sql = "SELECT perc60min FROM fasciaoraria WHERE manutentore='$matricola' AND giorno = $giorno ORDER BY fascia;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }


    /**This method returns all the Maintainers present in the DB.
     * Params:
     * $db: it represents the resource Database.
     */
    public static function get_maintainers($db)
    {
        $sql = "SELECT * FROM  manutentore ORDER BY matricola;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }


    /**This method returns the skills of a Maintainer.
     * Params:   
     * $matricola: it represents the identifier of the Maintainer;
     * $db: it represents the resource Database.
     */
    public static function get_maintainer_skills($matricola, $db)
    {
        $sql = "SELECT sid, tipo FROM skills JOIN possesso ON skill=sid WHERE manutentore = '$matricola';";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }


    /**This method delete a skill of a Maintainer.
     * Params:   
     * $matricola: it represents the identifier of the Maintainer;
     * $sid: it represents the identifier of the skill of the Maintainer by deleting;
     * $db: it represents the resource Database.
     */
    public static function delete_maintainer_skill($matricola, $sid, $db)
    {
        $sql = "DELETE FROM possesso WHERE manutentore = '$matricola' AND skill = $sid;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return true;
    }


    /**This method assigns a new Skill to a Maintainer.
     * Params:
     * $matricola: it represents the identifier of the Maintainer;
     * $sid: it represents the identifier of the Skill;
     * $db: it represents the resource Database.
     */
    public static function add_selected_skill($matricola, $sid, $db)
    {
        //CONTROLLO CHE IL MANUTENTORE NON ABBIA GIA' LA SKILLS SELEZIONATA
        $sql = "SELECT skill FROM possesso WHERE manutentore = '$matricola' AND skill = $sid;";
        $ret = pg_query($db->database, $sql);
        if (pg_num_rows($ret) == 0) {
            //ASSOCIO UNA NUOVA SKILL AL MANUTENTORE
            $sql_insert = "INSERT INTO possesso(manutentore,skill) VALUES ('$matricola',$sid);";
            $ret = pg_query($db->database, $sql_insert);
            if (!$ret) {
                echo "ERRORE QUERY";
                exit;
            }
            return true;
        } else {
            return false; //IL MANUTENTORE GIA' POSSIEDE LA SKILL
        }
    }


    /**This method returns the email of the Maintainer.
     * Params:
     * $matricola: it represents the identifier of the Maintainer;
     * $db: it represents the resource Database.
     */
    public static function get_maintainer_email($matricola, $db)
    {
        $sql = "SELECT email FROM manutentore WHERE matricola = '$matricola';";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "Email non presente";
            exit;
        }
        return $ret;
    }


    /**This method returns the name of the Maintainer.
     * Params:
     * $matricola: it represents the identifier of the Maintainer;
     * $db: it represents the resource Database.
     */
    public static function get_maintainer_name($matricola, $db)
    {
        $sql = "SELECT nome FROM manutentore WHERE matricola = '$matricola';";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY";
            exit;
        }
        return $ret;
    }
}
