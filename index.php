<?php
require("config.php");
require("n.php");

$present_accno=array();

if($page->authorized){
///////////////////////////////////////////////

function getCheckedHtml($type,$num,$is_full){
	$html="<a class='checked ";
	switch($is_full){
		case 0:
			$html .= "red'";
			break;
		case 1:
			//$html .= "yellow'";
			$html .= "yellow'";
			break;
		case 2:
			$html .= "green' href='#' onclick='mainReport(\"{$type}_{$num}\")'";
			break;
	}
	$html .= ">";
	return $html;
}

function check_accno_array(){
	global $present_accno;
	$s="";
	$result = mysql_query("SELECT DISTINCT ACCNO FROM B_AMBREE ORDER BY ACCNO");
	if($result)
		while ($row = mysql_fetch_row($result)) {
        	 $s .= getCheckedHtml("accno",$row[0],2).$row[0]."</a>\n";
        	 $present_accno[]=$row[0];
    	}
	mysql_free_result($result);
	return $s;
}

function checkMonths(){	global $present_accno, $accno, $month;
	$s="";
	for($i=1;$i<=12;$i++)
	{		$full = true; $half = false;
		foreach ($accno[$i] as $a){			if (in_array($a, $present_accno))
				{$half = true;}
			else
				{$full = false;}
		}

		$is_full = $full ? 2 : ($half ? 1 : 0);

		$s .= getCheckedHtml("month",$i,$is_full).($month[$i-1])."</a>\n";
	}
	return $s;
}

function checkQuartals(){	global $present_accno, $accno;
	$s="";
	for($i=1;$i<=4;$i++)
	{
		$full = true; $half = false;
		for($j=1;$j<=3;$j++)
        	foreach ($accno[($i-1)*3+$j] as $a)
				if (in_array($a, $present_accno))
					{$half = true;}
				else
					{$full = false;}
		$is_full = $full ? 2 : ($half ? 1 : 0);
		$s .= getCheckedHtml("quartal",$i,$is_full).$i."</a>\n";
	}
	return $s;
}

function checkYear(){
	global $present_accno, $accno;
	$s="";
	$full = true; $half = false;
	for($i=1;$i<=12;$i++)
       	foreach ($accno[$i] as $a)
			if (in_array($a, $present_accno))
				{$half = true;}
			else
				{$full = false;}
	$is_full = $full ? 2 : ($half ? 1 : 0);
	$s .= getCheckedHtml("year","1",$is_full)."Годовой отчет"."</a>\n";
	return $s;
}
///////////////////////////////////////////////
    $page->content("index.tpl");
	$page->content_add("<hr /><h5>Номера счетов :</h5>");
    $page->content_add("<p>".check_accno_array()."</p><hr />");
	$page->content_add("<h5>Месяцы :</h5>");
    $page->content_add("<p>".checkMonths()."</p><hr />");
	$page->content_add("<h5>Кварталы :</h5>");
    $page->content_add("<p>".checkQuartals()."</p><hr />");
    $page->content_add("<p>".checkYear()."</p>");
}
else $page->content("about.tpl");
$page->maintpl();
?>