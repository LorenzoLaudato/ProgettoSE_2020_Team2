<?php

use PHPUnit\Framework\TestCase;
include "../src/controller/DatabaseConnection.php";

final class DatabaseConnection_TEST extends TestCase {

    public function test_getConnectionString(){
        $db = new DatabaseConnection('localhost', 'postgres', 'postgres', '1234');
        $connection_string = "host=localhost dbname=postgres user=postgres password=1234";
        //$connection_string = "host=localhost dbname=postgres user=postgres password=1235"; //-- FAIL
        $this->assertEquals($connection_string, $db->getConnectionString());
    }

    public function testgetDB(): void {
        $db = new DatabaseConnection('localhost', 'postgres', 'postgres', '1234');
        $this->assertNotEquals(False, $db->getDB());

    }

}