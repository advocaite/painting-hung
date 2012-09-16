<?php
if ( !defined('INSIDE') )
{
	die('Hacking attempt');
}

class usersOnline {

	var $timeout = 600;
	var $num_online = 0;
	var $error;
	var $i = 0;
	var $username;
	var $deltaTimeSubtract;
	function usersOnline () {
		global $user;
		$this->timestamp = time();
		$this->ip = $this->ipCheck();
		$dispTime = $this->timestamp - $_SESSION['time_check_online'];
		if($dispTime>300){
			$_SESSION['time_check_online'] = $this->timestamp;
			if(isset($_SESSION['username']))
			{
				$this->username = $_SESSION['username'];
				$this->update_user();
			}else{
				if($user['username']){
					$this->username = $user['username'];
					$this->update_user();
				}			
			}
		}
		$this->delete_user();
		$this->count_users(); 
		$this->checkLimitTimeOnline();
	}
	
	function ipCheck() {
	/*
	This function will try to find out if user is coming behind proxy server. Why is this important?
	If you have high traffic web site, it might happen that you receive lot of traffic
	from the same proxy server (like AOL). In that case, the script would count them all as 1 user.
	This function tryes to get real IP address.
	Note that getenv() function doesn't work when PHP is running as ISAPI module
	*/
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	function new_userOnline($u='') {
		global $db;
		$sql = "INSERT INTO Wg_sessions(username,ip,timestamp) VALUES ('$u','$this->ip','$this->timestamp')";
		$db->setQuery($sql);
		if (!$db->query()) {
			$this->error[$this->i] = "Unable to record new visitor\r\n";			
			$this->i ++;
		}
		if($u!='') $_SESSION['username'] = $u;
	}
	
	function update_user() {
		global $db;
		$sql = "UPDATE Wg_sessions set timestamp='$this->timestamp' WHERE username = '$this->username'";
		$db->setQuery($sql);
		$db->query();
		if (!$db->getAffectedRows()) {
			$this->new_userOnline($this->username);
		}
	}
	
	function delete_user() {
		global $db;
		$sql = "DELETE FROM Wg_sessions WHERE timestamp < ($this->timestamp - $this->timeout)";
		$db->setQuery($sql);
		if (!$db->query()) {
			$this->error[$this->i] = "Unable to delete visitors";
			$this->i ++;
		}
	}
	
	function count_users() {
		global $db;
		if (count($this->error) == 0) {
			$sql = "SELECT count(ip) FROM Wg_sessions";
			$db->setQuery($sql);
			return $this->num_online = $db->loadResult();			
		}
	}	
	function checkLimitTimeOnline()
	{
		global $db,$game_config,$user;		
		if(substr($user['last_login'],8,2)==date("d") && substr($user['last_login'],5,2)==date("m")	&& substr($user['last_login'],0,4)==date("Y"))
		{
			if($_SESSION['last_login']){
				$check = $this->timestamp-$_SESSION['last_login'];
				$temp=$user['amount_time']-$check;
			}else{
				$check =$this->timeout;
				$temp=$user['amount_time']-$check;
			}
			if($temp<0){
				$temp=0;
			}
			
			$this->deltaTimeSubtract = $temp;
			if($user['amount_time']>0&& $check>=$game_config['delta_time_update'])
			{
				$_SESSION['last_login']=$this->timestamp; // cap nhat moi'				
				$sql="UPDATE wg_users set last_login='".date("Y-m-d H:i:s")."',amount_time=".$temp." WHERE id=".$user['id']."";
				$db->setQuery($sql);
				$db->query();			
			}						
		}
		else
		{
			$sql="UPDATE wg_users set last_login='".date("Y-m-d H:i:s")."',amount_time=".TIME_LIMIT_GAMER." WHERE id=".$user['id']."";
			$db->setQuery($sql);
			$db->query();
			$this->deltaTimeSubtract=TIME_LIMIT_GAMER;
		}
		return NULL;
	}
}

?>