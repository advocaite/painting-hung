<?php
ob_start(); 
session_start();
define('INSIDE',true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path .'includes/db_connect.'.$phpEx);
include($ugamela_root_path .'includes/common.'.$phpEx);
include("promo.php");
global $db,$promo;
function saveFile($content)
{
	$f = fopen('promo.php',"w");
	fputs($f,$content);
	fclose($f);
	return true;
}
$msg='';
if($_POST)
{
	$IMGVER_EnteredText = $HTTP_POST_VARS["txtCode"];
	$IMGVER_RandomText = $HTTP_SESSION_VARS["IMGVER_RndText"];	
	if ($IMGVER_EnteredText == $IMGVER_RandomText)
	{ 
		if(isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$check=$check_ip=0;
			if($promo[0]['date'] != date("Y-m-d"))
			{
				$char='$';
$string='<?php
if (!defined("INSIDE")){die("Hacking attempt");}
$promo=array();
$promo[0]["date"]="'.date("Y-m-d").'";';
				$sql="SELECT id,username,villages_id,population FROM wg_users ORDER BY population DESC";
				$db->setQuery($sql);
				$wg_users=NULL;
				$wg_users=$db->loadObjectList();
				if($wg_users)
				{
					foreach($wg_users as $key=>$value)
					{
						$string.="".$char."promo[".$value->id."]['name']='".$value->username."';";
						$string.="".$char."promo[".$value->id."]['villages_id']='".$value->villages_id."';";	
						$string.="".$char."promo[".$value->id."]['ip']='';";						
					}	
				}
				$string.='?>';
				saveFile($string);
				include("promo.php");			
				global $promo;
			} 
			if( $promo[$_GET['id']]['name'] =='')
			{
				$msg='ID không tồn tại !';
				$check=1;
			}
			if($check==0 && $promo[0]['date'] ==date("Y-m-d") )
			{
				$list_ip=$promo[$_GET['id']]['ip'];	
				$username=$promo[$_GET['id']]['name'];			
				$address=$_SERVER["REMOTE_ADDR"];
				if($list_ip !='')
				{
					$ip=split(';',$list_ip);
					if(count($ip)==10)
					{
						$check_ip=1;
					}
					else
					{
						foreach($ip as $i)
						{
							if($i == $address)
							{
								$check_ip=1;// IP nay da su dung trong ngay 
								$msg='Địa chỉ IP <span class="text_color">['.$address.']</span> đã được dùng trong ngày hôm nay !';
								break;
							}						
						}
					}
					$list_ip.=';'.$address.';';	
				}
				else
				{
					$list_ip=$address.';';
				}

				if($check_ip ==0)
				{
					$char='$';
$string='<?php
if (!defined("INSIDE")){die("Hacking attempt");}
$promo=array();
$promo[0]["date"]="'.date("Y-m-d").'";';
					foreach($promo as $key=>$value)
					{
						if($key == $_GET['id'])
						{
							$string.="".$char."promo[".$key."]['name']='".$value['name']."';";
							$string.="".$char."promo[".$key."]['ip']='".$value['villages_id']."';";	
							$string.="".$char."promo[".$key."]['ip']='".substr($list_ip,0,-1)."';";	
						}
						elseif($key >0)
						{
							$string.="".$char."promo[".$key."]['name']='".$value['name']."';";
							$string.="".$char."promo[".$key."]['villages_id']='".$value['villages_id']."';";
							$string.="".$char."promo[".$key."]['ip']='';";	
						}					
					}	
					$string.='?>';					
					$sql="UPDATE wg_villages SET rs1=(rs1+200),rs2=(rs2+200),rs3=(rs3+200),rs4=(rs4+200)
					 WHERE id=".$promo[$_GET['id']]['villages_id'];
					$db->setQuery($sql);
					$db->query();
					if($db->getAffectedRows()==1)
					{		
						saveFile($string);
						?>
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />            
                        </head>
                        <script language="javascript" type="text/javascript">
                        {
							function Close()
							{			
								window.parent.close();
							}	
						}	
                        </script>		
                        <body onLoad="Close();">
                        </body>
                        </html>		
                        <?			
                        header("http://asu.ingame.vn/Default.aspx?mod=reg&step=2&un=".$username);
                        exit();
					}
				}
            }			          			
        }
	}
	else
	{
		$msg='Mã xác nhận nhập vào không chính xác !';					
	}	
}
$IMGVER_TempString="";
for ($i = 1; $i <= 6; $i++)
{
   $IMGVER_TempString .= GetRandomChar();
}
$HTTP_SESSION_VARS["IMGVER_RndText"] = $IMGVER_TempString;
function GetRandomChar()
{
	return mt_rand(0,9);
	/*mt_srand((double)microtime()*1000000);
	return $IMGVER_RandVal = mt_rand(1,3);
	switch ($IMGVER_RandVal) {
	case 1:  
		$IMGVER_RandVal = mt_rand(97, 122); 
		break;
	case 2:  
		$IMGVER_RandVal = mt_rand(48, 57);
		break;
	case 3: 
		$IMGVER_RandVal = mt_rand(65, 90);
		break;
	}
return chr($IMGVER_RandVal);*/
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
<title>Tranh Hùng - Webgame</title>
</head>
<script type="text/javascript">
function openMeExt(vLink, vStatus, vResizeable, vScrollbars, vToolbar, vLocation, vFullscreen, vTitlebar, vCentered, vHeight, vWidth, vTop, vLeft, vID, vCounter)
{
	var sLink = (typeof(vLink.href) == 'undefined') ? vLink : vLink.href;

	winDef = '';
	winDef = winDef.concat('status=').concat((vStatus) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('resizable=').concat((vResizeable) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('scrollbars=').concat((vScrollbars) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('toolbar=').concat((vToolbar) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('location=').concat((vLocation) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('fullscreen=').concat((vFullscreen) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('titlebar=').concat((vTitlebar) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('height=').concat(vHeight-140).concat(',');
	winDef = winDef.concat('width=').concat(vWidth).concat(',');

	if (vCentered){
		winDef = winDef.concat('top=').concat((screen.height - vHeight)/2).concat(',');
		winDef = winDef.concat('left=').concat((screen.width - vWidth)/2);
	}
	else{
		winDef = winDef.concat('top=').concat(vTop).concat(',');
		winDef = winDef.concat('left=').concat(vLeft);
	}

	if (typeof(vCounter) == 'undefined'){
		vCounter = 0;
	}

	if (typeof(vID) == 'undefined')	{
		vID = 0;
	}
	
	if (vCounter){
		sLink = buildLink(vID,sLink);
	}

	open(sLink, '_blank', winDef);

	if (typeof(vLink.href) != 'undefined')
	{
		return false;
	}
}
function SetFocus()
{
		document.formular.txtCode.select();
		document.formular.txtCode.focus();
}
function doSubmit(value)
{
	if (formular.txtCode.value=='' || formular.txtCode.value.length !=6)
	{
		alert('Ban phai nhap ma xac nhan du 6 ky tu!');
		return false;
	}
	openMeExt('http://asu.ingame.vn/Default.aspx?mod=reg&step=2&un='+value,0,0,1,0,0,0,0,1,1000,1000,0,0,0);
	return true;
}
</script>
<style>
.body{
padding-top:0px;
padding-left:0px;
}
#head{
 width:100%;
 height:351px;
 background-image:url("../images/ref1.jpg");
 background-repeat:no-repeat;
}
#foot{
 width:100%;
 height:129px;
 background-image:url("../images/ref2.jpg");
 background-repeat:no-repeat;
}
.table{
padding-top:0px;

}
.button{
	font-weight:bold;
}
.input{
	width:80px;
	height:30px;
	font-family:Tahoma;
	font-size:23px;
	text-align:center;
}
.text{
	font-size:12px;
	padding-left:5px;
	padding-top:20px;
	font-weight:bolder;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.text_color{
	font-size:12px;
	color:#FF0000;	
	font-weight:bolder;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
</style>
<body onLoad="SetFocus();">
<div id="head">
<table width="640" height="480" border="0" cellspacing="0" cellpadding="0" class="table">
  <tr>
    <td>   
    <div class="text"><center>Bạn được giới thiệu bởi người chơi <span class="text_color"><?php echo $promo[$_GET['id']]['name'];?></span>. Người giới thiệu sẽ được cộng 100 đơn vị tài nguyên các loại sau khi bạn nhập mã xác nhận và xác thực</center></div>
    <p align="center"><img src="doimg.php"></p>
    <div><center>
  <form method="POST" id="formular" name="formular">
    <p><span class="text">Nhập mã xác nhận:</span> </font>
      <input name="txtCode" type="text" class="input" id="txtCode" maxlength="6" />
      <input type="submit" name="Submit" class="button" value="Xác nhận" onClick="return doSubmit('<?php echo $promo[$_GET['id']]['name'];?>');" />
    </p>
  </form>
  </center></div>
  <p><center><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $msg;?></font></center></p>    </td>
  </tr>
</table>
</div>
<div id="foot">
<img src="../images/ref2.jpg" usemap="#planetmap" border="0" />
<map name="planetmap">
  <area shape="rect" coords="70,0,250,50" title="Đăng ký ngay" href="http://asu.ingame.vn/Default.aspx?mod=reg&step=2&un=<?php echo $promo[$_GET['id']]['name'];?>" target="_blank"/>
</map>
</div>
</body>
</html>
