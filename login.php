<?php // login ajax
	session_start();
	include_once("dbinfo.php");
	include_once("functions.php");
	
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	
	if ($mysqli->connect_error)
	{
		die('Connect Error: ' . $mysqli->connect_error());
	}

	$response = array();

	if ($_SESSION["is_logged_in"])
	{
		$response['failure'] = true;
		$response['message'] = "You are already logged in.";
	}
	elseif (empty($_POST['username']))
	{
		$response['failure'] = true;
		$response['message'] = "Please enter a username.";
	}
	elseif (empty($_POST['password']))
	{
		$response['failure'] = true;
		$response['message'] = "Please enter a password.";
	}
	else
	{
		$username = protect($_POST["username"]);
		$password = md5(protect($_POST["password"]));
		$result = $mysqli->query("SELECT user_id FROM users WHERE username='$username'");
		if ($result->num_rows != 1)
		{
			$response['failure'] = true;
			$response['message'] = "That username does not exist.";
		}
		else
		{
			$result = $mysqli->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
			if ($result->num_rows != 1)
			{
				$response['failure'] = true;
				$response['message'] = "Incorrect password.";
			}
			else
			{
				$row = $result->fetch_assoc();
				$_SESSION['is_logged_in'] = true;
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['alert_on_coming'] = $row['alert_on_coming'];
				$_SESSION['alert_on_here'] = $row['alert_on_here'];
				$_SESSION['number'] = $row['number'];
				$_SESSION['number_status'] = $row['number_status'];
				$response['success'] = true;
			}
			
		}
	}
	
	echo json_encode($response);
?>