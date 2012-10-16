<h5 align="center">Счет №<?php echo $this->uploaded_accno ?> уже загружен.
Хотите ли вы удалить старые данные и загрузить счет заново?</h5>
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
		<button onclick="location.href='delete.php'">Обновить записи со
		счетом <?php echo $this->uploaded_accno ?></button>
    </td>
    <td align="center">
	  <button onclick="location.href='cancel.php'" >Отменить операцию</button>
	</td>
  </tr>
</table>