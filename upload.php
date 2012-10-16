<?php
require("config.php");
if($page->authorized){
	set_time_limit(0);
	$page->log="";
	$page->clearTempDir();
	$arj_file_name=$page->tempdir.$_FILES["filename"]["name"];
	if(copy($_FILES["filename"]["tmp_name"],$arj_file_name))
	{
		$page->log("Файл ".$_FILES["filename"]["name"]." успешно загружен.");

		// распаковываю архив
		$execstr = $page->unpacker.' e ../'.$page->tempdir.$_FILES["filename"]["name"];
		$currdir=getcwd();
		chdir($page->bindir);
	    exec($execstr,$retarr,$retval);
	 	chdir($currdir);

       // проверяю правильность архива
       if($retval==0)
       {
		    $fcount=explode(" ",trim($retarr[count($retarr)-1]));
		    $fcount=$fcount[0];
		    $page->log("Распаковано $fcount файлов : ");
		    $success=true;
		    for($i=0;$i<$fcount;$i++)
		    {
	              $tmp=explode(" ",trim($retarr[$i+4])); //TODO!! извлечь из $tmp имя таблицы, а в конфиге имена таблиц сделать простыми
	              $f=preg_split("{\\\|/}",$tmp[1]);
	              $f=$f[count($f)-1];
	              if(!in_array($f,$dbf_names)) $success=false;
	              $page->log("- $f");
		    }
	        if($fcount==5 && $success)
	        {
		        $page->log("Распаковка прошла правильно.");
				// Получаю номер счета $check в бд
				$fcheck=@dbase_open($page->tempdir."B_AMBREE.DBF",0);
                if($fcheck)
                {
	                $check=dbase_get_record_with_names($fcheck,1);
					dbase_close($fcheck);

					$ret=mysql_query("SELECT TAPNUM FROM B_AMBREE WHERE ACCNO=".$check["ACCNO"]." LIMIT 1");
					$page->uploaded_accno = $check["ACCNO"];
					if(mysql_num_rows($ret)>0)
					{
						// в бд есть записи с тем же счетом
						$page->log("В БД уже есть счет ".$page->uploaded_accno);
						$page->save();
						header("Location: http://".$page->base_address."/confirm.php");
					}
					else
					{
						// в бд нет таких записей
						$page->log("Загрузка счета №".$page->uploaded_accno);
						$page->save();
						header("Location: http://".$page->base_address."/update.php");
					}

     			}
				else
				{
					$page->log("Не могу открыть B_AMBREE.DBF");
					$page->clearTempDir();
		        }
			}
	        else
	        {
	        	$page->log("Распаковка прошла неправильно.");
	        	$page->log("Нефиг посылать чё попало.");
	        	$page->clearTempDir();
	        }
       }
       else
       {
       	$page->log("Файл - ".$_FILES["filename"]["name"]." неправильный.");
        $page->clearTempDir();
       }
	}
	else
	{
		$page->log("Ошибка загрузки файла");
	}
	$page->save();
}
else {header("Location: http://".$page->base_address);}
?>