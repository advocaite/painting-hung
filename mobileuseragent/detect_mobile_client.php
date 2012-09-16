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
	header("Location: http://s1.tranhhung.xgo.vn/mobile/login.php");
	exit(0);
}
?>