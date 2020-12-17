<?php

require_once("Service.php");

class MS_showSkills extends Service{

    public static function dbService($data, $db){
        //allora $data è il codice univoco dell'attività
        $sql = "SELECT S.tipo FROM skills S, richiesta R WHERE S.Sid = R.skill AND R.attivita = '$data';";
        $ret = pg_query($db->database, $sql);
        if(!$ret){
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            exit;
        }
        //serializzo l'array in formato string
        while($row = pg_fetch_row($ret)){
            $serialized = "";
            for ($i = 0; $i < count($row); $i++) {
                $serialized .= $row[$i];
                echo "<li>$serialized</li>"; //aggiunta di un elemento alla lista delle skills da mostrare all'utente
            }
        }
        return pg_query($db->database, $sql);

    }
}