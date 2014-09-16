<?php include "uplinks.html.php"; ?>
<form method='post'>
	<table>
		<tr valign='top'>
			<td>Name:</td>
			<td><input type='text' name='element[name]'></td>
		</tr>
		<tr valign='top'>
			<td>Phone:</td>
			<td><input type='text' name='element[phone]'></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type='submit' name='doAdd' value='Add'></td>
		</tr>
	</table>
</form>
<hr>
<?php if ($emptyfields == true) include "addemptyfields.html.php"; ?>