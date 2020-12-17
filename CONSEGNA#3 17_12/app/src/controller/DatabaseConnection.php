<?php

// Singleton to connect db.
class DatabaseConnection
{
  // Hold the class instance.
  private static $instance = null;
  private $conn;

  // Here we initialize the info about the local DataBase
  private $host = 'localhost';
  private $username = 'postgres';
  private $password = 'oznerol99';
  private $db = 'postgres';

  /**The db connection is established in the private constructor*/
  private function __construct()
  {
    $this->conn = pg_connect($this->getConnectionString()) or die('Impossibile connettersi al database: ' . pg_last_error());
  }
  /**This method returns the string of the connection to the DB*/
  public function getConnectionString()
  {
    $connection_string = "host=$this->host dbname=$this->db user=$this->username password=$this->password";
    return $connection_string;
  }

  /**This method returns an istance of DatabaseConnection class*/
  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new DatabaseConnection();
    }

    return self::$instance;
  }

  /**This method returns a resource conn of the DatabaseConnection class*/
  public function getConnection()
  {
    if ($this->conn == 'Impossibile connettersi al database: ' . pg_last_error()) {
      return False;
    } else {
      $this->database = $this->conn; //in database c'è la PostgreSQL connection resource
      return $this->conn;
    }
  }
}
