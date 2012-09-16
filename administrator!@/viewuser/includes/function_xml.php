<?php
/*
	Plugin Name: function_xml.php
	Plugin URI: http://asuwa.vn/includes/function_xml.php
	Description: 
	+ Cac ham dung cho viec xu ly xml
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

/*
* @Author: tdnquang
* @Des: thuc hien viec kiem tra va ghi (thoi gian, so user online) 
* xuong file xml
* @param: + $numUserOnline: so user online
* @return: 
*/
function executeXML($numUserOnline) 
{		
	$doc = loadXMLFile();		        
	$points = $doc->getElementsByTagName("point");					
	
	foreach($points as $point){
	  	$times = $point->getElementsByTagName( "time" );
	  	$time = $times->item(0)->nodeValue;
	  
	  	$amounts = $point->getElementsByTagName( "amount" );
	  	$amount = $amounts->item(0)->nodeValue;
		$lastPoint = &$point;		  				
	}	

	$currTime = time();
	$lastTime = $lastPoint->getElementsByTagName( "time" )->item(0)->nodeValue;
	$disTime = $currTime - $lastTime;
	$currAmount = $numUserOnline;
	$lastAmount = $lastPoint->getElementsByTagName( "amount" )->item(0)->nodeValue;
	
	$path = dirname(dirname(__FILE__));
	if($disTime<60){ //1phut
		if($currAmount > $lastAmount){ //update
			$lastPoint->getElementsByTagName( "amount" )->item(0)->nodeValue = $currAmount;
			$doc->saveXML();
			$doc->save($path.'/xml/ccu/count_user_online.xml');
		}
	}else{
		appendCounterToXml($currTime, $currAmount);
	}
		
}
/*
* @Author: tdnquang
* @Des: them 1 record vao cuoi file xml 
* @param: + $timeValue: thoi gian hien tai
* 		  + $amountValue: so user dang online
* @return: 
*/
function appendCounterToXml ($timeValue, $amountValue){
	
	$xdoc = loadXMLFile();
	
	$counters = $xdoc->getElementsByTagName("counter")->item(0);
	$newPointElement = $xdoc->createElement('point');
	$counters-> appendChild($newPointElement);
	
	$newTimeElement = $xdoc->createElement('time');
	$newPointElement-> appendChild($newTimeElement);		
	$txtTimeNode = $xdoc->createTextNode($timeValue);
	$newTimeElement->appendChild($txtTimeNode);
	
	$newAmountElement = $xdoc->createElement('amount');
	$newPointElement->appendChild($newAmountElement);		
	$txtAmountNode = $xdoc->createTextNode($amountValue);
	$newAmountElement->appendChild($txtAmountNode);
	
    $xdoc->saveXML();
	$path = dirname(dirname(__FILE__));
	$xdoc->save($path.'/xml/ccu/count_user_online.xml');		
}

/*
* @Author: tdnquang
* @Des: doc noi dung file xml 
* @param:  		  
* @return: + $arrContent: noi dung file xml dua vao mang
*/
function getXMLContent(){		
	$doc = loadXMLFile();
	$arrContent = array();
	$points = $doc->getElementsByTagName("point");
	$i = 1;
	foreach($points as $point)
	{
	  	$times = $point->getElementsByTagName( "time" );
	  	$amounts = $point->getElementsByTagName( "amount" );
	  	$arrContent[$i][$times->item(0)->nodeName] =  $times->item(0)->nodeValue;	
		$arrContent[$i][$amounts->item(0)->nodeName] =  $amounts->item(0)->nodeValue;
		$i++;
	}	
	return $arrContent;
}
/*
* @Author: tdnquang
* @Des: lay record dau tien cua file xml 
* @param:  		  
* @return: + $firstRecord: record dau tien
*/
function getFirstXMLRecord(){
	$doc = loadXMLFile();
	$points = $doc->getElementsByTagName("point");
	echo $firstRecord = $points->item(0)->getElementsByTagName("amount");
	return $firstRecord;
}
/*
* @Author: tdnquang
* @Des: ket noi file xml dung DOM 
* @param:  		  
* @return: + $xmlDoc: ket noi thanh cong
*/
function loadXMLFile(){
	global $base_url ;
	$xmlDoc = new DOMDocument();
	$path = dirname(dirname(__FILE__));
	
	$xmlDoc->load($path.'/xml/ccu/count_user_online.xml');
	return $xmlDoc;
}
/*
* @Author: tdnquang
* @Des: luu xuong file xml 
* @param:  		  
* @return:
*/
function saveXMLFile(){
		/*$PATH_ROOT = $_SERVER['DOCUMENT_ROOT']."/asuwa/xml/ccu"; 
		$xmlDoc = new DOMDocument();
		$dateNow = date("Ymd");
		if(is_file($dateNow."xml")){
			
		}
		$xmlDoc->load('C:\xampp\htdocs\asuwa\xml\count_user_online.xml');*/
	
	}
	
?>
