<?php
require("config.php");
require("n.php");
// TODO!! Проверить входящие данные



//--------------------------------------------------------------------------------------
function DescribeServ($code,$indx)
{
	global $col_count;
 	switch($indx-$col_count+6)
	{
		case 0:
			switch ($code)
			{
				case 1: return "Взрослые ($code***)"; break;
				case 2: return "Дети ($code***)"; break;
				case 3; return "Беременные ($code***)"; break;
				case 4; return "Андрология ($code***)"; break;
				case 5; return "Стоматология ($code***)"; break;
				case 7; return "Довлачебный прием ($code***)"; break;
				default: return "Неучтенный код ($code***)";
			} break;
		case 1:
			switch ($code)
			{
				case 1: return "Амбулаторный прием (*$code**)"; break;
				case 2: return "На дому (*$code**)"; break;
				case 3: return "На выезде(*$code**)"; break;
				case 4: return "Выездная поликлиника(*$code**)"; break;
				case 5: return "Неотложная помощь(*$code**)"; break;
				case 6: return "Амбулаторная хирургия(*$code**)"; break;
				case 7: return "Травмпункт(*$code**)"; break;
				case 8: return "Комплексное(*$code**)"; break;
				default: return "Неучтенный код (*$code**)";
			} break;
		case 2:
			switch ($code)
			{
				case 1: return "Лечебно-диагностическое(**$code*)"; break;
				case 2: return "Констатация смерти(**$code*)"; break;
				case 3: return "Диспансерное(**$code*)"; break;
				case 4: return "Профилатическое(**$code*)"; break;
				case 5: return "Консультативное(**$code*)"; break;
				case 6: return "Консультативное диагностическое(**$code*)"; break;
				case 7: return "Консультативное диспансерное(**$code*)"; break;
				case 8: return "Консультативное профилактическое(**$code*)"; break;
				case 9: return "Прочее (справки и др.) (**$code*)"; break;
				default: return "Неучтенный код (**$code*)";
			} break;
		case 3:
			switch ($code)
			{
				case 1: return "Первичное"; break;
				case 2: return "Повторное"; break;
				case 3: return "В приемном отделении"; break;
				case 4: return "Доп. диспансеризация, \"Здоровый мозг\", АСПОН"; break;
				case 5: return "Стоматология"; break;
				case 6: return "Исследование"; break;
				default: return "Неучтенный код";
			} break;
		default: die("Ошибка криворукого программиста");
	}
}
//--------------------------------------------------------------------------------------
function DescribeCode($code,$field)
{
	switch($field)
	{
		case "MU":
			switch ($code)
			{
				case 290: return "Tалицкая ЦРБ ($code)"; break;
				default: return "МУЗ с кодом ($code)";
			} break;

		case "SECTN":
			switch ($code)
			{
				case 10: return "Поликлиника ($code)"; break;
				case 11: return "Женская консультация ($code)"; break;
				case 26: return "Приемное отделение ($code)"; break;
				case 32: return "Поликлиника (2 уровень)"; break;
				case 27: return "ОВП Елань"; break;
				case 29: return "ОВП Пионер"; break;
				default: return "Отделение с кодом ($code)";
			} break;
		case "ASPEC":
//			echo "SELECT name FROM spec WHERE code=$code"; //debug
			$result=mysql_query("SELECT name FROM spec WHERE code=$code");
			if(!$result) {return "<br>".mysql_error();}
			else
			{
				$row = mysql_fetch_row($result);
				mysql_free_result($result);
				if($row){return "{$row[0]} ($code)";}
				else {return "Неизвестная специальность ($code)";}
			}
		case "AGENT":
//			echo "SELECT fio FROM Mperson WHERE code=$code"; //debug
			$result=mysql_query("SELECT fio FROM Mperson WHERE code=$code");
			if(!$result) {return "<br>".mysql_error();}
			else
			{
				$row = mysql_fetch_row($result);
				mysql_free_result($result);
				if($row){return "{$row[0]} ($code)";}
				else {return "Незарегистрированный ($code)";}
			}
		default: return "Что-то с кодом ($code)";
	}
}
//--------------------------------------------------------------------------------------
function ShowSumAndCount($where)
{	//echo "ShowSumAndCount - called<br>"; //debug
	$result=mysql_query("SELECT COUNT(DISTINCT TAPID),SUM(TPRICE),COUNT(TPRICE) FROM B_AMBSER ".$where);
	//echo "SELECT COUNT(DISTINCT TAPID),SUM(TPRICE),COUNT(TPRICE) FROM B_AMBSER ".$where."<br>"; //debug
	if(!$result) {return "<br>".mysql_error();}
	else
	{
		$row = mysql_fetch_row($result);
		$tapnum_count=$row[0];$sum=$row[1];$count=$row[2];
		mysql_free_result($result);
		return "<td align=\"right\">$tapnum_count</td>"
				."<td align=\"right\">$count</td>"
				."<td align=\"right\">$sum</td>";
	}
}
//---------------------------------------------------------
function ServArray($arr,$indx,$where,$prev,$serv_str)
{//	echo "ServArray - called<br>"; //debug
	global $c, $fields, $col_count;
	$i=$indx-count($fields);
	settype($retarray,"array");
	foreach($arr as $a)
		if(!in_array($a[$i],array_keys($retarray)))
			if($i==0 || substr($a,0,$i)==$prev)
				if($i==3)
				{
					$c.="<tr>".str_repeat("<td>&nbsp;&nbsp;</td>",$indx)."<td colspan=".($col_count-$indx-2).">".DescribeServ($a[$i],$indx)." (".$serv_str.$a[$i].")"."</td>";
					$c.=ShowSumAndCount("$where AND SERV=$serv_str{$a[$i]}");
					$c.="</tr>\n";
				}
				else
				{
					$c.="<tr>".str_repeat("<td>&nbsp;&nbsp;</td>",$indx)."<td colspan=".($col_count-$indx-2).">".DescribeServ($a[$i],$indx)."</td>";
					$c.=ShowSumAndCount("$where AND SERV LIKE '"
					        .str_pad($serv_str.$a[$i],4,"_",STR_PAD_RIGHT)."'");
					$c.="</tr>\n";
					$retarray[$a[$i]]=ServArray($arr,$indx+1,$where,$prev.$a[$i],$serv_str.$a[$i]);
				}
}
//---------------------------------------------------------
function EnumSERV($where,$indx)
{
//	echo "EnumSERV - called<br>"; //debug
	global $c, $fields, $col_count;
		preg_match("/({$fields[$indx]}=)([\d]+)/",$where,$matches);
		$c.="<tr>".str_repeat("<td>&nbsp;&nbsp;</td>",$indx)."<td colspan=".($col_count-$indx-2).">".DescribeCode($matches[2],$fields[$indx])."</td>";
		$c.=ShowSumAndCount($where);
		$c.="</tr>\n";
 	$sql="SELECT DISTINCT SERV FROM B_AMBSER $where ORDER BY SERV";
// 	echo $sql."<br>"; //debug
	$result=mysql_query($sql);
	if(!$result) {echo("<br>".mysql_error());}
	else while ($row = mysql_fetch_row($result)) $serv[]=$row[0];
	mysql_free_result($result);
	$indx++;
  	ServArray($serv,$indx,$where,"","");
}
//---------------------------------------------------------
function EnumValues($where,$indx)
{
//	echo "EnumValues - called<br>"; //debug
	global $c, $fields, $col_count;

	if($indx>-1)
	{
		preg_match("/({$fields[$indx]}=)([\d]+)/",$where,$matches);
		$c.="<tr>".str_repeat("<td>&nbsp;&nbsp;</td>",$indx)."<td colspan=".($col_count-$indx-2).">".DescribeCode($matches[2],$fields[$indx])."</td>";
		$c.=ShowSumAndCount($where);
		$c.="</tr>\n";
	}
	$indx++;
	$sql="SELECT DISTINCT {$fields[$indx]} FROM B_AMBSER $where ORDER BY {$fields[$indx]}";
//	echo $sql; //debug
	$result=mysql_query($sql);
	if(!$result) {echo("<br>".mysql_error());}
	else
		{
//			print_r(mysql_fetch_row($result));//debug
			while ($row = mysql_fetch_row($result))
			{//				echo "I am here!<br>"; //debug
			 	if($indx<count($fields)-1)
					{EnumValues("$where AND {$fields[$indx]}={$row[0]}",$indx);}
	   			else
	   				{EnumSERV("$where AND {$fields[$indx]}={$row[0]}",$indx);}
	   		}
   		}
	mysql_free_result($result);
}
//--------------------------------------------------------------------------------------
/*function decade($num){
	global $accno;
   	$accno[]= $num;
   	$accno[]= $num + 100;
   	$accno[]= $num + 200;
}*/
//--------------------------------------------------------------------------------------

if($page->authorized){
	set_time_limit(0);
   	$accno_list=array();

	if(isset($_GET["id"]))
	{
		list($id_type, $id_num) = explode("_", $_GET["id"]);

		switch ($id_type){
			case "accno":
			    $accno_list[]=$id_num;
			    $description = "Отчет по счету №".$id_num.".";
			    break;
			case "month":
		    	$accno_list = $accno[$id_num];
		    	$description = "Отчет за ".$month[$id_num-1]." 2009 года.";
			    break;
			case "quartal":
			    for($m=1;$m<=3;$m++)
			    	foreach ($accno[($id_num - 1)*3 + $m] as $a)
			    		$accno_list[] = $a;
				//print_r($accno_list);exit;
		    	$description = "Отчет за ".$id_num." квартал 2009 года.";
			    break;
			case "year":
			    for($m=1;$m<=12;$m++)
			    	foreach ($accno[$m] as $a)
			    		$accno_list[] = $a;
		    	$description = "Отчет за 2009 год.";
			    break;
			default: header("Location: http://".$page->base_address);
		}
	}
	else
	{
		header("Location: http://".$page->base_address);
	}

	sort($accno_list);
	$where_accno="IN (".implode(",",$accno_list).") ";
	//echo $where_accno; exit; //debug
	if      (isset($_GET["full"]))         {$expanded="full";}
	else if (isset($_GET["consolidated"])) {$expanded="consolidated";}
	else if (isset($_GET["only_codes"]))   {$expanded="only_codes";}
	else $expanded="error";


	//echo $expanded; //debug

	$c="<center><h4>$description ";
	$table_header = "<div align='center'><table border='1' cellpadding='0' cellspacing='0' id='report'>";

	switch ($expanded)
	{		case "full":
		{        	$fields=array("MU","SECTN","ASPEC","AGENT");
        	$c.="Развернутая форма</h4></center>";
        	$c.= $table_header;
        	$c.="<tr><td>МУЗ</td><td>Отделение</td><td>Специальность</td><td>Врач</td>"
			."<td>Контингент</td><td>Место</td><td colspan=\"2\">Тип посещения</td>"
			."<td>Кол-во талонов</td><td>Кол-во случаев</td><td>Сумма</td></tr>";
			break;
		}
		case "consolidated":
		{        	$fields=array("MU","SECTN");
        	$c.="Краткая форма</h4></center>";
        	$c.= $table_header;
        	$c.="<tr><td>МУЗ</td><td>Отделение</td><td>Контингент</td><td>Место</td>"
			."<td colspan=\"2\">Тип посещения</td>"
			."<td>Кол-во талонов</td><td>Кол-во случаев</td><td>Сумма</td></tr>";
			break;
		}
		case "only_codes":
		{        	$fields=array("MU");
        	$c.="По типам посещений</h4></center>";
        	$c.= $table_header;
        	$c.="<tr><td>МУЗ</td><td>Контингент</td><td>Место</td>"
			."<td colspan=\"2\">Тип посещения</td>"
			."<td>Кол-во талонов</td><td>Кол-во случаев</td><td>Сумма</td></tr>";
			break;
		}
		default: header("Location: http://".$page->base_address);
	}
    $col_count=count($fields)+6;

/*	if($expanded){$fields=array("MU","SECTN","ASPEC","AGENT");} // Полный отчет
	else {$fields=array("MU","SECTN");} //Краткий отчет
	$col_count=count($fields)+6; */

/*	if($expanded){$c.="Развернутая форма</h4></center>";} // Полный отчет
	else {$c.="Краткая форма</h4></center>";} //Краткий отчет      */

//	$c.= $table_header;
/*	if($expanded)
		{$c.="<tr><td>МУЗ</td><td>Отделение</td><td>Специальность</td><td>Врач</td>"
			."<td>Контингент</td><td>Место</td><td colspan=\"2\">Тип посещения</td>"
			."<td>Кол-во талонов</td><td>Кол-во случаев</td><td>Сумма</td></tr>";}
	else
		{$c.="<tr><td>МУЗ</td><td>Отделение</td><td>Контингент</td><td>Место</td>"
			."<td colspan=\"2\">Тип посещения</td>"
			."<td>Кол-во талонов</td><td>Кол-во случаев</td><td>Сумма</td></tr>";}   */

	$sql="CREATE TEMPORARY TABLE B_AMBSER SELECT * FROM B_AMBSER WHERE TAPID IN (SELECT TAPID FROM B_AMBREE WHERE ACCNO $where_accno)";
//	echo $sql."<br>"; //debug
	$result=mysql_query($sql);
	if(!$result)
	 {echo("<br>".mysql_error());} //Может быть ошибка когда кончается место на флешке
	else
	 {EnumValues("WHERE 1=1",-1);}

	 include($page->tpls_path."report.tpl");
	 echo $c."</table></div></body></html>";
}
else {header("Location: http://".$page->base_address);}
?>