<?php
if ( !defined('INSIDE') )
{
	die("Hacking attempt");
}
function string_to_array($string)
{
	/*$arr=array();
	$j=0;
	$tam="";
	for($i=0;$i<=strlen($string);$i++)
	{
		if ($string[$i]==',')
		{
			$arr[$j]=$tam;
			$j++;
			$tam="";
		}
		else
		{
			$tam.=$string[$i];			
		}
	}	
	$arr[$j]=$tam;
	return $arr;*/
	$value=split(",",$string);
	return $value;
}
?>