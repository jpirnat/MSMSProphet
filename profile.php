<p>Alright so this is basically just a glorified email notification service, but I'm using <a href="https://en.wikipedia.org/wiki/List_of_SMS_gateways">magic</a> to turn it into a text notification system. This is the best I can do while still keeping this thing free for everybody (myself included).</p>

<form name="settings" method="post" action="index.php">
	<input type="checkbox" name="coming" <?php echo $_SESSION['alert_on_coming'] ? "checked" : ""; ?>> Send alerts when update is coming <br>
	<input type="checkbox" name="here" <?php echo $_SESSION['alert_on_here'] ? "checked" : ""; ?>> Send alerts when update is here <br>
	<input type="submit" name="alert_settings" value="Update alert settings"> <br>
</form>

<form name="numberform" method="post" action="index.php">
	<br>
	<?php
		if (is_null($_SESSION['number']))
		{
			include_once('addnumber.php');
		}
		else
		{
			echo $_SESSION['number'];
			if ($_SESSION['number_status'] == 'pending')
			{
				echo " <input type=\"text\" name=\"verification_code\" placeholder=\"Enter verification code\"> <input type=\"submit\" name=\"verify\" value=\"Verify\">";
			}
			echo " <input type=\"submit\" name=\"remove\" value=\"Remove number\"> <br>";
		}
	?>
</form>

<a href="logout.php">Log out</a>

<!--
<?php
	if (is_null($_SESSION['number'])):
		include_once('addnumber.php');
	else:
		echo $_SESSION['number'];
		if ($_SESSION['number_status'] == 'pending'): ?>
			<input type="text" name="verification_code" placeholder="Enter verification code"> <input type="submit" name="verify" value="Verify">
		<?php endif; ?>
		<input type="submit" name="remove" value="Remove number"> <br>
<?php endif; ?>
-->