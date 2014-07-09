<?php // register ajax
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
	elseif (empty($_POST['password1']))
	{
		$response['failure'] = true;
		$response['message'] = "Please enter a password.";
	}
	elseif (empty($_POST['password2']))
	{
		$response['failure'] = true;
		$response['message'] = "Please reenter your password.";
	}
	elseif (empty($_POST['email']))
	{
		$response['failure'] = true;
		$response['message'] = "Please enter an email address.";
	}
	elseif (preg_match("/[^a-zA-Z0-9]/", $_POST['username']))
	{
		$response['failure'] = true;
		$response['message'] = "Username can only contain alphanumeric characters.";
	}
	elseif (strlen($_POST['username'])<6 || strlen($_POST['username'])>16)
	{
		$response['failure'] = true;
		$response['message'] = "Username must be between 6 and 16 characters.";
	}
	elseif (strlen($_POST['password1'])<6 || strlen($_POST['password1'])>32)
	{
		$response['failure'] = true;
		$response['message'] = "Password must be between 6 and 32 characters.";
	}
	elseif (!preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $_POST['email']))
	{
		$response['failure'] = true;
		$response['message'] = "Please enter a valid email address.";
	}
	elseif ($_POST['password1'] !== $_POST['password2'])
	{
		$response['failure'] = true;
		$response['message'] = "Passwords do not match.";
	}
	else
	{
		$username = protect($_POST['username']);
		$password = md5(protect($_POST['password1']));
		$email = protect($_POST['email']);
		
		$result = $mysqli->query("SELECT user_id FROM users WHERE username='$username'");
		if ($result->num_rows != 0)
		{
			$response['failure'] = true;
			$response['message'] = "Username is already taken.";
		}
		else
		{
			$result = $mysqli->query("SELECT user_id FROM users WHERE user_email='$email'");
			if ($result->num_rows != 0)
			{
				$response['failure'] = true;
				$response['message'] = "This email address is already in use.";
			}
			else
			{
				$sql = "INSERT INTO users (username, password, user_email, creation_timestamp, modified_timestamp) VALUES ('$username', '$password', '$email', NOW(), NOW())";
				$result = $mysqli->query($sql);
				
				$_SESSION['is_logged_in'] = true;
				$_SESSION['user_id'] = $mysqli->insert_id;
				$_SESSION['username'] = $username;
				$_SESSION['alert_on_coming'] = 0;
				$_SESSION['alert_on_here'] = 0;
				$_SESSION['number'] = null;
				$_SESSION['number_status'] = null;
				
				$response['success'] = true;
			}
		}
	}
	
	echo json_encode($response);
?>