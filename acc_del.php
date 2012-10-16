<?php
require("config.php");
if($page->authorized){
	$page->content("acc_del.tpl");
	$page->maintpl();
}
else {header("Location: http://".$page->base_address);}
?>