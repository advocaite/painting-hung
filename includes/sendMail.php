<?php
defined( 'INSIDE' ) or die( 'Restricted access' );
require_once($ugamela_root_path.'/config_mailserver.php');
require_once($ugamela_root_path."libs/phpmailer/phpmailer.php");	

function sendMails($from, $tos, $ccs, $bccs, $subject, $content){
	$mail = new PHPMailer();

	$mail->IsHTML(true);
	$mail->IsSMTP();

	foreach($tos as $to){
		;
	}
}
global $err;
function sendMail($from,$fromName, $to, $toName, $subject, $content){
	global $err;
	$mail = new PHPMailer();

	$mail->IsHTML(true);
	$mail->IsSMTP();

	$mail->AddAddress($to, $toName);

	$mail->Host = 'localhost';
	$mail->From = $from;
	$mail->Sender = $from;
	$mail->FromName = $fromName;
	
	$mail->Subject = $subject;
	$mail->Body    = $content;

	if ( !$mail->Send() ) {
		PRINT "PROBLEMS SENDING MAIL TO: $p_recipient<br />";
		PRINT 'Mailer Error: '.$mail->ErrorInfo.'<br />';
		$err = $mail->ErrorInfo;
		if ( $p_exit_on_error )  {
			exit;
		} else {
			return false;
		}
	}
	return true;
}
//sendMail('mouse23680@yahoo.com','mouse23680@yahoo.com', 'trung.dang@ketnoitre.info', 'Trung Dang', 'text', 'test')
?>