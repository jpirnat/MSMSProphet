<?php
	function protect($string)
	{
		global $mysqli;
		$string = $mysqli->real_escape_string($string);
		$string = strip_tags($string);
		return $string;
	}
	
	function send_verification_mail($to, $verification_code)
	{
	
		require_once("../PHPMailer-master/class.phpmailer.php");
	
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "smtp.mail.yahoo.com";
		$mail->Port = 465;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Username = "";
		$mail->Password = "";
		
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