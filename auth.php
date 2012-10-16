<?php
require("config.php");
$name = $_POST["name"];
$password = $_POST["password"];
$sql = "SELECT name,note FROM users WHERE name=\"$name\" AND hash=\"$password\"";
$result = mysql_query($sql);
if(mysql_num_rows($result) == 1)
{
	$row = mysql_fetch_row($result);
	$page->username=$row[0];
	$page->usernote=$row[1];
	$page->authorized = true;
}
else
{	$page->authorized = false;
	$page->wrong_password = true;}
mysql_free_result($result);
$page->save();
if(isset($_SERVER["HTTP_REFERER"])){header("Location: ".$_SERVER["HTTP_REFERER"]);}
else {header("Location: http://".$page->base_address);}
?>