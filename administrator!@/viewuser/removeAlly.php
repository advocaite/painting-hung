<?php //contact.php

define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');

if(!check_user()){ header("Location: login.$phpEx"); }

includeLang('allianz');
global $db,$user;
/*
	KhangNguyen
*/
function sendMailAllyMember($user,$kick)
{
	global $lang;
	//$sql_admin = "select * from ";
	
	//test
	$from = "administrator@admin.com";
	$fromName = "administrator_Asuwa";
	$to = $user->email;
	$toName = $user->name;
	$subject = $lang['Email_subject'];
	$content = "Invitation: $kick";
	
	return sendMail($from,$fromName, $to, $toName, $subject, $content);
}



if($_POST)
{
	$errors = 0;
	$errorlist = "";
	$remove_suc ="";
	/*
	//if OK (submit) button was clicked
	if(isset($_POST['submit']))
	{
		
	}

	*/
	
	//check name enter
	//if(true) => delete
	//else => report to ally Leader <=> name enter is error or not exist
	/*
	$test = "testtest";
	$sql = "select users.* from wg_users users, wg_ally_members ally";
	$sql .= "where users.username = '$test'";
	$sql .= "and users.id = ally.user_id";
	*/
	$sql = "select users.* from wg_users users, wg_ally_members ally ";
	$sql .= "where users.username = '".$_POST['ally_name']."'";
	$sql .= "and users.id = ally.user_id";
	$db->setQuery($sql);
	$element_1 = null;
	$db->loadObject($element_1);
	if($element_1)
	{
		//delete ally member from wg_ally_member
		$sql = "delete from wg_ally_members where user_id ='".$element_1->id."'";
		$db->setQuery($sql);
		if($db->query())
		{
			
			//Owner, Sender, Time, Type, From, Subject, Content
			//$owner = $_POST['a_name'];
			//$sender = $user['username'];
			
			$subject = $lang['Del_subject'];
			$content = $lang['Del_content'];
			
			//insert message into wg_messages table
			$sql = "insert into wg_messages";
			$sql .= "(owner, sender, time, type,subject,content)";
			$sql .= "value('".$_POST['ally_name']."','".$user['username']."',now(),'0','$subject','$content')";		
			$db->setQuery($sql);
			if($db->query())
			{
				$remove_suc .= $lang['remove_suc'];
				message($remove_suc,$lang['Kick player'],"allianz.".$phpEx);
				//send email
/*				if (sendMailAllyMember($user,$kick))
				{}
				else{die("error!!.");} */
				
			}
			else
			{
				die("error!!.");
			}				
		}
		else
		{
			die("Error: Can't delete in wg_ally_members tables");
		}
		
	}
	else
	{	$errorlist .= $lang['error_remove'];
		$errorlist .= $lang['backto_removeAlly'];
		message($errorlist,$lang['Kick player']);//,"removeAlly.".$phpEx);							
	}
}

$parse = $lang;

	$page = parsetemplate(gettemplate('RemoveAlly'), $parse);
	display($page,$lang['Overview']);

?>

