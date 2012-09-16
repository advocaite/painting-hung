<?php
define('INSIDE', true);

ob_start();
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include($ugamela_root_path . 'includes/common.' . $phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/function_trade.php');
include($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_resource.php');

//checkRequestTime();
if (!check_user())
{
    header("Location: login.php");
}

global $db, $wg_asuconfig;
includeLang('trade');
$parse = $lang;
$r1    = $r2 = $r3 = $r4 = $rscan = $r = 0;

$Set_k = "Select * from wg_asuconfigs";
$db->setQuery($Set_k);
$wg_asuconfig = null;
$db->loadObject($wg_asuconfig);

if ($_GET['q'])
{
    $q = $_GET["q"];

    switch ($q)
    {
        case 1:
            $r1    = (COST_BASIC + ($wg_asuconfig->k_lumber * 10));

            $rscan = $r1;
            $loai  = $lang['Lumber'];
            break;

        case 2:
            $r2    = (COST_BASIC + ($wg_asuconfig->k_clay * 10));

            $rscan = $r2;
            $loai  = $lang['Clay'];
            break;

        case 3:
            $r3    = (COST_BASIC + ($wg_asuconfig->k_iron * 10));

            $rscan = $r3;
            $loai  = $lang['Iron'];
            break;

        case 4:
            $r4    = (COST_BASIC + ($wg_asuconfig->k_crop * 10));

            $rscan = $r4;
            $loai  = $lang['Crop'];
            break;
    }

    if ($rscan <= 0)
    {
        echo $lang['CostAsu'] . " <b> 0 </b> " . $loai;
    }
    else
    {
        echo $lang['CostAsu'] . " <b>" . $rscan . "</b> " . $loai;
    }
}
elseif ($_GET['p'] && $_GET['r'])
{
    $p = $_GET["p"];
    $r = $_GET['r'];

    switch ($r)
    {
        case 1:
            $r1    = (COST_BASIC + ($wg_asuconfig->k_lumber * 10)) * $p;

            $rscan = $r1;
            $loai  = $lang['Lumber'];
            break;

        case 2:
            $r2    = (COST_BASIC + ($wg_asuconfig->k_clay * 10)) * $p;

            $rscan = $r2;
            $loai  = $lang['Clay'];
            break;

        case 3:
            $r3    = (COST_BASIC + ($wg_asuconfig->k_iron * 10)) * $p;

            $rscan = $r3;
            $loai  = $lang['Iron'];
            break;

        case 4:
            $r4    = (COST_BASIC + ($wg_asuconfig->k_crop * 10)) * $p;

            $rscan = $r4;
            $loai  = $lang['Crop'];
            break;
    }

    if ($rscan <= 0)
    {
        echo $lang['CostRS'] . " <b> 0 </b> " . $loai;
    }
    else
    {
        echo $lang['CostRS'] . " <b>" . $rscan . "</b> " . $loai;
    }
}

if ($_GET['f'] && $_GET['pi'])
{
    $txt_Price = round(intval($_GET['f']) * intval($_GET['pi']) / 500, 0);

    if ($txt_Price == 0)
    {
        echo
            '<table>
            <tr>
                <td align="right">'.$lang['Fee'].': </td>
                <td align="left"><b>0</b></td>
            </tr>
            <tr>
                <td align="right">'.$lang['Total Price'].': </td>
                <td align="left"><b>1</b></td>
             </tr>
        </table>';
    }
    else
    {
        $fee = round($txt_Price * 5 / 100, 0);
        echo '<table>
            <tr>
                <td align="right">'.$lang['Fee'].': </td>
                <td align="left"><b>' . $fee . '</b></td>
            </tr>
            <tr>
                <td align="right">'.$lang['Total Price'].': </td>
                <td align="left"><b>' . $txt_Price . '</b></td>
             </tr>
        </table>';
    }
}

ob_end_flush();
?>