<?php
require("config.php");
if($page->authorized){    for($i=0;$i<count($dbf_names);$i++)
    {
    	$tname=substr($dbf_names[$i],0,strlen($dbf_names[$i])-4);
    	if($tname=="B_AMBREE") continue;
    	if(mysql_query("DELETE FROM $tname WHERE TAPID IN (SELECT TAPID FROM B_AMBREE WHERE ACCNO={$page->uploaded_accno})"))
			{$page->log("�� ������� $tname ���� ������� ".mysql_affected_rows()." �������.");}
		else
			{$page->log("$tname - ".mysql_error());}
    }
	if(mysql_query("DELETE FROM B_AMBREE WHERE ACCNO={$page->uploaded_accno}"))
		{$page->log("�� ������� B_AMBREE ���� ������� ".mysql_affected_rows()." �������.");}
	else
		{$page->log("B_AMBREE - ".mysql_error());}
    $page->save();
	header("Location: http://".$page->base_address."/update.php");
}
else {header("Location: http://".$page->base_address);}
?>