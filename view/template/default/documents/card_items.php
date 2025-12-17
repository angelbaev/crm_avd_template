<?php
$dt_js = array();
$i=0;
if (count($document["doc_cards"])) {
	for(;$i < count($document["doc_cards"]); $i++){
		$dt_js[] = "$('#c_dt_".$i."').datepicker();";
	}
}
$i=0;
if (count($document["doc_suppliers"])) {
	for(; $i < count($document["doc_suppliers"]); $i++){
		$dt_js[] = "$('#s_dt_".$i."').datepicker();";
		$dt_js[] = "$('#r_dt_".$i."').datepicker();";
	}
}

echo "
		<script type=\"text/javascript\">
			\$(function() {
			".implode("\n", $dt_js)."
			});		
		</script>
";
?>

<?php 
if (count($document["doc_cards"])) {
	foreach ($document["doc_cards"] as $id => $item) {

?>		
				<tr id="c_title_row_<?php echo $id; ?>" class="t-card">
					<td colspan=4 class="label"><span class="t-cart_title">Дейност</span></td>
					<td style="text-align: right;"><a style="float: right;" class="remove" href="javascript: void(0);" onclick="removeCardItem('<?php echo $id; ?>', '<?php echo $item["rec_hash"]; ?>')"></a></td>
				</tr>	
				<tr id="c_act_row_<?php echo $id; ?>" class="t-card">
					<td class="label">Потребител</td>
					<td>
					<?php echo userSelect("card[".$id."][uid]", "", $item["uid"]); ?>
					</td>
					<td colspan=2 rowspan=2 valign="bottom">
					<input type="hidden" name="card[<?php echo $id; ?>][card_status]" value="<?php echo $item["card_status"]; ?>">
					<input type="hidden" name="card[<?php echo $id; ?>][rec_hash]" value="<?php echo $item["rec_hash"]; ?>">
					
					<textarea name="card[<?php echo $id; ?>][card_notes]" rows="2" cols="45"><?php echo trim($item["card_notes"]); ?></textarea>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr id="c_dt_row_<?php echo $id; ?>" class="t-card">
					<td class="label">За Дата</td>
					<td><input type="text" name="card[<?php echo $id; ?>][card_date]" id="c_dt_<?php echo $id; ?>" class="ie120" value="<?php echo $item["card_date"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr id="c_save_row_<?php echo $id; ?>" class="t-card">
					<td colspan="5" style="text-align: right;"><input type="button" onclick="saveCardItem('<?php echo $id; ?>', '<?php echo $item["rec_hash"]; ?>');" class="submit_button button_add" value="Запис" name="add_position"></td>
				</tr>
<?php

	} 
}	

?>	
				<tr id="hook_card" style="display:none;">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr class="line t-card">
					<td colspan=5>&nbsp;</td>
				</tr>
<?php 

if (count($document["doc_suppliers"])) {
	foreach ($document["doc_suppliers"] as $id => $item) {

?>		
				<tr id="s_title_row_<?php echo $id; ?>" class="t-card">
					<td colspan=4 class="label"><span class="t-cart_title">Контрагент</span></td>
					<td style="text-align: right;"><a style="float: right;" class="remove" href="javascript: void(0);" onclick="removeSupplierItem('<?php echo $id; ?>', '<?php echo $item["rec_hash"]; ?>');"></a></td>
				</tr>	
				<tr id="s_act_row_<?php echo $id; ?>" class="t-card">
					<td class="label">Контрагент</td>
					<td>
					<?php echo supplierSelect("supplier[".$id."][c_id]", "", $item["c_id"]); ?>
					</td>
					<td colspan=2 rowspan=2 valign="bottom">
					
					<textarea name="supplier[<?php echo $id; ?>][CNotes]" rows="2" cols="45"><?php echo trim($item["CNotes"]); ?></textarea>
					<input type="hidden" name="supplier[<?php echo $id; ?>][CStatus]" value="<?php echo $item["CStatus"]; ?>">
					<input type="hidden" name="supplier[<?php echo $id; ?>][rec_hash]" value="<?php echo $item["rec_hash"]; ?>">
					
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr id="s_dt_row_<?php echo $id; ?>" class="t-card">
					<td class="label">За Дата</td>
					<td><input type="text" name="supplier[<?php echo $id; ?>][CDate]" id="s_dt_<?php echo $id; ?>" class="ie120" value="<?php echo $item["CDate"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr id="s_sum_row_<?php echo $id; ?>" class="t-card">
					<td class="label">Отговорен</td>
					<td>
					<?php echo userSelect("supplier[".$id."][uid]", "", $item["uid"]); ?>
					</td>
					<td  class="label">Сума</td>
					<td><input type="text" name="supplier[<?php echo $id; ?>][CSum]" class="ie120" value="<?php echo $item["CSum"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr id="s_case_row_<?php echo $id; ?>" class="t-card">
					<td class="label">За дата</td>
					<td><input type="text" name="supplier[<?php echo $id; ?>][date_requested]" id="r_dt_<?php echo $id; ?>" class="ie120" value="<?php echo $item["date_requested"]; ?>"></td>
					<td  class="label">Заявена сума</td>
					<td><input type="text" name="supplier[<?php echo $id; ?>][amount_requested]" class="ie120" value="<?php echo $item["amount_requested"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr id="s_save_row_<?php echo $id; ?>" class="t-card">
					<td colspan="5" style="text-align: right;"><input type="button" onclick="saveSupplierItem('<?php echo $id; ?>', '<?php echo $item["rec_hash"]; ?>');" class="submit_button button_add" value="Запис" name="add_position"></td>
				</tr>

<?php

	} 
}	

?>	
				<tr id="hook_supplier" style="display:none;">
					<td colspan=5>&nbsp;</td>
				</tr>

				<tr class="line t-card">
					<td colspan=5>&nbsp;</td>
				</tr>
				
				
				<tr>
					<td colspan="5">
					<!--
					<input type="button" name="add_position" value="Добави дейност" class="submit_button button_add" onclick="addCardItem();">
					-->
					<input type="button" name="add_position" value="Добави контрагент" class="submit_button button_add" onclick="addSupplierItem();">
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
<!--  id="for_ci" -->
<?php
$startCIndex = (count($document["doc_cards"])? count($document["doc_cards"]):0);
$startSIndex = (count($document["doc_suppliers"])? count($document["doc_suppliers"]):0);
?>
<script type="text/javascript">
var startCIndex = parseInt('<?php echo $startCIndex; ?>');
var startSIndex = parseInt('<?php echo $startSIndex; ?>');

function addCardItem() {
	html = ''+
		'<tr id="c_title_row_'+startCIndex+'" class="t-card">'+
		'	<td colspan=4 class="label"><span class="t-cart_title">Дейност</span></td>'+
		'	<td style="text-align: right;"><a style="float: right;" class="remove" href="javascript: void(0);" onclick="removeCardItem(\''+startCIndex+'\', \'\')"></a></td>'+
		'</tr>'+
		'<tr id="c_act_row_'+startCIndex+'" class="t-card">'+
		'	<td class="label">Дейност</td>'+
		'	<td><?php echo userSelect("card['+startCIndex+'][uid]", "", ""); ?></td>'+
		'	<td colspan=2 rowspan=2 valign="bottom">'+
		'		<textarea name="card['+startCIndex+'][card_notes]" rows="2" cols="45"></textarea>'+
		'		<input type="hidden" name="card['+startCIndex+'][card_status]" value="<?php echo CARD_STATUS_W; ?>">'+
		'		<input type="hidden" name="card['+startCIndex+'][rec_hash]" value="">'+
		'	</td>'+
		'	<td>&nbsp;</td>'+
		'	</tr>'+
		'	<tr id="c_dt_row_'+startCIndex+'" class="t-card">'+
		'		<td class="label">За Дата</td>'+
		'		<td><input type="text" name="card['+startCIndex+'][card_date]" id="c_dt_'+startCIndex+'" class="ie120" value=""></td>'+
		'		<td>&nbsp;</td>'+
		'	</tr>'+
		'	<tr id="c_save_row_'+startCIndex+'" class="t-card">'+
		'	  <td colspan="5" style="text-align: right;"><input type="button" onclick="saveCardItem(\''+startCIndex+'\', \'\');" class="submit_button button_add" value="Запис" name="add_position"></td>'+
		'	</tr>';
		
		_startCIndex = startCIndex;
		startCIndex++;					
		$('#hook_card').after(html);
		$('#c_dt_'+_startCIndex).datepicker();
}

function removeCardItem(tr_id, md5Hash) {
	if (confirm ('Изтриване на Технологична карта !')) {
	  if (md5Hash != '')  {
    	$.ajax({
    		url: '<?php echo HTTP_SERVER."ajax/doc-ajax.php";?>',
    		type: 'post',
    		data: 'id=' + md5Hash + '&act=remove_card_item',
    		dataType: 'json',
    		success: function(json) {
      		$('#c_title_row_'+tr_id+'').remove();
      		$('#c_act_row_'+tr_id+'').remove();
      		$('#c_dt_row_'+tr_id+'').remove();
    		}
    	});
    } else {
  		$('#c_title_row_'+tr_id+'').remove();
  		$('#c_act_row_'+tr_id+'').remove();
  		$('#c_dt_row_'+tr_id+'').remove();
    }	
		
	}
}

function saveCardItem(tr_id, md5Hash) {
    $('input[name=\'card['+tr_id+'][card_date]\']').removeClass('err');
    if ($('input[name=\'card['+tr_id+'][card_date]\']').val() == '') {
       $('input[name=\'card['+tr_id+'][card_date]\']').addClass('err');
       alert('Не е попълнена дата!');

       return;
    }    
    
    if (confirm ('Запазване на Технологична карта !')) {
      var rec = {
        'uid': $('select[name=\'card['+tr_id+'][uid]\']').val(),
        'card_notes': $('textarea[name=\'card['+tr_id+'][card_notes]\']').val(),
        'card_status': $('input[name=\'card['+tr_id+'][card_status]\']').val(),
        'rec_hash': $('input[name=\'card['+tr_id+'][rec_hash]\']').val(),
        'card_date': $('input[name=\'card['+tr_id+'][card_date]\']').val()
        };

        $.ajax({
                url: '<?php echo HTTP_SERVER."ajax/doc-ajax.php";?>',
                type: 'post',
                data: 'id=' + md5Hash + '&act=save_card_item&uid='+rec['uid']+'&card_notes='+rec['card_notes']+'&card_status='+rec['card_status']+'&rec_hash='+rec['rec_hash']+'&card_date='+rec['card_date']+'&doc_id=<?php echo $_GET["doc_id"];?>',
                dataType: 'json',
                success: function(json) {
                  alert('Технологична карта е записана успешно!');
                }
        });
    }
}

function addSupplierItem () {
	html = ''+
		'<tr id="s_title_row_'+startSIndex+'" class="t-card">'+
		'	<td colspan=4 class="label"><span class="t-cart_title">Контрагент</span></td>'+
		'	<td style="text-align: right;"><a style="float: right;" class="remove" href="javascript: void(0);" onclick="removeSupplierItem(\''+startSIndex+'\', \'\')"></a></td>'+
		'</tr>'+
		'<tr id="s_act_row_'+startSIndex+'" class="t-card">'+
		'	<td class="label">Контрагент</td>'+
		'	<td><?php echo supplierSelect("supplier['+startSIndex+'][c_id]", "", ""); ?></td>'+
		'	<td colspan=2 rowspan=2 valign="bottom">'+
		'		<textarea name="supplier['+startSIndex+'][CNotes]" rows="2" cols="45"></textarea>'+
		'		<input type="hidden" name="supplier['+startSIndex+'][CStatus]" value="<?php echo CARD_STATUS_W; ?>">'+
		'		<input type="hidden" name="supplier['+startSIndex+'][rec_hash]" value="">'+
		'	</td>'+
		'	<td>&nbsp;</td>'+
		'	</tr>'+
		'	<tr id="s_dt_row_'+startSIndex+'" class="t-card">'+
		'		<td class="label">За Дата</td>'+
		'		<td><input type="text" name="supplier['+startSIndex+'][CDate]" id="s_dt_'+startSIndex+'" class="ie120" value=""></td>'+
		'		<td>&nbsp;</td>'+
		'	</tr>'+
		'	<tr id="s_sum_row_'+startSIndex+'" class="t-card">'+
		'		<td class="label">Отговорен</td>'+
		'		<td><?php echo userSelect("supplier['+startSIndex+'][uid]", "", ""); ?></td>'+
		'		<td  class="label">Сума</td>'+
		'		<td><input type="text" name="supplier['+startSIndex+'][CSum]" class="ie120" value=""></td>'+
		'		<td>&nbsp;</td>'+
		'	</tr>'+
		'	<tr id="s_case_row_'+startSIndex+'" class="t-card">'+
		'		<td class="label">За Дата</td>'+
		'		<td><input type="text" name="supplier['+startSIndex+'][date_requested]" id="r_dt_'+startSIndex+'" class="ie120" value=""></td>'+
		'		<td  class="label">Заявена сума</td>'+
		'		<td><input type="text" name="supplier['+startSIndex+'][amount_requested]" class="ie120" value=""></td>'+
		'		<td>&nbsp;</td>'+
		'	</tr>'+
		'	<tr id="s_save_row_'+startSIndex+'" class="t-card">'+
		'	  <td colspan="5" style="text-align: right;"><input type="button" onclick="saveSupplierItem(\''+startSIndex+'\', \'\');" class="submit_button button_add" value="Запис" name="add_position"></td>'+
		'	</tr>';
		
		_startSIndex = startSIndex;
		startSIndex++;					
		$('#hook_supplier').after(html);
		$('#s_dt_'+_startSIndex).datepicker();
		$('#r_dt_'+_startSIndex).datepicker();
}

function removeSupplierItem(tr_id, md5Hash) {
	if (confirm ('Изтриване на Контрагента!')) {
	  if (md5Hash != '')  {
    	$.ajax({
    		url: '<?php echo HTTP_SERVER."ajax/doc-ajax.php";?>',
    		type: 'post',
    		data: 'id=' + md5Hash + '&act=remove_supplier_item',
    		dataType: 'json',
    		success: function(json) {
      		$('#s_title_row_'+tr_id+'').remove();
      		$('#s_act_row_'+tr_id+'').remove();
      		$('#s_dt_row_'+tr_id+'').remove();
      		$('#s_sum_row_'+tr_id+'').remove();
    		}
    	});
    } else {
  		$('#s_title_row_'+tr_id+'').remove();
  		$('#s_act_row_'+tr_id+'').remove();
  		$('#s_dt_row_'+tr_id+'').remove();
  		$('#s_sum_row_'+tr_id+'').remove();
		}
	}
} 

function saveSupplierItem(tr_id, md5Hash) {
    $('input[name=\'supplier['+tr_id+'][CDate]\']').removeClass('err');
    if ($('input[name=\'supplier['+tr_id+'][CDate]\']').val() == '') {
        $('input[name=\'supplier['+tr_id+'][CDate]\']').addClass('err');
        alert('Не е попълнена дата!');
        
        return ;
    }
  
	if (confirm ('Запазване на Контрагента !')) {
	  var rec = {
            'c_id': $('select[name=\'supplier['+tr_id+'][c_id]\']').val(),
            'CNotes': $('textarea[name=\'supplier['+tr_id+'][CNotes]\']').val(),
            'CStatus': $('input[name=\'supplier['+tr_id+'][CStatus]\']').val(),
            'rec_hash': $('input[name=\'supplier['+tr_id+'][rec_hash]\']').val(),
            'CDate': $('input[name=\'supplier['+tr_id+'][CDate]\']').val(),
            'uid': $('select[name=\'supplier['+tr_id+'][uid]\']').val(),
            'CSum': $('input[name=\'supplier['+tr_id+'][CSum]\']').val(),
            'date_requested': $('input[name=\'supplier['+tr_id+'][date_requested]\']').val(),
            'amount_requested': $('input[name=\'supplier['+tr_id+'][amount_requested]\']').val(),
            };

            $.ajax({
                    url: '<?php echo HTTP_SERVER."ajax/doc-ajax.php";?>',
                    type: 'post',
                    data: 'id=' + md5Hash + '&act=save_supplier_item&c_id='+rec['c_id']+'&CNotes='+rec['CNotes']+'&CStatus='+rec['CStatus']+'&rec_hash='+rec['rec_hash']+'&CDate='+rec['CDate']+'&uid='+rec['uid']+'&CSum='+rec['CSum']+'&date_requested='+rec['date_requested']+'&amount_requested='+rec['amount_requested']+'&doc_id=<?php echo $_GET["doc_id"];?>',
                    dataType: 'json',
                    success: function(json) {
                      alert('Контрагента е записан успешно!');
                    }
            });

	}
}

</script>