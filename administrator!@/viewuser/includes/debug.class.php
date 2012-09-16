<?php

if(!defined('INSIDE')){ die("attemp hacking");}

class debug
{
	var $log,$numqueries;

	function debug()
	{
		$this->vars = $this->log = '';
		$this->numqueries = 0;
	}

	function add($mes)
	{
		$this->log .= $mes;
		$this->numqueries++;
	}

	function echo_log()
	{	global $ugamela_root_path;
		echo  "<br><table><tr><td class=k colspan=4><a href=".$ugamela_root_path."admin/settings.php>Debug log</a>:</td></tr>".$this->log."</table>";
		die();
	}
	
	function error($message,$title)
	{
		global $link,$game_config;
		if($game_config['debug']==1){
			echo "<h2>$title</h2><br><font color=red>$message</font><br><hr>";
			echo  "<table>".$this->log."</table>";
		}
		//else{
			
			global $user,$ugamela_root_path,$phpEx;
			include($ugamela_root_path . 'config.'.$phpEx);
			if(!$link) die('Máy chủ đang bận xin vui lòng thử lại lần nữa sau ít phút !');
			$query = "INSERT INTO {{table}} SET
				`error_sender` = '{$user['id']}' ,
				`error_time` = '".time()."' ,
				`error_type` = '{$title}' ,
				`error_text` = '{$message}';";
			$sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"].'errors',$query))
				or die(mysql_error());
			$query = "explain select * from {{table}}";
			$q = mysql_fetch_array(mysql_query(str_replace("{{table}}", $dbsettings["prefix"].
				'errors', $query))) or die('Lỗi 1: ');
				
			echo "Lỗi 2 '".$q['rows']."'";
		//}		
		die();
	}	
}

?>
