<?php

function is_client_mobile() {
  static $mua = null;
  if (!isset($mua)) {
    require_once('MobileUserAgent.php');
    $mua = new MobileUserAgent();
  }
  return $mua->success();
}
if(is_client_mobile()){
	header("Location: http://asuwa.vn/mobile/login.php");
	exit(0);
}
?>