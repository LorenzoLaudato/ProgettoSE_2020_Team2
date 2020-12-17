<?php

require_once("Service.php");

class MS_getActivities extends Service {

    public static function dbService($data, $db) {
        //allora $data è uguale a $weeknumber
        $sql = "SELECT * FROM Attivita WHERE nsettimana = $data ORDER BY code;";
        $ret = pg_query($db->database, $sql);
        if (pg_num_rows($ret) ==0) {
            echo "NO MAINTENANCE ACTIVITIES IN THIS WEEK";
            exit;
        }
        return $ret;
    }
}
