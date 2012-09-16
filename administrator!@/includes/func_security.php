<?php
if (!defined('INSIDE'))
{
    die ("Hacking attempt");
}

function globalError($content)
{
    global $db, $game_config;
    setcookie($game_config['COOKIE_NAME'], NULL, time() - 100000, "/", "", 0);
    session_destroy();
    header ("Location:logout.php");
}

/**
* Luu loi nhung khong ngat viec xu ly.
* @param: $content noi dung loi
*/
function globalError2($content)
{
    global $db;
    $sql = "INSERT INTO wg_error (time,content) VALUES ('" . date("Y-m-d H:i:s") . "','" . $db->getEscaped(
                                                                                               $content) . "')";
    $db->setQuery($sql);
    $db->query();
}

function Time_Wait($time)
{
    global $db, $game_config;
    $time_end = strtotime($game_config['time_waiting']);

    if ($time < $time_end)
    {
        header ("Location:waitting.php");
        exit (0);
    }

    return true;
}

//Ham ma hoa
function MaHoa($str)
{
    for ($i = 0; $i <= strlen($str) - 1; $i++)
    {
        $char1 = substr($str, $i, 1);
        $char2 = chr(ord($char1) + 10);
        $enstr .= $char2;
    }

    return base64_encode($enstr);
}

//Ham giai ma
function GiaiMa($enstr1)
{
    $enstr = base64_decode($enstr1);

    for ($i = 0; $i <= strlen($enstr) - 1; $i++)
    {
        $char1 = substr($enstr, $i, 1);
        $char2 = chr(ord($char1) - 10);
        $str .= $char2;
    }

    return $str;
}
/**
* Insert to table wg_securities
* @$featureName name of feature
*/
function insertFeature($username, $content)
{
    global $db;
    $ip     = $_SERVER['REMOTE_ADDR'];
    $sql_ip = "INSERT INTO wg_securities ";
    $sql_ip .= " (username,ip,feature,time) ";
    $sql_ip .= " VALUES ('$username','$ip','$content',now()) ";
    $db->setQuery($sql_ip);

    if ($db->query())
    {
        return true;
    }
    else
    {
        die ('error');
    }
}

function returnMacAddress1()
{
    //First get the IP address then use the
    //DOS command + only get row with client IP address
    //This takes only one line of the ARP table instead
    //of what could be a very large table of data to
    //hopefull give a small speed/performance advantage
    $remoteIp = rtrim($_SERVER['REMOTE_ADDR']);
    $location = rtrim(`arp -a $remoteIp`);
    print_r ($remoteIp . $location); //display
    //reduce no of white spaces then
    //Split up into array element by white space
    $location = preg_replace('/\s+/', 's', $location);
    $location = split('\s', $location); //

    $num      = count($location);       //get num of array elements
    $loop     = 0;                      //start at array element 0

    while ($loop < $num)
    {
        //mac address is always one after the
        //IP after inserting the firstline
        //(preg_replace) line above.
        if ($location[$loop] == $remoteIp)
        {
            $loop            = $loop + 1;
            echo "<h1>Client MAC Address:- " . $location[$loop] . "</h1>";
            $_SESSION['MAC'] = $loop;
            return;
        }
        else
        {
            $loop = $loop + 1;
        }
    }
}

function returnMacAddress2()
{
    // This code is under the GNU Public Licence
    // Written by michael_stankiewicz {don't spam} at yahoo {no spam} dot com
    // Tested only on linux, please report bugs
    // WARNING: the commands 'which' and 'arp' should be executable
    // by the apache user; on most linux boxes the default configuration
    // should work fine
    // Get the arp executable path
    $location    = `which arp`;
    // Execute the arp command and store the output in $arpTable
    $arpTable    = `$location`;
    // Split the output so every line is an entry of the $arpSplitted array
    $arpSplitted = split("\n", $arpTable);
    // Get the remote ip address (the ip address of the client, the browser)
    $remoteIp    = $GLOBALS['REMOTE_ADDR'];

    // Cicle the array to find the match with the remote ip address
    foreach ($arpSplitted as $value)
    {
        // Split every arp line, this is done in case the format of the arp
        // command output is a bit different than expected
        $valueSplitted = split(" ", $value);

        foreach ($valueSplitted as $spLine)
        {
            if (preg_match("/$remoteIp/", $spLine))
            {
                $ipFound = true;
            }

            // The ip address has been found, now rescan all the string
            // to get the mac address
            if ($ipFound)
            {
                // Rescan all the string, in case the mac address, in the string
                // returned by arp, comes before the ip address
                // (you know, Murphy's laws)
                reset ($valueSplitted);

                foreach ($valueSplitted as $spLine)
                {
                    if (preg_match(
                            "/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]"
                                . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i",
                            $spLine))
                    {
                        return $spLine;
                    }
                }
            }

            $ipFound = false;
        }
    }

    return false;
}
?>