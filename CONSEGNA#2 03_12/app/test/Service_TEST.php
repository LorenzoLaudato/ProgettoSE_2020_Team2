<?php

use PHPUnit\Framework\TestCase;
include "../src/controller/DatabaseConnection.php";
include "../src/controller/Service.php";

final class Service_TEST extends TestCase{

    public function test_getActivities(){

        $database = new DatabaseConnection('localhost', 'postgres', 'postgres', '1234');
        $database->getDB();
        $micro = new Service($database);

        $this->assertFalse($micro->getActivities("")); //se l'utente non inserisce un input per weekNumber, la funzione restituisce false

    }

    public function test_showSkills(){

        $database = new DatabaseConnection('localhost', 'postgres', 'postgres', '1234');
        $database->getDB();
        $micro = new Service($database);
        $ret = $micro->showSkills('5');
        //devo testare se in $ret ci sono le due skills: 1) Electrical Maintenance, 2) xyz- type robot knowledge
        $row = pg_fetch_row($ret);
        $this->assertEquals('Electrical Maintenance', $row[0]);
        $row = pg_fetch_row($ret);
        $this->assertEquals('xyz- type robot knowledge', $row[0]);

    }


}