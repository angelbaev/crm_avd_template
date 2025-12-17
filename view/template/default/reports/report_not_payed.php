		<script type="text/javascript">
			$(function() {

				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$('input[name=\'report_from_date\']').datepicker();
				$('input[name=\'report_to_date\']').datepicker();
			});	
			
			function report_not_payed() {
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
					<td class="label">Тотал</td>
					<td><input type="text" name="report_total" class="ie120" value="<?php echo $report_filter["report_total"];?>"></td>
					
					<td class="label">Неплатени</td>
					<td>
					<input type="text" name="report_total_not_payed" class="ie120" value="<?php echo $report_filter["report_total_not_payed"];?>">
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Начална дата</td>
					<td><input type="text" name="report_from_date" class="ie120" value="<?php echo $report_filter["report_from_date"];?>"></td>
					<td class="label">крайна дата</td>
					<td><input type="text" name="report_to_date" class="ie120" value="<?php echo $report_filter["report_to_date"];?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td colspan="4" align="right">
						<input type="button" name="button_save" value="Покажи" class="submit_button button_add" onclick="report_not_payed();">
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
  	
	  		if ($('input[name=\'report_total\']').val() != '0.00') {
					$('input[name=\'report_total\']').css({'border':'1px red solid'});
				}
				
	  		if ($('input[name=\'report_from_date\']').val() != '') {
					$('input[name=\'report_from_date\']').css({'border':'1px red solid'});
				}
	  		if ($('input[name=\'report_to_date\']').val() != '') {
					$('input[name=\'report_to_date\']').css({'border':'1px red solid'});
				}
		});
	</script>	

<p>&nbsp;</p>	
<table class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="100"><!--Тип-->
		<col width="80"><!--Номер-->
		<col width="180"><!--Клиент-->
		<col width="80"><!--Аванс-->
		<col width="80"><!--Доплащане-->
		<col width="80"><!--Тотал-->
		<col width="160"><!--Обслужва-->
		<col width="100"><!--Обслужва-->
	</colgroup>
	<tr>	
		<th style="padding-top:6px;padding-bottom:6px;">Тип</th>
		<th>Номер</th>
		<th>Клиент</th>
		<th>Аванс</th>
		<th>Доплащане</th>
		<th>Тотал</th>
		<th>Обслужва</th>
		<th>&nbsp;</th>
	</tr>
	<?php if (count($report_not_payed)) {?>
	<?php foreach ($report_not_payed as $key => $item) {?>
  <?php
	$doc_class = "";
	switch($item["DStatus"]) {
		case DOC_ORDER_STATUS_E:
			$doc_class = "mark-red";
			break;
		case DOC_ORDER_STATUS_A:
			$doc_class = "mark-green";
			break;
		case DOC_ORDER_STATUS_R:
			$doc_class = "mark-blue";
			break;
		case DOC_ORDER_STATUS_N:
			default:
			$doc_class = "mark-gray";
			break;
	}
	
	$pay1_class = ($item["doc_payed"]?"mark-green":"mark-red");
	$pay2_class = ($item["doc_payed2"]?"mark-green":"mark-red");
  
  ?>	
	<tr>	
		<td style="padding-top:6px;padding-bottom:6px;"><?php echo _fnes($GLOBALS["DOC_TYPES"][$item["DType"]]); ?></td>
		<td class="<?php echo $doc_class; ?>" align="center"><?php echo _fnes($item["DocNum"]); ?></td>
		<td><?php echo _fnes($item["PName"]); ?></td>
		<td class="<?php echo $pay1_class; ?>" align="right" style="padding-right:6px;"><?php echo _fnes(bg_money($item["DAdvance"])); ?></td>
		<td class="<?php echo $pay2_class; ?>" align="right"><?php echo _fnes(bg_money($item["DSurcharge"])); ?></td>
	  <td align="right"><?php echo _fnes(bg_money(($item["DAdvance"]+$item["DSurcharge"]))); ?></td>
		<!--	<td align="right"><?php echo _fnes($GLOBALS["ORDER_PAYMENT"][$item["DPayment"]]);?></td>-->
		<td ><?php echo _fnes($item["member_name"]." ".$item["member_family"]); ?></td>
		<td ><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=edit_doc&token=".get_token();?>&doc_id=<?php echo $item["doc_id"]; ?>" target="_blank">Преглед</a></td>
	</tr>
	
	<?php }?>
	<?php }?>
</table>
