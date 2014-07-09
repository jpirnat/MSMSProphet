<div class="accordion" id="accordion2">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#login">Login</a>
		</div>
		
		<div id="login" class="accordion-body collapse in">
			<div class="accordion-inner">

<form method="post" action="index.php" id="login-form">
	Username: <input type="text" name="username">
	<span id="login-username-error" class="error"></span><br>
	
	Password: <input type="password" name="password">
	<span id="login-password-error" class="error"></span><br>

	<input type="submit" value="Login" name="login"><br>
</form>

			</div>
		</div>
	</div>

	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#register">Register</a>
		</div>

		<div id="register" class="accordion-body collapse">
			<div class="accordion-inner">

<form method="post" action="index.php" id="register-form">
	Username: <input type="text" name="username">
	<span id="register-username-error" class="error"></span><br>

	Password: <input type="password" name="password1">
	<span id="register-password1-error" class="error"></span><br>

	Confirm Password: <input type="password" name="password2">
	<span id="register-password2-error" class="error"></span><br>

	Email: <input type="email" name="email">
	<span id="register-email-error" class="error"></span><br>
	
	<input type="submit" value="Register" name="register"><br>
</form>

			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function()
{
	$('.accordion-toggle').on('click', function(e)
	{
		if ($(this).parents('.accordion-group').children('.accordion-body').hasClass('in'))
		{
			e.stopPropagation();
		}
	});
	
	
	$("form#login-form").submit(function()
	{
		$.post("login.php", $(this).serialize(), function(data)
		{
			console.log(data);
			data = JSON.parse(data);
			if ('success' in data){
				window.location = window.location;
			}
			else
			{
				console.log(data);
				alert(data.message);
			}
		});
		return false;
	});
	
	$("form#register-form").submit(function()
	{
		$.post("register.php", $(this).serialize(), function(data)
		{
			console.log(data);
			data = JSON.parse(data);
			if ('success' in data){
				window.location = window.location;
			}
			else
			{
				console.log(data);
				alert(data.message);
			}
		});
		return false;
	});
});
</script>