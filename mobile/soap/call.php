<?php
include ('config_auth_remote.php');
/**
* Acitve by remote to billing system
* @param: $username-> username want to active
* @param: $code of username
* @return: true or false. true if actived else false.
*/
function active_remote($username, $code)
{
    $user = get_profile($username);

    if (is_array($user))
    {
        $customerID = $user['customerid'];
        return (md5($customerID) == $code);
    }

    return false;
}

/**
* Login by remote to billing system
* @param: $user username want login
* @param: $pass pass of username
* @return: true or false. true if login success, else false.
*/
function login_remote($user, $pass)
{
    //  return true;
    global $auth_remote;
    $wsdl   = $auth_remote['url'];
    $client = new SoapClient($wsdl, array
    (
        'encoding' => 'utf-8',
        'location' => $wsdl
    ));

    $param_login = array
    (
        'username'    => $user,
        'password'    => $pass,
        'ws_username' => $auth_remote['account'],
        'ws_password' => $auth_remote['pass']
    );

    $login = $client->__soapCall('login', $param_login);
    return is_array(json_decode($login, true));

    echo '<pre>';
    print_r (json_decode($login, true));
    echo get_email_remote($user);
    die();
}

function get_profile($user)
{
    global $auth_remote;
    $wsdl   = $auth_remote['url'];
    $client = new SoapClient($wsdl, array
    (
        'encoding' => 'utf-8',
        'location' => $wsdl
    ));

    $param_profile = array
    (
        'username'    => $user,
        'ws_username' => $auth_remote['account'],
        'ws_password' => $auth_remote['pass']
    );

    $getProfile = $client->__soapCall('getProfile', $param_profile);
    return json_decode($getProfile, true);

    echo '<pre>';
    print_r (json_decode($getProfile));
    die();
}

/**
* Get money form billing system
* @param: $user username want get
* @return: total money of user if login success, else return -1.
*/
function get_gold_remote($user)
{
    global $db;
    $customerID = get_customerid($user);
    $sql        = "SELECT asu FROM wg_plus_xgo WHERE customerid='" . $customerID . "'";
    $db->setQuery($sql);
    $asu = $db->loadResult();
    return $asu == NULL ? 0 : $asu;
}

/**
* Withdraw money form billing system
* @param: $user username want withdraw
* @param: $gold money withdraw
* @return: total money of user after withdraw if login success, else return false.
*/
function withdraw_gold_remote($user, $gold, $des)
{
    global $db;
    $customerID = get_customerid($user);
    $sql        = "UPDATE wg_plus_xgo SET asu=asu-'" . $gold . "' WHERE customerid=" . $customerID;
    $db->setQuery($sql);
    $db->query();
}

/**
* get email form billing system
* @param: $user username want get email
* @return: email if true, else return false.
*/
function get_email_remote($user)
{
    $userProFile = get_profile($user);

    if (is_array($userProFile))
        return $userProFile['email'];

    return "";
}

function get_customerid($user)
{
    $userProFile = get_profile($user);

    if (is_array($userProFile))
        return $userProFile['customerid'];

    return "";
}

/**
* get phone form billing system
* @param: $user username want get phone
* @return: phone if true, else return false.
*/
function get_phone_remote($username)
{
    global $db;
    $sql = "SELECT phone FROM wg_profiles WHERE username='" . $username . "'";
    $db->setQuery($sql);
    $phone = $db->loadResult();
    return $phone;
}

function InsertLogPlus($userId, $des, $asu)
{
    global $db, $user;
    include_once ('./includes/function_plus.php');
    $asu_bill = get_gold_remote($user['username']); //lay ASU tu buildling systems
    $asu_game = showGold($user['id']);              //lay asu tu game

    $sql      = "SELECT logs FROM wg_plus WHERE user_id=" . $userId;
    $db->setQuery($sql);
    $char = NULL;

    if ($asu_game >= $asu)
    {
        $char = $db->loadResult() . date("H:i d-m-y") . ',1,' . $des . ',' . $asu . ';';
    }
    else
    {
        if ($asu_game + $asu_bill >= $asu)
        {
            $char = $db->loadResult() . date("H:i d-m-y") . ',2,' . $des . ',' . $asu . ';';
        }
    }

    $sql = "UPDATE wg_plus SET logs='" . $char . "' WHERE user_id=" . $userId;
    $db->setQuery($sql);
    $db->query();

    $datetime = date("y-m-d H:i:s");
    $sql      = "INSERT INTO wg_gold_logs (`datetime` ,`description`) VALUES ('$datetime', '$des')";
    $db->setQuery($sql);
    $db->query();
}
?>