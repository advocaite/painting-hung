<?php
function TimeToStringTime($time){
	$hours = floor($time / 3600); 
	$mins = floor(($time - $hours * 3600) / 60); 
	$secs = floor($time - $hours * 3600 - $mins * 60);
	if($hours<10){
		$hours="0$hours";
	}
	if($mins<10){
		$mins="0$mins";
	}
	if($secs<10){
		$secs="0$secs";
	}
	return $hours . ':' . $mins . ':' . $secs;	
}

?>