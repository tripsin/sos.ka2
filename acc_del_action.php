<?php
require("config.php");
if($page->authorized){
	if(isset($_GET["accno"])){		$acc_for_del = $_GET["accno"];
	    for($i=0;$i<count($dbf_names);$i++)
	    {
	    	$tname=substr($dbf_names[$i],0,strlen($dbf_names[$i])-4);
	    	if($tname=="B_AMBREE") continue;
	    	if(mysql_query("DELETE FROM $tname WHERE TAPID IN (SELECT TAPID FROM B_AMBREE WHERE ACCNO=$acc_for_del)"))
				{$page->log("Из таблицы $tname было удалено ".mysql_affected_rows()." записей.");}
			else
				{$page->log("$tname - ".mysql_error());}
	    }
		if(mysql_query("DELETE FROM B_AMBREE WHERE ACCNO=$acc_for_del"))
			{$page->log("Из таблицы B_AMBREE было удалено ".mysql_affected_rows()." записей.");}
		else
			{$page->log("B_AMBREE - ".mysql_error());}
	    $page->save();
		header("Location: http://".$page->base_address."/update.php");	}
}
header("Location: http://".$page->base_address);
?>