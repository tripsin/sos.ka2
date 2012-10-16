<?php
require("config.php");
if($page->authorized){
	$c="<h5>Список специальностей</h5>";

	$sql="SELECT * FROM spec";
	$result=mysql_query($sql);
	if(!$result)
		{$c.="<br>".mysql_error();}
	else
		while ($row = mysql_fetch_row($result))
		{
			$c.="<br>$row[1]  $row[2]";
		}
	$page->content_clear();
	$page->content_add($c);
	$page->maintpl();
}
else {header("Location: http://".$page->base_address);}
?>