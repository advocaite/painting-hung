<?php

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
    //creat client soap connection
    $client = new SoapClient("http://yourdomain.com/soap/auth.wsdl");
    //build array for server vars
    $auth_remote=Array
    (
        'username'    => "test",
        'password'    => "test2345"
    );
    $return = $client->__soapCall('test_function', $auth_remote);
    print_r($return);

?>