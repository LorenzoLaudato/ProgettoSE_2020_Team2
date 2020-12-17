<?php
    
    function setWeekNumber(){
        if (isset($_GET['weekNumber']))
            $weekNumber = $_GET['weekNumber'];
          else
            $weekNumber = "";

        return $weekNumber;
    }
    
    function dataService_interface($activity, $data){
        switch ($activity) {
            case "SEARCH ACTIVITIES BY WEEK":
                // $data contiene il numero di settimana scelto dall'utente per visualizzare le correspondenti attività
                showActivities($data);
                break;
            case "INFO ACTIVITY TABLE":
                // $data contiene il codice dell'attività di cui si vogliono conoscere le informazioni presenti nella tabella del db
                return selectedActivity($data);
            case "EDIT NOTE":
                // $data contiene il codice dell'attività di cui si vogliono modificare le workspace notes
                updateNote($data);
                break;
            case "SKILLS NEEDED":
                // $data contiene il codice dell'attività di cui si vogliono mostrare le competenze necessarie
                showSkills($data); 
                break;     
        }
    }


    function showActivities($weekNumber){
        if (!empty($weekNumber)) {
            $ret = db_interface("MICROSERVICE #1", $weekNumber); 

            echo <<<_HTML
                        <table class="table table-striped" border="1">
                        <thead>
                        <tr style="background-color:#FFF8C6;">
                            <th scope="col">ID</th>
                            <th scope="col">AREA</th>
                            <th scope="col">TYPE</th>
                            <th scope="col">ESTIMATED INTERVENTION TIME [min]</th> 
                        </tr>
                        _HTML;
            //row[0] contiene l'id.
            while ($row = pg_fetch_row($ret)) {
            $code = $row[0];
            $code = (int)$code;
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
                            <td>$row[2]</td>
                            <td>$row[1]</td>
                            <td>$row[3]</td>
                            <td><a href="page2.php?code=$code&weekNumber=$weekNumber"><input class="button" type="button" value="SELECT"></a></td>
                            </tr>
                        _HTML;
            }
            echo "</thead>";
            echo "</table>";
        }
    }

    function selectedActivity($code){
        return db_interface("MICROSERVICE #2", $code);
    }
    
    function updateNote($code){
        db_interface("MICROSERVICE #2.1", $code);
    }

    function showSkills($code){
        db_interface("MICROSERVICE #2.2", $code);
    }

    function db_interface($serviceCode, $data){
        include ("connection.php");
        switch ($serviceCode) {
            case "MICROSERVICE #1":
                // MICROSERVIZIO 1 : Maintenance Pianification Services
                // allora $data sarà uguale a $weeknumber
                $sql = "SELECT * FROM Attivita WHERE nsettimana = $data ORDER BY code;";
                $ret = pg_query($db, $sql);
                if (pg_num_rows($ret) ==0) { 
                    echo "NO MAINTENANCE ACTIVITIES IN THIS WEEK";
                    exit;
                }
                break;
            // MICROSERVIZIO 2 : Intervention Information Service
            case "MICROSERVICE #2": 
                // allora $data sarà uguale a $code
                $sql = "SELECT * FROM Attivita WHERE code = '$data' ORDER BY code;";
                $ret = pg_query($db, $sql);
                if (!$ret) {
                    echo "ERRORE QUERY: " . pg_last_error($db);
                    exit;
                }
                break; 
            case "MICROSERVICE #2.1": 
                // allora $data sarà uguale a $code
                if(!empty($_POST['note'])){
                    //SE L'ARRAY POST NON é VUOTO AGGIORNO IL DATABASE e carico i nuovi valori nelle variabili utilizzate per riempire il form
                    $note = $_POST['note'];
                    $sql_update= <<<_QUERY
                            UPDATE Attivita
                            SET
                            note = $1
                            WHERE code='$data';
                        _QUERY;
                    $prep = pg_prepare($db,"UpdateAttività", $sql_update); 
                    if(!$prep) {
                        echo pg_last_error($db); 
                    } 
                    else {
                        $ret_update = pg_execute($db, "UpdateAttività",array($note));
                        if(!$ret_update){
                            $note = "";
                        }
                        else{
                            echo "<script>okEdit();</script>";
                        }
                    }       
                }
                $ret = false;
                break;

            case "MICROSERVICE #2.2": 
                // allora $data sarà uguale a $code
                $sql = "SELECT S.tipo FROM skills S, richiesta R WHERE S.Sid = R.skill AND R.attivita = '$data';";
                $ret = pg_query($db, $sql);
                if(!$ret){
                    echo "ERRORE QUERY: " . pg_last_error($db);
                    exit;
                }
                //serializzo l'array in formato string
                while($row = pg_fetch_row($ret)){
                    $serialized = "";
                    for ($i = 0; $i < count($row); $i++) {
                        $serialized .= $row[$i];
                        echo "<li>$serialized</li>";
                    }
                }
                $ret = false;
                break;
        }
    pg_close($db);
    return $ret;  
    }
    
?>