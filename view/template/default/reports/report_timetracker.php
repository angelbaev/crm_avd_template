		<script type="text/javascript">
			$(function() {

				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$('input[name=\'report_from_date\']').datepicker();
				$('input[name=\'report_to_date\']').datepicker();
			});	
			
			function report_partner() {
				var url = '<?php echo $action;?>'
	  		if ($('input[name=\'report_from_date\']').val() != '') {
	  			url += '&report_from_date=' + encodeURIComponent($('input[name=\'report_from_date\']').val());
				}
	  		if ($('input[name=\'report_to_date\']').val() != '') {
	  			url += '&report_to_date=' + encodeURIComponent($('input[name=\'report_to_date\']').val());
				}
	  		if ($('select[name=\'report_user_id\'] option:selected').val() != '*') {
	  			url += '&report_user_id=' + encodeURIComponent($('select[name=\'report_user_id\'] option:selected').val());
				}
	  		if ($('select[name=\'report_work_time\'] option:selected').val() != '*') {
	  			url += '&report_work_time=' + encodeURIComponent($('select[name=\'report_work_time\'] option:selected').val());
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
					<td class="label">Потребител</td>
					<td>
						<select name="report_user_id"  class="ie240">
							<option value="*"> - Избери потребител - </option>
							<?php if (count($all_users)) { ?>
							<?php foreach ($all_users as $pid => $item) { ?>
							<option value="<?php echo $pid; ?>" <?php echo ($pid == $report_filter["report_user_id"]? " selected":""); ?>> <?php echo $item["UName"];?> </option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td class="label">Работно време</td>
					<td>
						<select name="report_work_time"  class="ie120">
							<option value="*"> - Избери - </option>
							<option value="30600" <?php echo ($report_filter["report_work_time"] == 30600? "selected":"");?> > 08,30 </option>
							<option value="32400" <?php echo ($report_filter["report_work_time"] == 32400? "selected":"");?> > 09,00 </option>
						</select>
					
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
	  		if ($('select[name=\'report_user_id\'] option:selected').val() != '*') {
					$('select[name=\'report_user_id\']').css({'border':'1px red solid'});
				}
	  		if ($('select[name=\'report_work_time\'] option:selected').val() != '*') {
					$('select[name=\'report_work_time\']').css({'border':'1px red solid'});
				}
		});
	</script>	

<p>&nbsp;</p>	
<table class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="180"><!--Потребител-->
		<col width="120"><!--Дата-->
		<col width="120"><!--Начало-->
		<col width="120"><!--Край-->
		<col width="80"><!--Общо-->
	</colgroup>
	<tr>	
		<th style="padding-top:6px;padding-bottom:6px;">Потребител</th>
		<th>Дата</th>
		<th>Начало</th>
		<th>Край</th>
		<th>Общо</th>
	</tr>
	<?php if (count($report_timetracker)) {?>
	<?php
	 $SUM_DURATION = 0;
	 $SUM_FILTER_TOTAL = ($report_filter["report_work_time"] * count($report_timetracker));
	?>
	<?php foreach ($report_timetracker as $key => $item) {?>
	<tr>	
		<td><?php echo $item['fullName']; ?></td>
		<td><?php echo $item['key_yyyy_mm_dd']; ?></td>
		<td><?php echo $item['start_time']; ?></td>
		<td><?php echo $item['end_time']; ?></td>
		<td><?php echo $item['w_time_hh']; ?></td>
	</tr>
	<?php
	 $SUM_DURATION += $item['w_time_U'];
	?>
	<?php }?>
	<?php
	$bg_color = "";
	if ($SUM_FILTER_TOTAL != 0) {
  	if ($SUM_FILTER_TOTAL == $SUM_DURATION) {
  	 $bg_color = "green";
    } 
  	if ($SUM_FILTER_TOTAL < $SUM_DURATION) {
  	 $bg_color = "blue";
    } 
  	if ($SUM_FILTER_TOTAL > $SUM_DURATION) {
  	 $bg_color = "red";
    } 
  }
	?>
	<tr>	
		<td></td>
		<td><b>Общо ДНИ</b></td>
		<td style="text-align: right;"><?php echo count($report_timetracker); ?></td>
		<td><b>Общо</b></td>
		<td style="background: <?php echo $bg_color;?>;"><?php echo time_from_seconds($SUM_DURATION); ?></td>
	</tr>
	<?php if ($SUM_FILTER_TOTAL != 0) { ?>
	<?php
	if ($SUM_FILTER_TOTAL == $SUM_DURATION) {
	?>
	<tr>	
		<td colspan="3"></td>
		<td><b>Разлика</b></td>
		<td style="background: green;">00:00:00</td>
	</tr>
	
	<?php
  } 
	if ($SUM_FILTER_TOTAL < $SUM_DURATION) {
	?>
	<tr>	
		<td colspan="3"></td>
		<td><b>Разлика</b></td>
		<td style="background: blue;">+ <?php echo time_from_seconds(($SUM_DURATION-$SUM_FILTER_TOTAL)); ?></td>
	</tr>
	<?php
  } 
	if ($SUM_FILTER_TOTAL > $SUM_DURATION) {
	?>
	<tr>	
		<td colspan="3"></td>
		<td><b>Разлика</b></td>
		<td style="background: red;">- <?php echo time_from_seconds(($SUM_FILTER_TOTAL-$SUM_DURATION)); ?></td>
	</tr>
	<?php
  } 
	?>
	<?php } ?>
	
	<?php }?>
</table>
