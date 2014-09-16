<?php include "uplinks.html.php";
include "sortlinks.html.php"; ?>
<table>
<?php foreach($book as $element) { ?>
	<tr>
		<td><b><?=htmlspecialchars($element['name'])?></b>:</td>
		<td><i><?=htmlspecialchars($element['phone'])?></i></td>
		<?php $url = dirname($_SERVER['SCRIPT_NAME'])."/delete/".$element['name']; ?>
		<td><a href='<?=$url?>'>[Delete]</a></td>
		<?php $url = dirname($_SERVER['SCRIPT_NAME'])."/name/".$element['name']; ?>
		<td><a href='<?=$url?>'>[Manage]</a></td>
	</tr>
<?php } ?>
</table>
<hr>