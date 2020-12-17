<?php


class DatabaseConnection {

    public function __construct(string $host, string $db, string $username, string $password){
        $this->host = $host;
        $this->db = $db;
        $this->username = $username;
        $this->password = $password;

    }

    public function getConnectionString(){
        $connection_string = "host=$this->host dbname=$this->db user=$this->username password=$this->password";
        return $connection_string;
    }

    public function getDB (){
        //CONNESSIONE AL DB
        $connection_string = $this->getConnectionString();
        $db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());
        if ($db == 'Impossibile connettersi al database: ' . pg_last_error() ){
            return False;
        }
        else {
            $this->database = $db; //in database c'è la PostgreSQL connection resource
            return $db;
        }
    }

    public function closeDB (){
        pg_close($this->database);
    }

}