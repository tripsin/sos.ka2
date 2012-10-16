<?php
	require("config.php");
	session_destroy();
	header("Location: http://".$page->base_address);
?>