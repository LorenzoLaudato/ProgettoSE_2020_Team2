<?php

require_once("Service.php");

class MS_infoActivity extends Service {

    public static function dbService($data, $db) {
        // allora $data sarà uguale a $code
        $sql = "SELECT * FROM Attivita WHERE code = '$data' ORDER BY code;";
        $ret = pg_query($db->database, $sql);
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db->database);
            exit;
        }
        return $ret;
    }
}