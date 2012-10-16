<?php
require("config.php");
if($page->authorized){
	set_time_limit(0);
	$amb=array("ree"=>"B_AMBREE.DBF",
	            "lc"=>"B_AMBLC.DBF",
			   "ser"=>"B_AMBSER.DBF",
			   "sop"=>"B_AMBSOP.DBF",
			    "tf"=>"B_AMBTF.DBF");
	foreach($amb as $key=>$name)
	{
			// Получаю массив имен таблиц в базе
		$table[$key]=substr($name,0,strlen($name)-4);
			// Открываю все dbf-файлы
		$famb[$key]=dbase_open($page->tempdir.$name,0);
		$hh=dbase_get_header_info($famb[$key]);
		if(isset($harr)) unset($harr);
		foreach($hh as $h)
		{
			// Получаю массивы имен столбцов, имеющих тип "date"
			if($h["type"]=="date") $date_cols[$key][]=$h["name"];
			$harr[]=$h["name"];
		}
		//  Получаю массив строк с именами столбцов
		$head_of_amb[$key]=implode(", ",$harr);

		// Загоняю все dbf-файлы в память
		for($i=1;$i<=dbase_numrecords($famb[$key]);$i++)
			$mem[$key][$i]=dbase_get_record_with_names($famb[$key],$i);

		//echo("<br>count mem $key = ".count($mem[$key])); //debug print

		dbase_close($famb[$key]);
	}
	$page->log("Данные успешно распакованы и загружены в память.");

	for($i=1;$i<=count($mem["ree"]);$i++)
	{
		$currec=$mem["ree"][$i];
		if ($currec["deleted"]==1) {continue;} // Пропускаю удаленные записи (на всякий случай)
		else {unset($currec["deleted"]);} // Удаляю ненужное поле deleted в конце массива
		// Привожу дату к формату mysql
		foreach($date_cols["ree"] as $d)
		{
			$s=$currec[$d];
			if($s==0) {$currec[$d]=0;}
				 else {$currec[$d]=$s[0].$s[1].$s[2].$s[3]."-".$s[4].$s[5]."-".$s[6].$s[7];}
		}
		// Заключаю строки в кавычки
		foreach($currec as $r=>$v) if(is_string($v)) $currec[$r]="'$v'";

		// Преобразование cp866 -> win1251
		$fields=convert_cyr_string(implode(", ",$currec),"a","w");

		// Собираю запрос на добавление записи в b_ambree
		$query="INSERT INTO B_AMBREE (".$head_of_amb["ree"]." ) VALUES ( ".$fields." )";
		//echo "<br>".$query."<br>"; //debug print

	  	// Выполняю запрос
		if(!mysql_query($query)) $page->log(mysql_error());

		// Данные для поиска в подчиненных таблицах
		$tapid=mysql_insert_id();
		$tapnum=trim($currec["TAPNUM"],"'");

		//echo("<BR>tapid = $tapid, TAPNUM = $tapnum<BR>"); //debug print

		unset($key);

		foreach($mem as $key=>$dbfarr)
		{
			if($key=="ree") continue; // Пропускаю главную таблицу

			unset($finded);
			for($j=1;$j<=count($dbfarr);$j++)
				if($dbfarr[$j]["TAPNUM"]==$tapnum)
					$finded[]=$j;
//	        echo count($finded)."- I am here!<br>"; //debug
			if(count($finded)>0) foreach($finded as $recnum)
			{
				$currec=$dbfarr[$recnum];
				if ($currec["deleted"]==1) {continue;} // Пропускаю удаленные записи (на всякий случай)
				else {unset($currec["deleted"]);} // Удаляю ненужное поле deleted в конце массива
				// Привожу дату к формату mysql
				if(isset($date_cols[$key])) foreach($date_cols[$key] as $d)
				{
					$s=$currec[$d];
					if($s==0) {$currec[$d]=0;}
						else {$currec[$d]=$s[0].$s[1].$s[2].$s[3]."-".$s[4].$s[5]."-".$s[6].$s[7];}
				}
				// Заключаю строки в кавычки
				foreach($currec as $r=>$v) if(is_string($v)) $currec[$r]="'$v'";

				// Преобразование cp866 -> win1251
				$fields=convert_cyr_string(implode(", ",$currec),"a","w");

				// Собираю запрос на добавление записи в b_ambree
				$query="INSERT INTO ".$table[$key]." (TAPID, ".$head_of_amb[$key]." ) VALUES ( $tapid, $fields )";
				// Выполняю запрос
		        if(!mysql_query($query)) $page->log(mysql_error());
				//$page->log("<br>$query<br>"); //debug print
			}
		}
	}

	foreach($mem as $key=>$arr)	$page->log("В таблицу {$table[$key]} добавлено ".count($arr)." записей.");

	$page->log("ВСЕГО:");
	foreach($table as $name)
	{
		$ret=mysql_query("SELECT COUNT( * ) FROM $name");
		$page->log("В таблице $name - ".mysql_result($ret,0)." записей.");
	}
	set_time_limit(30);
	$page->clearTempDir();
	$page->save();
}
header("Location: http://".$page->base_address);
?>