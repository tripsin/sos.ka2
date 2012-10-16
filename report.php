<?php
require("config.php");
require("n.php");
// TODO!! ��������� �������� ������



//--------------------------------------------------------------------------------------
function DescribeServ($code,$indx)
{
	global $col_count;
 	switch($indx-$col_count+6)
	{
		case 0:
			switch ($code)
			{
				case 1: return "�������� ($code***)"; break;
				case 2: return "���� ($code***)"; break;
				case 3; return "���������� ($code***)"; break;
				case 4; return "���������� ($code***)"; break;
				case 5; return "������������ ($code***)"; break;
				case 7; return "����������� ����� ($code***)"; break;
				default: return "���������� ��� ($code***)";
			} break;
		case 1:
			switch ($code)
			{
				case 1: return "������������ ����� (*$code**)"; break;
				case 2: return "�� ���� (*$code**)"; break;
				case 3: return "�� ������(*$code**)"; break;
				case 4: return "�������� �����������(*$code**)"; break;
				case 5: return "���������� ������(*$code**)"; break;
				case 6: return "������������ ��������(*$code**)"; break;
				case 7: return "����������(*$code**)"; break;
				case 8: return "�����������(*$code**)"; break;
				default: return "���������� ��� (*$code**)";
			} break;
		case 2:
			switch ($code)
			{
				case 1: return "�������-���������������(**$code*)"; break;
				case 2: return "����������� ������(**$code*)"; break;
				case 3: return "������������(**$code*)"; break;
				case 4: return "���������������(**$code*)"; break;
				case 5: return "���������������(**$code*)"; break;
				case 6: return "��������������� ���������������(**$code*)"; break;
				case 7: return "��������������� ������������(**$code*)"; break;
				case 8: return "��������������� ����������������(**$code*)"; break;
				case 9: return "������ (������� � ��.) (**$code*)"; break;
				default: return "���������� ��� (**$code*)";
			} break;
		case 3:
			switch ($code)
			{
				case 1: return "���������"; break;
				case 2: return "���������"; break;
				case 3: return "� �������� ���������"; break;
				case 4: return "���. ���������������, \"�������� ����\", �����"; break;
				case 5: return "������������"; break;
				case 6: return "������������"; break;
				default: return "���������� ���";
			} break;
		default: die("������ ����������� ������������");
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
				case 290: return "T������� ��� ($code)"; break;
				default: return "��� � ����� ($code)";
			} break;

		case "SECTN":
			switch ($code)
			{
				case 10: return "����������� ($code)"; break;
				case 11: return "������� ������������ ($code)"; break;
				case 26: return "�������� ��������� ($code)"; break;
				case 32: return "����������� (2 �������)"; break;
				case 27: return "��� �����"; break;
				case 29: return "��� ������"; break;
				default: return "��������� � ����� ($code)";
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
				else {return "����������� ������������� ($code)";}
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
				else {return "�������������������� ($code)";}
			}
		default: return "���-�� � ����� ($code)";
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
			    $description = "����� �� ����� �".$id_num.".";
			    break;
			case "month":
		    	$accno_list = $accno[$id_num];
		    	$description = "����� �� ".$month[$id_num-1]." 2009 ����.";
			    break;
			case "quartal":
			    for($m=1;$m<=3;$m++)
			    	foreach ($accno[($id_num - 1)*3 + $m] as $a)
			    		$accno_list[] = $a;
				//print_r($accno_list);exit;
		    	$description = "����� �� ".$id_num." ������� 2009 ����.";
			    break;
			case "year":
			    for($m=1;$m<=12;$m++)
			    	foreach ($accno[$m] as $a)
			    		$accno_list[] = $a;
		    	$description = "����� �� 2009 ���.";
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
        	$c.="����������� �����</h4></center>";
        	$c.= $table_header;
        	$c.="<tr><td>���</td><td>���������</td><td>�������������</td><td>����</td>"
			."<td>����������</td><td>�����</td><td colspan=\"2\">��� ���������</td>"
			."<td>���-�� �������</td><td>���-�� �������</td><td>�����</td></tr>";
			break;
		}
		case "consolidated":
		{        	$fields=array("MU","SECTN");
        	$c.="������� �����</h4></center>";
        	$c.= $table_header;
        	$c.="<tr><td>���</td><td>���������</td><td>����������</td><td>�����</td>"
			."<td colspan=\"2\">��� ���������</td>"
			."<td>���-�� �������</td><td>���-�� �������</td><td>�����</td></tr>";
			break;
		}
		case "only_codes":
		{        	$fields=array("MU");
        	$c.="�� ����� ���������</h4></center>";
        	$c.= $table_header;
        	$c.="<tr><td>���</td><td>����������</td><td>�����</td>"
			."<td colspan=\"2\">��� ���������</td>"
			."<td>���-�� �������</td><td>���-�� �������</td><td>�����</td></tr>";
			break;
		}
		default: header("Location: http://".$page->base_address);
	}
    $col_count=count($fields)+6;

/*	if($expanded){$fields=array("MU","SECTN","ASPEC","AGENT");} // ������ �����
	else {$fields=array("MU","SECTN");} //������� �����
	$col_count=count($fields)+6; */

/*	if($expanded){$c.="����������� �����</h4></center>";} // ������ �����
	else {$c.="������� �����</h4></center>";} //������� �����      */

//	$c.= $table_header;
/*	if($expanded)
		{$c.="<tr><td>���</td><td>���������</td><td>�������������</td><td>����</td>"
			."<td>����������</td><td>�����</td><td colspan=\"2\">��� ���������</td>"
			."<td>���-�� �������</td><td>���-�� �������</td><td>�����</td></tr>";}
	else
		{$c.="<tr><td>���</td><td>���������</td><td>����������</td><td>�����</td>"
			."<td colspan=\"2\">��� ���������</td>"
			."<td>���-�� �������</td><td>���-�� �������</td><td>�����</td></tr>";}   */

	$sql="CREATE TEMPORARY TABLE B_AMBSER SELECT * FROM B_AMBSER WHERE TAPID IN (SELECT TAPID FROM B_AMBREE WHERE ACCNO $where_accno)";
//	echo $sql."<br>"; //debug
	$result=mysql_query($sql);
	if(!$result)
	 {echo("<br>".mysql_error());} //����� ���� ������ ����� ��������� ����� �� ������
	else
	 {EnumValues("WHERE 1=1",-1);}

	 include($page->tpls_path."report.tpl");
	 echo $c."</table></div></body></html>";
}
else {header("Location: http://".$page->base_address);}
?>