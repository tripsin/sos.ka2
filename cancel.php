<?php
require("config.php");
if($page->authorized){
	$page->log("���������� ��������");
	$page->clearTempDir();
	$page->uploaded_accno = NULL;
	$page->save();
	header("Location: http://".$page->base_address);
}
else {header("Location: http://".$page->base_address);}
?>