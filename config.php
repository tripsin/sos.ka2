<?php

$month = array('€нварь', 'февраль', 'март',
				'апрель', 'май', 'июнь',
				'июль', 'август', 'сет€брь',
				'окт€брь', 'но€брь', 'декабрь');

$dbf_names=array(	"B_AMBLC.DBF",
					"B_AMBREE.DBF",
					"B_AMBSER.DBF",
					"B_AMBSOP.DBF",
					"B_AMBTF.DBF"   );

class Page {
	var $base_address = "www.sos.ka2";
	var $maintpl = "main.tpl";
	var $tpls_path = "./tpls/";
	var $tempdir = "temp/";
	var $bindir = "bin/";
	var $unpacker = "unarj32_patched_by_tripsin.exe";

	var $db_location = "localhost";
	var $db_name = "sos2009";//"sos"; - это 2008
	var $db_user = "soska";
	var $db_password = "sos";

	var $username;
	var $usernote;
	var $authorized = false;
	var $wrong_password = false;
	var $page_content;
	var $log;
	var $uploaded_accno;

	function tpl($tpl){
		include($this->tpls_path.$tpl);
	}

	function maintpl(){
		$this->save();
		$this->tpl($this->maintpl);
	}

	function save(){
		$_SESSION["page"] = serialize($this);
	}

	function content($tplname){
		$this->content_clear();
		$this->content_add( $tplname, true);
	}

	function content_clear(){
		$this->page_content = array();
	}

	function content_add($data, $is_tpl = false){
		$this->page_content[] = array(	'data'   => $data,
										'is_tpl' => $is_tpl);
	}

	function content_show(){
		foreach($this->page_content as $content){
			if($content['is_tpl']){
				include($this->tpls_path.$content['data']);
			}
			else {
				echo $content['data'];
			}
		}
	}

	function log($text){
		$this->log = date("H:i:s - ").$text."\n".$this->log;
	}

	function clearTempDir(){
		$dir=scandir($this->tempdir);
		$this->log("Ќачата очистка временной папки.");
		foreach($dir as $f)
		{
			if($f!="." && $f!=".." && is_file($this->tempdir.$f))
			{
		  		if(@unlink($this->tempdir.$f))
			  	{$this->log("”дален файл ".$f);}
			  	else
			  	{$this->log("!!! Ќ≈ ”ƒјЋќ—№ удалить файл ".$f);}
			}
		}
		$this->log("ќчистка временной папки закончена");
	}
}

session_start();
if(isset($_SESSION["page"]))
{
	$page = unserialize($_SESSION["page"]);
}
else
{
	$page = new Page;
}

$mysql_connection=@mysql_connect($page->db_location,
								 $page->db_user,
								 $page->db_password);
if (!$mysql_connection)
	{
		die($page->tpl("nonconnected.tpl"));
	}
else
	{
		if (!@mysql_select_db($page->db_name,$mysql_connection))
 			{
 				die($page->tpl("nonconnected.tpl"));
 			}
	}
?>