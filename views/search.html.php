<?php
include "head.html.php";
include "uplinks.html.php"; ?>
<form action="" method="post">
	<table>
		<tr valign="top">
			<td>Name:</td>
			<td><input type="text" name="element[name]"></td>
		</tr>
		<tr valign="top">
			<td>Phone:</td>
			<td><input type="text" name="element[phone]"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="doSearch" value="Search"></td>
		</tr>
	</table>
</form>
<hr>
<?php include "searchsortlinks.html.php"; ?>
<table>
<?php foreach($searchbook as $element) { ?>
	<tr>
		<td><b><?=htmlspecialchars($element['name'])?></b>:</td>
		<td><i><?=htmlspecialchars($element['phone'])?></i></td>
<?php } ?>
</table>
<hr>
<?php if ($emptyfields == true) include "searchemptyfields.html.php";
include "bottom.html.php"; ?>