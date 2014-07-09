<form action="index.php" method="post">
	
	Phone number: <input type="text" name="number"> <br>
	
	Country:
	<select name="country">
		<?php
			foreach ($json['countries'] as $country_code => $country_name)
			{
				echo "<option value=\"$country_code\">$country_name</option>\n";
			}
		?>
	</select> <br>
	
	Carrier:
	<?php
		foreach ($json['sms_carriers'] as $country_code => $carrier_array)
		{
			echo "<select name=\"$country_code-carriers\" style=\"display:none;\">\n";
			foreach ($carrier_array as $carrier_name => $carrier_data)
			{
				echo "<option value=\"$carrier_name\">" . $carrier_data[0] . "</option>\n";
			}
			echo "</select>\n";
		}
	?> <br>
	
	<input type="submit" name="add" value="Submit number">
	
</form>

<script>
$(document).ready(function()
{
	$('select[name="' + $('select[name="country"] option:selected').val() + '-carriers"]').show();

	$('select[name="country"]').on('change', function(e)
	{
		$('select[name$="-carriers"]').hide();
		var country_code = $('select[name="country"] option:selected').val();
		$('select[name="' + country_code + '-carriers"]').show();
	});
	
	$('input[name="add"]').on('click', function(e)
	{
		//alert(e);
	});
});
</script>