<?php
include "head.html.php";
include "uplinks.html.php"; ?>
<table>
	<tr>
		<td><b><?=htmlspecialchars($record[0]['name'])?>:</b></td>
		<td><i><?=htmlspecialchars($record[0]['phone'])?></i></td>
	</tr>
<?php 
foreach($phones as $element) { ?>
		<tr>
			<td>&nbsp;</td>
			<td><i><?=htmlspecialchars($element['phone'])?></i></td>
			<?php $url = dirname($_SERVER['SCRIPT_NAME'])."/delphone/".$element['name']."/".$element['id']; ?>
			<td><a href='<?=$url?>'>[Delete]</a></td>
		</tr>
<?php } ?>
</table>
<hr>
<form method='post'>
	<table>
		<tr valign='top'>
			<td>Phone:</td>
			<td><input type='text' name='element[phone]'></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type='submit' name='doAddPhone' value='Add'></td>
		</tr>
	</table>
</form>
<hr>
<?php if ($emptyfields == true) include "addemptyfields.html.php";
include "bottom.html.php"; ?>