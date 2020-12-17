<?php

use PHPUnit\Framework\TestCase;
include "../src/controller/DatabaseConnection.php";

final class DatabaseConnection_TEST extends TestCase {
    
    public function test_getConnectionString(){
        $db = DatabaseConnection::getInstance();
        $connection_string = "host=localhost dbname=postgres user=postgres password=1234";
        //$connection_string = "host=localhost dbname=postgres user=postgres password=1235"; //-- FAIL
        $this->assertEquals($connection_string, $db->getConnectionString());
    }

    public function testgetDB() {
        $db = DatabaseConnection::getInstance();
        $this->assertNotEquals(False, $db->getConnection());

    }

}