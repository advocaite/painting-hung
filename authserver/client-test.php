<?php

//login auth done.
error_reporting(E_ALL);
ini_set('display_errors', '1');
    ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
    $client = new SoapClient("http://auth.yourdomain.com/soap/auth.wsdl");
    $auth_remote=Array
    (
        'username'    => "minea",
        'password'    => 123456
    );
    $return = $client->__soapCall('getProfile', $auth_remote);
    $array = explode(",",$return);
    
    //print_r($return);
    print_r ($array);
    //echo get_email_remote($user);
    die();

?>