<?php
	session_start();
	
	$dbhost = "";
	$dbuser = "";
	$dbpass = "";
	$dbname = "";
	
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	
	if ($mysqli->connect_error)
	{
		die('Connect Error: ' . $mysqli->connect_error());
	}
	
	$sms_carriers_file = file_get_contents("https://raw.github.com/cubiclesoft/email_sms_mms_gateways/master/sms_mms_gateways.txt");
	$json = json_decode($sms_carriers_file, true);
	
	function send_verification_mail($to, $verification_code)
	{
	
		require_once("../PHPMailer-master/class.phpmailer.php");
	
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "smtp.mail.yahoo.com";
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "tls";
		$mail->Username = "";
		
		$mail->From = "";
		$mail->FromName = "";
		$mail->Body = "Your verification code is $verification_code";
		
		$mail->AddAddress($to);
		if ($mail->Send())
		{
			echo "Your verification code has been sent. <br>\n";
		}
		else
		{
			echo $mail->ErrorInfo;
		}
		$mail->ClearAddresses();
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<title></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	</head>
	
	<body>
