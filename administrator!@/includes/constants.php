<?php  // constants.php

if ( !defined('INSIDE') )
{
	die("Hacking attempt");
}

// Debug Level
//define('DEBUG', 1); // Debugging on
define('DEBUG', 1); // Debugging off


// User Levels <- Do not change the values of USER or ADMIN
define('DELETED', -1);
define('ANONYMOUS', -1);

define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);


// Auth settings
define('AUTH_LIST_ALL', 0);
define('AUTH_ALL', 0);

define('AUTH_REG', 1);
define('AUTH_ACL', 2);
define('AUTH_MOD', 3);
define('AUTH_ADMIN', 5);

define('AUTH_VIEW', 1);
define('AUTH_READ', 2);
define('AUTH_POST', 3);
define('AUTH_REPLY', 4);
define('AUTH_EDIT', 5);
define('AUTH_DELETE', 6);
define('AUTH_ANNOUNCE', 7);
define('AUTH_STICKY', 8);
define('AUTH_POLLCREATE', 9);
define('AUTH_VOTE', 10);
define('AUTH_ATTACH', 11);

//cac hang cho type report
define("REPORT_ATTACK", 1);
define("REPORT_DEFEND", 2);
define("REPORT_TRADE", 3);
define("REPORT_SEND_RARE", 4);

//Cua Tu
define("TIME_ZONE", 25200);	//7*3600

define("MERCHANT_SPEED_ARABIA", 16);//toc do di chuyen cua thuong nhan Arabia
define("MERCHANT_SPEED_MONGO", 12);
define("MERCHANT_SPEED_SUNDA", 24);
define("MERCHANT_CAPACITY_ARABIA", 500);//Suc mang cua thuong nhan Arabia
define("MERCHANT_CAPACITY_MONGO", 1000);
define("MERCHANT_CAPACITY_SUNDA", 750);

define("NATION_NAME_ARABIA", 'Arabia');
define("NATION_NAME_MONGO", 'Mongo');
define("NATION_NAME_SUNDA", 'Sunda');

define('BUILDING_TYPE_NAME_MARKETPLACE', 'Marketplace');	//chợ
//end Cua Tu	 
define("HERRO_SPEED", 5);
// Time phuc hoi cua Thu Hoang
define("TIME_OASISS",12);
?>