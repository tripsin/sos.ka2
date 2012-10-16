<?php
require("config.php");
if($page->authorized)
{
	set_time_limit(0);
	$mkb="ABCDEFGHIJKLMNOPQRSTUVWXY";
	echo "<table lang='ru' border='1' cellpadding='0' cellspacing='0'>";
	echo "<tr><td>Тип нозологии</td><td>Первичные</td><td>Вторичные</td>";
	for($i=0;$i<strlen($mkb);$i++)
	{
		$sql="SELECT MKB FROM B_AMBREE WHERE TAPID IN (SELECT TAPID FROM B_AMBSER WHERE (SERV LIKE '1111' OR SERV LIKE '1211') AND AGENT = 17) AND MKB LIKE '$mkb[$i]%'";
		$result=mysql_query($sql);
		if (!$result)
			{$perv = mysql_error();}
		else
			{$perv = mysql_num_rows($result);}
		mysql_free_result($result);

		$sql="SELECT MKB FROM B_AMBREE WHERE TAPID IN (SELECT TAPID FROM B_AMBSER WHERE (SERV LIKE '1112' OR SERV LIKE '1212') AND AGENT = 17) AND MKB LIKE '$mkb[$i]%'";
		$result=mysql_query($sql);
		if (!$result)
			{$povt = mysql_error();}
		else
			{$povt = mysql_num_rows($result);}
		mysql_free_result($result);

		if ($perv or $povt) echo("<tr><td>$mkb[$i]</td><td>$perv</td><td>$povt</td></tr>");
	}
	echo "</table>";
}
else {header("Location: http://".$page->base_address);}
?>