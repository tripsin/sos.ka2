<?php
require("config.php");
if($page->authorized){	$c="<h5>Список врачей</h5>";

	$sql="SELECT * FROM mperson ORDER BY aspec";
	$result=mysql_query($sql);
	if(!$result)
		{$c.="<br>".mysql_error();}
	else
		while ($row = mysql_fetch_row($result))
		{
		    $sql_spec="SELECT name FROM spec WHERE code = $row[3]";
		    $result_spec=mysql_query($sql_spec);
		    if(!$result_spec)
		    	{$spec="Неизвестная специальность";}
	        else
	        	{$spec=mysql_result($result_spec,0);}
			$c.="<br>$row[1] - $row[2] - $spec";
		}	$page->content_clear();	$page->content_add($c);
	$page->maintpl();
}
else {header("Location: http://".$page->base_address);}
?>