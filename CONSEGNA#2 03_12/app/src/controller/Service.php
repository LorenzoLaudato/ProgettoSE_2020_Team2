<?php

require_once("MS_getActivities.php");
require_once("MS_infoActivity.php");
require_once("MS_showSkills.php");
require_once("MS_updateNote.php");

/* La classe Service implementa i metodi utilizzati dal front-end per effettuare le operazioni per visualizzare le attività
schedulate nella settimana selezionata dall'utente, le informazioni associate, le competenze richieste e per aggiornare le note  */
class Service {

    public function __construct(DatabaseConnection $db){
        $this->db = $db;
    }

    public function getActivities($data){
        if (!empty($data)) { //se l'utente inserisce un input per weekNumber, viene invocato il Microservizio interfacciandosi col db
            return MS_getActivities::dbService($data, $this->db); //la funzione restituisce l'insieme delle attività (risultato della query del db) schedulate nella settimana selezionata
        }
        else{
            return false;
        }
    }

    public function infoActivity($data){
        return MS_infoActivity::dbService($data, $this->db); //la funzione restituisce il risultato della query contenente le informazioni dell'attività selezionata
    }

    public function showSkills($data){
        return MS_showSkills::dbService($data, $this->db); //viene invocato il Microservizio per mostrare l'elenco delle competenze richieste per l'attività selezionata
    }

    public function updateNote($data){
        MS_updateNote::dbService($data, $this->db); //viene invocato il Microservizio per aggiornare le note dell'attivita selezionata dall'utente

    }

}

