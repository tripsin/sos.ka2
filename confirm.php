<?php
require("config.php");
if($page->authorized){	$page->content("confirm.tpl");
	$page->maintpl();
}
else {header("Location: http://".$page->base_address);}
?>