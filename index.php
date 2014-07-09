<?php
	session_start();
	include_once("dbinfo.php");
	include_once("functions.php");
	
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	
	if ($mysqli->connect_error)
	{
		die('Connect Error: ' . $mysqli->connect_error());
	}
	
	$sms_carriers_file = file_get_contents("https://raw.github.com/cubiclesoft/email_sms_mms_gateways/master/sms_mms_gateways.txt");
	$json = json_decode($sms_carriers_file, true);
	

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<title></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css">
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
	</head>
	
	<body>
<?php	
/* 	echo "<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo "</pre>"; */
	
	if (isset($_SESSION['is_logged_in']))
	{
		$user_id = $_SESSION['user_id'];
	}
	
	if (isset($_POST['login']))
	{
		$username = protect($_POST['username']);
		$password = md5(protect($_POST['password']));
		$result = $mysqli->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
		if ($result->num_rows != 1)
		{
			echo "wrong";
		}
		else
		{
			$row = $result->fetch_assoc();
			$_SESSION['is_logged_in'] = true;
			$_SESSION['user_id'] = $row['user_id'];
			$_SESSION['username'] = $row['username'];
			//$_SESSION['user_email'] = $row['user_email'];
			$_SESSION['alert_on_coming'] = $row['alert_on_coming'];
			$_SESSION['alert_on_here'] = $row['alert_on_here'];
			$_SESSION['number'] = $row['number'];
			$_SESSION['number_status'] = $row['number_status'];
		}
	}
	
	if (isset($_POST['register']))
	{
		// TO DO NEXT
		$username = protect($_POST['username']);
		$password1 = protect($_POST['password1']);
		$password2 = protect($_POST['password2']);
		$user_email = protect($_POST['email']);
		$errors = array();
		
		if (empty($username))
		{
			$errors[] = "Username is not defined!";
		}
		if (empty($password1))
		{
			$errors[] = "Password is not defined!";
		}
		
		
	}
	
	if (isset($_POST['alert_settings']))
	{
		$alert_on_coming = $_SESSION['alert_on_coming'] = isset($_POST['coming']) ? 1 : 0;
		$alert_on_here = $_SESSION['alert_on_here'] = isset($_POST['here']) ? 1 : 0;
		$mysqli->query("UPDATE users SET alert_on_coming=$alert_on_coming, alert_on_here=$alert_on_here WHERE user_id=$user_id");
	}
	
	if (isset($_POST['remove']))
	{
		$mysqli->query("UPDATE users SET number=NULL, sms_email=NULL, number_status=NULL, verification_code=NULL WHERE user_id=$user_id");
		$_SESSION['number'] = null;
		$_SESSION['number_status'] = null;
	}
	
	if (isset($_POST['add']))
	{
		$number = preg_replace("/[^0-9]+/", "", protect($_POST['number']));
		
		$country_code = protect($_POST['country']);
		$which_carriers = $country_code . "-carriers";
		$carrier_name = protect($_POST[$which_carriers]);
		$template_email = $json['sms_carriers'][$country_code][$carrier_name][1];
		
		$sms_email = str_replace("{number}", $number, $template_email);
		
		$verification_code = rand(100000, 999999);
		
		send_verification_mail($sms_email, $verification_code);
		
		$mysqli->query("UPDATE users SET number='$number', sms_email='$sms_email', number_status='pending', verification_code='$verification_code' WHERE user_id=$user_id");
		
		$_SESSION['number'] = $number;
		$_SESSION['number_status'] = 'pending';
	}
	
	if (isset($_POST['verify']))
	{
		$verification_code = protect($_POST['verification_code']);
		$result = $mysqli->query("SELECT verification_code FROM users WHERE user_id=$user_id");
		$row = $result->fetch_assoc();
		if ($row['verification_code'] == $verification_code)
		{
			$mysqli->query("UPDATE users SET number_status='active' WHERE user_id=$user_id");
			$_SESSION['number_status'] = 'active';
		}
		else
		{
			echo "invalid verification code";
		}
	}
	
	if (isset($_SESSION['is_logged_in']))
	{
		include_once("profile.php");
	}
	else // not logged in
	{
		include_once("entrance.php");
	}
	
	include_once("footer.php");
	
	$mysqli->close();
?>