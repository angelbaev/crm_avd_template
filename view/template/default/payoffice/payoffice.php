<?php if ($page_tab == "new_paycase" || $page_tab == "edit_paycase") { ?>
		<script type="text/javascript">
			$(function() {
				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$( "#case_date" ).datepicker();
			});		
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
					<td class="label">Тип</td>
					<td>
					
					<input type="radio" id="type_P" name="case[case_type]" value="<?php echo CASE_TYPE_PROFIT; ?>" <?php echo ($payoffice["case_type"] == CASE_TYPE_PROFIT?"checked=\"true\"":"");?>><label class="profit" for="type_P">Приход</label><br>
					<input type="radio" id="type_C" name="case[case_type]" value="<?php echo CASE_TYPE_COST; ?>"  <?php echo ($payoffice["case_type"] == CASE_TYPE_COST?"checked=\"true\"":"");?>><label class="cost" for="type_C">Разход</label><br>
     
					</td>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Дата</td>
					<td><input type="text" id="case_date" name="case[case_date]" class="ie120" value="<?php echo $payoffice["case_date"]; ?>"></td>
					<td class="label">Стойност</td>
					<td><input id="case_sym" type="text" name="case[case_sym]" class="ie120" value="<?php echo $payoffice["case_sym"]; ?>"> лв.</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Основание</td>
					<td colspan="4"><input type="text" name="case[case_title]" class="ie360" style="width:520px;" value="<?php echo $payoffice["case_title"]; ?>"></td>
				</tr>
				<tr>
					<td class="label">Контрагент</td>
					<td colspan="4"><input type="text" name="case[case_notes]" class="ie360" style="width:520px;" value="<?php echo $payoffice["case_notes"]; ?>"></td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="4">
						<input type="button" name="button_save" value="<?php echo $case_id?"Запази":"Добави"; ?>" class="submit_button <?php echo $case_id?"button_edit":"button_add"; ?>" onclick="validate_case('<?php echo $action_save.get_filter(); ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel.get_filter(); ?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=5><?php echo get_record_info("company_case", "case_id", $case_id); ?></td>
				</tr>

		</table>

</form>
<?php } elseif ($page_tab == "payment") { ?>

<?php
  $case_payment_type = array(
    'cash' => 'в брой',
    'bank' => 'банка',
  );
  $case_type = array(
    'office' => 'офис',
    'provider' => 'доставчик',
    'bank' => 'банка',
  );
?>
<p>&nbsp;</p>
		<script type="text/javascript">
			$(function() {
				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$( "#for_date" ).datepicker();
				$( "#date_of_payment" ).datepicker();
				$( "#to_date" ).datepicker();
				$( "#from_date" ).datepicker();
				$( "#cs_date_of_payment" ).datepicker();
			});		
		</script>

	<div class="filter_box">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="1">
		  <tr>
		    <td>Поръчка</td>
				<td>
				<input type="text" name="filter_case_doc_num" class="ie80" value="<?php echo $pflt_filter["filter_case_doc_num"]; ?>">
				</td>
		    <td>Контрагент</td>
				<td>
				<select name="filter_case_partner_id" class="ie80" style="width:105px;">
					<option value="*"> - Всички - </option>
					<?php if (count($all_suppliers)) { ?>
					<?php foreach ($all_suppliers as $sid => $item) { ?>
					<option value="<?php echo $sid; ?>" <?php echo ($sid == $pflt_filter["filter_case_partner_id"]? " selected":""); ?>> <?php echo $item["CName"];?> </option>
					<?php } ?>
					<?php } ?>
        </select>				
				</td>
		    <td>Вид плащане</td>
				<td>
				<select name="filter_case_payment_type" class="ie80" style="width:105px;">
					<option value="*"> - Всички - </option>
					<?php foreach($case_payment_type as $key => $val){?>
					 <option value="<?php echo $key;?>" <?php echo ($key == $pflt_filter["filter_case_payment_type"]? " selected":""); ?>> <?php echo $val;?> </option>
					<?php } ?>
        </select>				
				</td>
		    <td>Дата на плащане</td>
				<td>
				<input type="text" id="date_of_payment" name="filter_case_date_of_payment" class="ie80" value="<?php echo $pflt_filter["filter_case_date_of_payment"]; ?>">
				</td>
		  </tr>
		  <tr>
		    <td>Отговорен</td>
				<td>
				<select name="filter_case_user_id" class="ie80" style="width:105px;">
					<option value="*"> - Всички - </option>
					<?php if (count($all_users)) { ?>
					<?php foreach ($all_users as $pid => $item) { ?>
					<option value="<?php echo $pid; ?>" <?php echo ($pid == $pflt_filter["filter_case_user_id"]? " selected":""); ?>> <?php echo $item["UName"];?> </option>
					<?php } ?>
					<?php } ?>
        </select>				
				</td>
		    <td>Начална дата</td>
				<td>
				<input type="text" id="to_date" name="filter_case_from_date" class="ie80" value="<?php echo $pflt_filter["filter_case_from_date"]; ?>">
				</td>
		    <td>Крайна дата</td>
				<td>
				<input type="text" id="from_date" name="filter_case_to_date" class="ie80" value="<?php echo $pflt_filter["filter_case_to_date"]; ?>">
				</td>
		    <td>Каса</td>
				<td>
				<select name="filter_case_type" class="ie80" style="width:105px;">
					<option value="*"> - Всички - </option>
					<?php foreach($case_type as $key => $val){?>
					 <option value="<?php echo $key;?>" <?php echo ($key == $pflt_filter["filter_case_type"]? " selected":""); ?>> <?php echo $val;?> </option>
					<?php } ?>
        </select>				
				</td>
		  </tr>
		  <tr>
		    <td colspan="6">
        
        </td>
				<td colspan="2" style="text-align: right;">
						<input type="button" name="button_filter" value="Филтър" class="submit_button button_add" onclick="pflt_case_payment_filter();">
						<input type="button" name="button_clear" value="X" class="submit_button button_delete" onclick="pflt_clear_filter('<?php echo $page_tab; ?>');">
				</td>
		  </tr>
		</table>



	</div>

  <script type="text/javascript">
  	$(document).ready(function(){
  		if ($('select[name=\'filter_case_partner_id\']').val() != '*') {
				$('select[name=\'filter_case_partner_id\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_case_payment_type\']').val() != '*') {
				$('select[name=\'filter_case_payment_type\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_case_user_id\']').val() != '*') {
				$('select[name=\'filter_case_user_id\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_case_type\']').val() != '*') {
				$('select[name=\'filter_case_type\']').css({'border':'1px red solid'});
			}
			
  		if ($('input[name=\'filter_case_doc_num\']').val() != '') {
				$('input[name=\'filter_case_doc_num\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_case_from_date\']').val() != '') {
				$('input[name=\'filter_case_from_date\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_case_date_of_payment\']').val() != '') {
				$('input[name=\'filter_case_date_of_payment\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_case_from_date\']').val() != '') {
				$('input[name=\'filter_case_from_date\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_case_to_date\']').val() != '') {
				$('input[name=\'filter_case_to_date\']').css({'border':'1px red solid'});
			}

		});
	</script>	


<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="100">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th>Поръчка</th>
		<th>Контрагент</th>
		<th>Описание</th>
		<th>Заявена сума</th>
		<th>За дата</th>
		<th>Платена сума</th>
		<th>Вид плащане</th>
		<th>Дата на плащане</th>
		<th>Номер на документ</th>
		<th>Каса</th>
		<th>Отговорен</th>
		<th>Действие</th>
	</tr>
	<?php if (count($payoffices)) { ?>
	<?php foreach ($payoffices as $case_id => $item) { ?>
	<tr>	
		<td align="center"><input type="checkbox"></td>
		<td align="center"><?php echo _fnes($item["DocNum"]); ?></td>
		<td><?php echo _fnes($item["CName"]); ?></td>
		<td align="center"><?php echo _fnes($item["case_description"]); ?></td>
		<td align="right"><?php echo _fnes(number_format((float)$item["case_amount_requested"], 2)); ?></td>
		<td align="center"><?php echo _fnes($item["case_date"]); ?></td>
		<td align="right"><?php echo _fnes(number_format((float)$item["case_paid_amount"], 2)); ?></td>
		<td align="center"><?php echo _fnes($case_payment_type[$item["case_payment_type"]]); ?></td>
		<td align="center"><?php echo _fnes($item["case_date_of_payment"]); ?></td>
		<td align="center"><?php echo _fnes($item["case_doc_num"]); ?></td>
		<td align="center"><?php echo _fnes($case_type[$item["case_type"]]); ?></td>
		<td align="center"><?php echo _fnes($item["UName"]); ?></td>
		<td width="80" class="action">
		<table border=0>
		  <tr>
		    <td>
		<a class="sadd" href="javascript: void(0);" onclick="open_popup_case_box('<?php echo $item["case_id"];?>');"></a>
		    </td>
		    <td>
		<a class="sremove" href="javascript: void(0)" onclick="delete_settings('<?php echo $action_delete.$case_id.get_filter(); ?>');"></a>
		    </td>
		  </tr>
		</table>
		</td>
	</tr>
	
	<?php } ?>
	<?php } ?>
</table>
<div id="container">
<?php echo $styles; ?>
<?php echo $pagination; ?>
</div>
<script>
function open_popup_case_box(id) {
    $('#cs_id').val(id);
    $('#cs_order_num').val('');
    $('#cs_partner').val('');
    $('#cs_notes').val('');
    $('#cs_case_amount_requested').val('');
    $('#cs_case_date').val('');
    $('#cs_user').val('');
    $('#case_date_of_payment').val('');
    $('#cs_paid_amount').val('');
    $('#cs_payment_type').val('');
    $('#cs_date_of_payment').val('');
    $('#cs_type').val('');
    $('#cs_doc_num').val('');
   $.ajax({
      url: 'http://avdesigngroup.org/crm_avd/ajax/system-ajax.php',
      type: 'GET',
      dataType: 'json',
      data: {'case_id':id, 'act':'get_case_info'},
      error: function() {
      },
      success: function(data) {
        document.getElementById('light').style.display='block';
        document.getElementById('fade').style.display='block';
        $('.black_overlay').height($(document).height());
        if(data['case_id']){
          $('#cs_order_num').val(data['DocNum']);
          $('#cs_partner').val(data['CName']);
          $('#cs_notes').val(data['case_description']);
          $('#cs_case_amount_requested').val(data['case_amount_requested']);
          $('#cs_case_date').val(data['case_date']);
          $('#cs_user').val(data['UName']);
          $('#case_date_of_payment').val(data['UName']);
          $('#cs_paid_amount').val(data['case_paid_amount']);
          $('#cs_payment_type').val(data['case_payment_type']);
          $('#cs_date_of_payment').val(data['case_date_of_payment']);
          $('#cs_type').val(data['case_type']);
          $('#cs_doc_num').val(data['case_doc_num']);
          
        }
      }
   });
}

function save_new_case() {
   $.ajax({
      url: 'http://avdesigngroup.org/crm_avd/ajax/system-ajax.php',
      type: 'POST',
      dataType: 'json',
      data: {case_id:$('#cs_id').val(), act:'update_case_info', cs_paid_amount:$('#cs_paid_amount').val(), cs_payment_type:$('#cs_payment_type').val(), cs_type:$('#cs_type').val(), cs_date_of_payment: $('#cs_date_of_payment').val(), cs_doc_num: $('#cs_doc_num').val()},
      error: function() {
      },
      success: function(data) {
        $('#cs_id').val('');
        $('#cs_order_num').val('');
        $('#cs_partner').val('');
        $('#cs_notes').val('');
        $('#cs_case_amount_requested').val('');
        $('#cs_case_date').val('');
        $('#cs_user').val('');
        $('#cs_date_of_payment').val('');
        $('#cs_paid_amount').val('');
        $('#cs_payment_type').val('');
        $('#cs_date_of_payment').val('');
        $('#cs_type').val('');
        $('#cs_doc_num').val('');
       location.reload();
      }
   });
}
</script>
<div id="light" class="white_content">
<div class="nav-header-box">
  <a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">Close</a>
</div>
<div>
  <h2>Плащане</h2>
  <input type="hiden" id="cs_id" name="cs_id" class="ie240" value="">
  <table cellspacing="0" cellpadding="2" border="0" width="100%" class="form"> 
    <tr>
      <td class="label">Поръчка</td>
      <td><input type="text" id="cs_order_num" name="" class="ie240" value=""></td>
    </tr>
    <tr>
      <td class="label">Контрагент</td>
      <td>
      <input type="text" id="cs_partner" name="" class="ie240" value="">
      </td>
    </tr>
    <tr>
      <td class="label">Описание</td>
      <td><input type="text" id="cs_notes" name="" class="ie240" value=""></td>
    </tr>
    <tr>
      <td class="label">Заявена сума</td>
      <td><input type="text" id="cs_case_amount_requested" name="" class="ie240" value=""></td>
    </tr>
    <tr>
      <td class="label">За дата</td>
      <td><input type="text" id="cs_case_date" name="" class="ie240" value=""></td>
    </tr>
    <tr>
      <td class="label">Отговорен</td>
      <td>
      <input type="text" id="cs_user" name="" class="ie240" value="">
      </td>
    </tr>
  </table>
  
  <br>
  <hr>
  <br>
  <table cellspacing="0" cellpadding="2" border="0" width="100%" class="form"> 
    <tr>
      <td class="label">Платена сума</td>
      <td><input type="text" id="cs_paid_amount" name="cs_paid_amount" class="ie240" value=""></td>
    </tr>
    <tr>
      <td class="label">Вид плащане</td>
      <td>
				<select id="cs_payment_type" name="cs_payment_type" class="ie240" style="width:105px;">
					<?php foreach($case_payment_type as $key => $val){?>
					 <option value="<?php echo $key;?>" > <?php echo $val;?> </option>
					<?php } ?>
        </select>				
      </td>
    </tr>
    <tr>
      <td class="label">Дата на плащане</td>
      <td><input type="text" id="cs_date_of_payment" name="cs_date_of_payment" class="ie240" value=""></td>
    </tr>
    <tr>
      <td class="label">Номер на документ</td>
      <td><input type="text" id="cs_doc_num" name="cs_doc_num" class="ie240" value=""></td>
    </tr>
    <tr>
      <td class="label">Каса</td>
      <td>
				<select id="cs_type" name="cs_type" class="ie240" style="width:105px;">
					<?php foreach($case_type as $key => $val){?>
					 <option value="<?php echo $key;?>"> <?php echo $val;?> </option>
					<?php } ?>
        </select>				
      </td>
    </tr>
  </table>
  <input type="button" name="button_pay_save" value="Запази" class="submit_button button_add" onclick="save_new_case();">
</div>
</div>
<div id="fade" class="black_overlay"></div>
<style>
.black_overlay{
	display: none;
	position: absolute;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: black;
	z-index:1001;
	-moz-opacity: 0.8;
	opacity:.80;
	filter: alpha(opacity=80);
}

.white_content {
	display: none;
	position: absolute;
	top: 25%;
	left: 25%;
	width: 460px;
	height: 55%;
	padding: 16px;
	border: 3px solid orange;
	background-color: white;
	z-index:1002;
	overflow: auto;
}

.nav-header-box {
  text-align: right;
  margin-top: -4px;
  margin-right: -4px;
}
/*
http://jqueryui.com/autocomplete/
*/
</style>
<?php } elseif ($page_tab == "stock") { ?>
<?php if ($sub_mode == "form") { ?>
<!-- Product stock form begin -->
<?php include_once("product_stock_form.php"); ?>
<!-- Product stock form end -->
<?php } else { ?>
<!-- Product stock Report begin -->
<?php include_once("report_product_stock.php"); ?>
<!-- Product stock Report end -->
<?php } ?>
<?php } else { ?>
<?php
$case_total["PROFIT"] = bg_money($case_total["PROFIT"], 2);
$case_total["COST"] = bg_money($case_total["COST"], 2);

$types = array(
	CASE_TYPE_PROFIT => "Приход"
	, CASE_TYPE_COST => "Разход"
);
?>
<div class="cbox_wrapp">
<!--
<div class="profit_box">
	<div class="title">Приход</div>
	<div class="sum"><?php echo $case_total["PROFIT"]; ?></div>
</div>
<div class="cost_box">
	<div class="title">Разход</div>
	<div class="sum">-<?php echo $case_total["COST"]; ?></div>
</div>
-->
<div class="balance_box">
	<div class="title">Текущо салдо</div>
	<div class="sum">
	<?php echo bg_money((float) ($case_total["PROFIT"]-$case_total["COST"]), 2); ?>
	</div>
</div>
<div class="clear"></div>
</div>	
<p>&nbsp;</p>
		<script type="text/javascript">
			$(function() {
				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$( "#from_date" ).datepicker();
				$( "#to_date" ).datepicker();
			});		
		</script>

	<div class="filter_box">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td>Тип</td>
				<td>
				<select name="filter_case_type" class="ie80" style="width:105px;">
					<option value="*"> - Всички - </option>
					<?php if (count($types)) { ?>
					<?php foreach ($types as $id => $val) { ?>
					<option value="<?php echo $id; ?>" <?php echo ($id == $pflt_filter["filter_case_type"]? " selected":""); ?>> <?php echo $val;?> </option>
					<?php } ?>
					<?php } ?>
				</select>
				
				</td>
				<td>Дата</td>
				<td><input type="text" id="from_date" name="filter_case_from_date" class="ie80" value="<?php echo $pflt_filter["filter_case_from_date"]; ?>"></td>
				<td>До</td>
				<td>
				<input type="text" id="to_date" name="filter_case_to_date" class="ie80" value="<?php echo $pflt_filter["filter_case_to_date"]; ?>">
				</td>
				<td>Стойност</td>
				<td>
				<input type="text" name="filter_case_from_num" class="ie80" value="<?php echo $pflt_filter["filter_case_from_num"]; ?>">
				&nbsp;до&nbsp;
				<input type="text" name="filter_case_to_num" class="ie80" value="<?php echo $pflt_filter["filter_case_to_num"]; ?>">
				</td>
				<td style="text-align: right;">
						<input type="button" name="button_filter" value="Филтър" class="submit_button button_add" onclick="pflt_case_filter();">
						<input type="button" name="button_clear" value="X" class="submit_button button_delete" onclick="pflt_clear_filter('<?php echo $route; ?>');">
				</td>
			</tr>
		</table>
  <script type="text/javascript">
  	$(document).ready(function(){
  		if ($('select[name=\'filter_case_type\']').val() != '*') {
				$('select[name=\'filter_case_type\']').css({'border':'1px red solid'});
			}
			
  		if ($('input[name=\'filter_case_from_date\']').val() != '') {
				$('input[name=\'filter_case_from_date\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_case_to_date\']').val() != '') {
				$('input[name=\'filter_case_to_date\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_case_from_num\']').val() != '') {
				$('input[name=\'filter_case_from_num\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_case_to_num\']').val() != '') {
				$('input[name=\'filter_case_to_num\']').css({'border':'1px red solid'});
			}
		});
	</script>	
	</div>



<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="80">
		<col width="200">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th>Дата</th>
		<th>Основание</th>
		<th>Контрагент</th>
		<th>Тип</th>
		<th>Сума</th>
		<th>Действие</th>
	</tr>
	<?php if (count($payoffices)) { ?>
	<?php foreach ($payoffices as $case_id => $item) { ?>
	<?php $item["case_type"] = ($item["case_type"] == CASE_TYPE_COST? "<b style=\"color:#ff0000;\">Разход</b>":"<b style=\"color:#008000;\">Приход</b>"); ?>
	<tr>	
		<td align="center"><input type="checkbox"></td>
		<td align="center"><?php echo _fnes($item["case_date"]); ?></td>
		<td><?php echo _fnes($item["case_title"]); ?></td>
		<td align="center"><?php echo _fnes($item["case_notes"]); ?></td>
		<td align="center"><?php echo _fnes($item["case_type"]); ?></td>
		<td align="right"><?php echo _fnes(number_format((float)$item["case_sym"], 2)); ?></td>
		<td width="80" class="action">
		<a class="sadd" href="<?php echo $action_edit.$case_id.get_filter(); ?>"></a>
		<a class="sremove" href="javascript: void(0)" onclick="delete_settings('<?php echo $action_delete.$case_id.get_filter(); ?>');"></a>
		</td>
	</tr>
	
	<?php } ?>
	<?php } ?>
</table>
<div id="container">
<?php echo $styles; ?>
<?php echo $pagination; ?>
</div>

<?php } ?>

<?php 
	if (count($messages)) {
		displayMessages($messages);
	}
?>
