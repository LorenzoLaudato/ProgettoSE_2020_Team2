<?php
include("Service.php");

/**This class is an Implementation of the Factory Design Pattern*/

class ServiceFactory{

    public static function create(){
        return Service::getInstance();
    }
    
}