		<script type="text/javascript">
			$(function() {

				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$('input[name=\'report_from_date\']').datepicker();
				$('input[name=\'report_to_date\']').datepicker();
			});	
			
			function report_partner() {
				var url = '<?php echo $action;?>'
	  		if ($('input[name=\'report_total\']').val() != '') {
	  			url += '&report_total=' + encodeURIComponent($('input[name=\'report_total\']').val());
				}
	  		if ($('input[name=\'report_from_date\']').val() != '') {
	  			url += '&report_from_date=' + encodeURIComponent($('input[name=\'report_from_date\']').val());
				}
	  		if ($('input[name=\'report_to_date\']').val() != '') {
	  			url += '&report_to_date=' + encodeURIComponent($('input[name=\'report_to_date\']').val());
				}
	  		if ($('select[name=\'report_supplier_id\'] option:selected').val() != '*') {
	  			url += '&report_supplier_id=' + encodeURIComponent($('select[name=\'report_supplier_id\'] option:selected').val());
				}
				/*
	  		if ($('select[name=\'report_order_type\'] option:selected').val() != '*') {
	  			url += '&report_order_type=' + encodeURIComponent($('select[name=\'report_order_type\'] option:selected').val());
				}
				*/
				window.location=url;			
			}	
		</script>


<form action="<?php echo $action;?>" name="fEdit" method="post">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="120">
					<col  width="280">
					<col width="120">
					<col  width="280">
					<col>
				</colgroup>
				<tr>
					<td class="label">Контрагенти</td>
					<td>
						<select name="report_supplier_id"  class="ie240">
							<option value="*"> - Избери контрагент - </option>
							<?php if (count($all_suppliers)) { ?>
							<?php foreach ($all_suppliers as $sid => $item) { ?>
							<option value="<?php echo $sid; ?>" <?php echo ($sid == $report_filter["report_supplier_id"]? " selected":""); ?>> <?php echo $item["CName"];?> </option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td class="label">Тотал</td>
					<td><input type="text" name="report_total" class="ie120" value="<?php echo $report_filter["report_total"];?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Начална дата</td>
					<td><input type="text" name="report_from_date" class="ie120" value="<?php echo $report_filter["report_from_date"];?>"></td>
					<td class="label">крайна дата</td>
					<td><input type="text" name="report_to_date" class="ie120" value="<?php echo $report_filter["report_to_date"];?>"></td>
					<td>&nbsp;</td>
				</tr>
				<!--
				<tr>
					<td class="label">Тип</td>
					<td>
					<select name="report_order_type" class="ie120" style="width:130px;">
						<option value ="*"> - Всички - </option>
							<?php if (count($order_types)) { ?>
							<?php foreach ($order_types as $id => $item) { ?>
							<option value="<?php echo $id; ?>" <?php echo ($id == $report_filter["report_order_type"]? " selected":""); ?>> <?php echo $item;?> </option>
							<?php } ?>
							<?php } ?>
					</select>					
					</td>
					<td colspan="3">&nbsp;</td>
				</tr>
				-->
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td colspan="4" align="right">
						<input type="button" name="button_save" value="Покажи" class="submit_button button_add" onclick="report_partner();">
						<input type="button" name="button_save" value="X" class="submit_button button_delete" onclick="window.location='<?php echo $action;?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
		</table>

</form>
  <script type="text/javascript">
  	$(document).ready(function(){
  	/*
	  		if ($('input[name=\'report_total\']').val() != '') {
					$('input[name=\'report_total\']').css({'border':'1px red solid'});
				}
				*/
	  		if ($('input[name=\'report_from_date\']').val() != '') {
					$('input[name=\'report_from_date\']').css({'border':'1px red solid'});
				}
	  		if ($('input[name=\'report_to_date\']').val() != '') {
					$('input[name=\'report_to_date\']').css({'border':'1px red solid'});
				}
	  		if ($('select[name=\'report_supplier_id\'] option:selected').val() != '*') {
					$('select[name=\'report_supplier_id\']').css({'border':'1px red solid'});
				}
				/*
	  		if ($('select[name=\'report_order_type\'] option:selected').val() != '*') {
					$('select[name=\'report_order_type\']').css({'border':'1px red solid'});
				}
				*/
		});
	</script>	

<p>&nbsp;</p>	
<table class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="180"><!--контрагент-->
		<col width="180"><!--Клиент-->
		<col width="110"><!--Дата-->
		<col width="120"><!--документ-->
		<col width="80"><!--Статус-->
		<!--<col width="220"><!--дейност-->
		<col width="80"><!--Сума-->
		<col width="180"><!--Акаунт-->
	</colgroup>
	<tr>	
		<th style="padding-top:6px;padding-bottom:6px;">Контрагент</th>
		<th>Клиент</th>
		<th>Дата</th>
		<th>№ на документ</th>
		<th>Статус</th>
		<!--
		<th>дейност</th>
		-->
		<th>Сума</th>
		<th>Акаунт</th>
	</tr>
	<?php if (count($report_supplier)) {?>
	<?php foreach ($report_supplier as $key => $item) {?>
	<tr>	
		<td style="padding-top:6px;padding-bottom:6px;"><?php echo _fnes($item["CName"]);?></td>
		<td><?php echo _fnes($item["PName"]);?></td>
		<td align="center"><?php echo _fnes($item["CDate"]);?></td>
		<td><?php echo _fnes($item["DocNum"]);?></td>
		<td align="center"><?php echo _fnes($GLOBALS["ORDER_STATUS"][$item["DStatus"]]);?></td>
		<!--
		<th>дейност</th>
		-->
		<td align="right"><?php echo _fnes($item["CSum"]);?></td>
		<td><?php echo _fnes($item["fullName"]);?></td>
	</tr>
	
	<?php }?>
	<?php }?>
</table>
