<?php

require_once("Service.php");
class MS_updateNote extends Service {

    public static function dbService($data, $db) {
        // allora $data sarà uguale a $code
        if(!empty($_POST['note'])){
            $note = $_POST['note'];
            $sql_update= "UPDATE Attivita SET note = $1 WHERE code='$data';";
            $prep = pg_prepare($db->database,"UpdateAttività", $sql_update);
            if(!$prep) {
                echo pg_last_error($db->database);
            }
            else {
                $ret_update = pg_execute($db->database, "UpdateAttività",array($note));
                if(!$ret_update){
                    $note = "";
                }
                else{
                    echo "<script>okEdit();</script>";
                }
            }
        }

    }
}