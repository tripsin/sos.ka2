<?php
require("config.php");
if($page->authorized){
	$page->maintpl();
}
else {header("Location: http://".$page->base_address);}
?>