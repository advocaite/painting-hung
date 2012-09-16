<?php

    /*     * ****************************************************************************************
     * PHPKode Newsletter X - A web-based PHP newsletter script and email marketing software
     * Copyright 2010-2011, iShang Information Industry Co., Ltd.
     *
     * * @require: PHP 4.3.x or higher; MySQL 4.x or higher
     *
     * @copyright Copyright 2010-2011, iShang Information Industry Co., Ltd.
     * @link http://www.phpnewsletter.org/ PHPKode Newsletter X Software
     * @package phpkode_newsletter_latest.zip
     * @version 1.1
     * @license PHPKode Newsletter X END USER LICENSE AGREEMENT at
     *    http://www.phpnewsletter.org/agreement.txt
     * ************************************************************************************** */

    session_start();
    $_SESSION['kode_validate_code'] = "";
    $validate_code = "";
    //$font = str_replace('\\', '/', dirname(__FILE__)) . "/kode_font_pl.ttf";
    $font = "./kode_font_pl.ttf";
   

    $validate_char = "1,2,3,4,5,6,7,8,9,a,b,c,d,e,f";
    $list = explode(",", $validate_char);
    for ($i = 0; $i < 4; $i++) {
        $randnum = rand(0, 14);
        $validate_code .= $list[$randnum];
    }

    $_SESSION['kode_validate_code'] = $validate_code;

    $im = imagecreatetruecolor(80, 25);
    $color = imagecolorallocate($im, 0, 0, 0);
    $bg = imagecolorallocate($im, 255, 255, 255);
    
    imagefill($im, 1, 1, $bg);
    for ($i = 0; $i < 4; $i++) {
        ImageTTFText($im, 18, 0, 18 * $i, 25, $color, $font, $validate_code[$i]);
    }

    header('Content-type:image/gif');
    ImageGif($im);
    imagedestroy($im);
?> 