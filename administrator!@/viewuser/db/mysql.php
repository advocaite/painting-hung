<?php

function doquery($query, $table, $fetch = false){
  global $link,$debug,$ugamela_root_path;
//    echo $query."<br />";
	require($ugamela_root_path.'config.php');

	if(!$link)
	{
		$link = mysql_connect($dbsettings["server"], $dbsettings["user"], 
				$dbsettings["pass"]) or
				$debug->error(mysql_error()."<br />$query","SQL Error");
				//message(mysql_error()."<br />$query","SQL Error");
		
		mysql_select_db($dbsettings["name"]) or $debug->error(mysql_error()."<br />$query","SQL Error");
		mysql_query("SET NAMES latin2");
		echo mysql_error();
	}
	// por el momento $query se mostrara
	// pero luego solo se vera en modo debug
	$sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"].
				$table, $query)) or 
				$debug->error(mysql_error()."<br />$query","SQL Error");
				//print(mysql_error()."<br />$query"."SQL Error");

	unset($dbsettings);//se borra la array para liberar algo de memoria

	global $numqueries,$debug;//,$depurerwrote003;
	$numqueries++;
	//$depurerwrote003 .= ;
	$debug->add("<tr><th>Query $numqueries: </th><th>$query</th><th>$table</th><th>$fetch</th></tr>");

	if($fetch)
	{ //hace el fetch y regresa $sqlrow
		$sqlrow = mysql_fetch_array($sqlquery);
		return $sqlrow;
	}else{ //devuelve el $sqlquery ("sin fetch")
		return $sqlquery;
	}
	
}



?>
