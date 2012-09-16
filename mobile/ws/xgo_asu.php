<?php
ob_start();

define('INSIDE', true);
$ugamela_root_path = "..";
include ($ugamela_root_path . "/includes/db_connect.php");

class ws_xgoAsu
{
    public function getxGo_asuEntry($domainName, $ws_userName, $ws_passWord, $transaction, $customerId, $xgo_amount)
    {
        include ('config_auth_ws.php');

        //xac thuc thong tin client
        //$ip = $_SERVER['REMOTE_ADDR'];
        //$hostname = gethostbyaddr($ip);
        if (!isset($domainName) || $auth_ws['domain_name'] != $domainName)// || $domainName != $hostname)
            return -1;

        if (!isset($ws_userName) || $auth_ws['ws_username'] != $ws_userName)
            return -2;

        if (!isset($ws_passWord) || $auth_ws['ws_password'] != $ws_passWord)
            return -3; //invalid authenticatiion webservice

        if (!$this->CheckUser($customerId))
            return -4; //account error not exist

        if ($this->Update_Asu($customerId, $xgo_amount))
        {
            $this->InsertLog_xGo($customerId, $transaction, $xgo_amount);
            return 1; //finish
        }

        return -5;
    }

    private function InsertLog_xGo($customerId, $transaction, $xgo_amount)
    {
        global $db;
        $datetime = date("y-m-d H:i:s");
        $sql      =
            "INSERT INTO wg_log_xgo (`customerid`,`transactionid`,`xgo_amount`,`datelog`) VALUES ('$customerId','$transaction','$xgo_amount','$datetime')";
        $db->setQuery($sql);
        $db->query();
    }

    private function CheckUser($customerId)
    {
        global $db;
        $sql = "SELECT id,actived FROM wg_users WHERE customerid='$customerId'";
        $db->setQuery($sql);
        $wg_users = NULL;
        $db->loadObject($wg_users);
        return ($wg_users && $wg_users->actived == 1);
    }

    private function Update_Asu($customerId, $xGo_amount)
    {
        global $db;
        $sql = "SELECT customerid FROM wg_plus_xgo WHERE customerid=" . $customerId;
        $db->setQuery($sql);
        $temp = NULL;
        $temp = $db->loadResult();

        if ($temp == NULL)
        {
            $sql = "SELECT id FROM wg_users WHERE customerid=" . $customerId;
            $db->setQuery($sql);
            $userId = NULL;
            $userId = $db->loadResult();

            $sql    =
                "INSERT INTO wg_plus_xgo (`customerid`,`userid`,`asu`) VALUES ('$customerId','$userId','$xGo_amount')";
            $db->setQuery($sql);
            return $db->query();
        }
        else
        {
            $sql = "UPDATE wg_plus_xgo SET asu = asu + " . $xGo_amount . " WHERE customerid=" . $customerId;
            $db->setQuery($sql);
            return $db->query();
        }
    }
}

ini_set("soap.wsdl_cache_enabled", "0");
$server = new SoapServer("xgo_asu.wsdl");
$server->setClass('ws_xgoAsu');
$server->handle();
ob_end_flush();
?>