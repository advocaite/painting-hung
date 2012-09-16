<?php
/*
	Plugin Name: captcha_security_images.php
	Plugin URI: http://asuwa.net/includes/captcha_security_images.php
	Description: 
	+ Su dung cho viec tao chuoi ngau nhien security cho viec nap card
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

session_start();

class CaptchaSecurityImages 
{

	var $font = 'monofont.ttf';
	
	/*
	* @Author: tdnquang
	* @Des: tao chuoi ngau nhien
	* @param: + $characters: chieu dai cua chuoi
	* @return: + chuoi ngau nhien
	*/
	function generateCode($characters) 
	{
		$possible = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$code = '';
		$i = 0;
		while ($i < $characters) 
		{ 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	
	/*
	* @Author: tdnquang
	* @Des: tao hinh anh captcha
	* @param: 
	* @return: 
	*/
	function CaptchaSecurityImages($width='120',$height='40',$characters='6') 
	{
		$code = $this->generateCode($characters);
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = imagecolorallocate($image, 100, 120, 180);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) 
		{
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) 
		{
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$_SESSION['security_code'] = $code;
	}

}
$width = isset($_GET['width']) ? $_GET['width'] : '120';
$height = isset($_GET['height']) ? $_GET['height'] : '40';
$characters = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';

$captcha = new CaptchaSecurityImages($width,$height,$characters);

?>