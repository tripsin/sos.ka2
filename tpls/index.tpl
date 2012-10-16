<script language="javascript" type="text/javascript">
function mainReport(id){
   s="http://<?php echo $this->base_address ?>/report.php?id=" + id;
   if($("input#full_report").attr("checked")) s = s + "&full";
   if($("input#consolidated_report").attr("checked")) s = s + "&consolidated";
   if($("input#only_codes_report").attr("checked")) s = s + "&only_codes";
   location.href = s;
}
</script>

    <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><h5>Выбери вид отчета : </h5></td>
        <td align="center">
			<label>
				<input id="full_report" name="repType" type="radio" value="full" />
				Детальный
			</label>
		</td>
        <td align="center">
			<label>
      			<input id="consolidated_report" type="radio" name="repType" value="consolidated" checked="checked" />
      			Сводный
				</label>
		</td>
      </tr>
        <td align="center">
			<label>
      			<input id="only_codes_report" type="radio" name="repType" value="only_codes"  />
      			По кодам посещений
				</label>
		</td>
      </tr>
    </table>
