<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Система обработки статистики :: ТЦРБ</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<base href="<?php echo $this->base_address ?>" />
<link rel="stylesheet" type="text/css" href="css/tcrb.css" />
<link rel="stylesheet" type="text/css" href="css/contentpanel.css" />
<script language="javascript" type="text/javascript" src="js/jquery-1.2.3.pack.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.dimensions.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script language="javascript" type="text/javascript">
/* $(document).ready(
	function () {
		$("div.content_panel").dropShadow();
	}
); */
</script>
</head>
<body lang="ru">
<table align="center" id="logo" width="800px">
  <tr><td>
	<div class="word">система</div>
	<div class="word">обработки</div>
	<div class="word">статистики</div>
	</td>
	<td id="tcrb">Талицкая центральная районная больница<br>
		<font size=1><a href="http://localhost/denwer/">Главная Denver'а</a><br>
		<a href="http://localhost/Tools/phpmyadmin/index.php">phpMyAdmin</a></font>	
	</td>
</tr></table>
<table id="map" width="800px" height="400px" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td width="210" align="left" valign="top"><div class="content_panel">
<h1 class="content_panel_header">Авторизация</h1>
<div class="content_panel_content">
<?php
	if($this->authorized){$this->tpl("authorized.tpl");}
	else {$this->tpl("nonauthorized.tpl");}
?>
</div>
</div>
<div class="content_panel">
<h1 class="content_panel_header">Опции</h1>
<div class="content_panel_content">
  <ul>
    <li><a href="index.php">Главная страница</a></li>
<?php if($this->authorized){ // Пункты меню, доступные после авторизации ?>
    <li><a href="acc_del.php">Удаление счета</a></li>
    <li><a href="spec_list.php">Специальности</a></li>
    <li><a href="doctor_list.php">Мед. персонал</a></li>
    <li><a href="report.php?id=year&consolidated">Сводный накопительный отчет</a></li>
    <li><a href="report.php?id=year&full">Полный накопительный отчет</a></li>
    <li><a href="q_nozology.php">Обращаемость по нозологии</a></li>
<?php } ?>
  </ul>
</div>
</div>

<?php if ($this->authorized) { ?>
<div class="content_panel">
<h1 class="content_panel_header">Загрузка данных</h1>
<div class="content_panel_content" id="upload_div">
<script language="javascript" type="text/javascript">
function showLoading(){
$("#upload_div").innerHtml="<img src='images/loading.gif' width='32' height='32' />";
}
</script>
<form class="left_panel" action="upload.php" method="post"
		enctype="multipart/form-data" name="upload" id="upload">
  <input type="file" name="filename" /><br /><br />
  <input class="submit" name="upload_button" type="submit" value="Загрузить"
  onclick="showLoading()"/>
</form>
<p>
	<textarea id="log" name="textfield" rows="10" /><?php echo $this->log ?></textarea>
</p>
	</div>
</div>
<?php } ?>

</td>
    <td align="center" valign="top"><div class="content_panel" style="width:95%; height:95%">
<h1 class="content_panel_header">Блок вывода</h1>
<div class="content_panel_content">
<?php $this->content_show() ?>
</div>
</div></td>
  </tr>
</table>
</body>
</htm>