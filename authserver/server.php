<?php
//include functions for server here (personal preferance you can include them in this file too)
require 'auth_functions.php';

ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
//Start soap server linked to root dir .wsdl
$server = new SoapServer("auth.wsdl");
//Buils server function function must be included in functions file
$server->addFunction("test_function");
//handle shit
$server->handle();
?>