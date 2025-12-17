<?php		if ($page_tab == "edit") { ?>
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
					<td class="label">Статус</td>
					<td>
					<input type="text" name="" class="ie240" value="<?php echo $GLOBALS["ORDER_STATUS"][$document["DStatus"]]; ?>" readonly="readonly">
					</td>
					<td class="label">Номер</td>
					<td><input type="text" name="" class="ie240" value="<?php echo $document["DocNum"]; ?>" readonly="readonly"</td>
					<td align="right">&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Клиент</td>
					<td>
					<input type="text" name="" class="ie240" value="<?php echo $document["PName"]; ?>" readonly="readonly">
					</td>
				<td colspan=3>&nbsp;</td>	
				</tr>
				<tr>
					<td class="label">Дата</td>
					<td><input type="text" name="" class="ie240" value="<?php echo $document["Docdate"]; ?>" readonly="readonly"></td>

					<td class="label">Дата за получаване</td>
					<td><input type="text" name="" class="ie240" value="<?php echo $document["DocPeceiptDate"]; ?>" readonly="readonly"></td>
					<td>&nbsp;</td>	
				</tr>

				<tr>
					<td class="label">Обслужва</td>
					<td><input type="text" name="" class="ie240" value="<?php echo $document["member_login"]; ?>" readonly="readonly"> </td>
				<td colspan=3>&nbsp;</td>	
				</tr>


<!-- Repeat items -->
				<tr>
					<td colspan=5>

						<table id="doc_pos" width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
							<colgroup>
								<col width="80">
								<col  width="280">
								<col width="80">
								<col  width="280">
								<col>
							</colgroup>
							<?php foreach ($document["doc_options"] as $option_id => $item) { ?>
									<tr id="data_<?php echo $option_id; ?>">
										<td colspan=5>
										<?php 
										$item["DExportData"] = trim($item["DExportData"]);
											print  $item["DExportData"];
										?>	
										</td>
									</tr>
									<tr>
										<td class="label">Забележки</td>
										<td colspan=3><input type="text" name="" class="ie360" value="<?php echo $item["DNotes"]; ?>" readonly="readonly"></td>
									</tr>
					
							<?php }?>
						</table>
					</td>
				</tr>
<!-- Repeat items -->

				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				
				<?php 
				if ($document["DType"] == DOC_TYPE_ORDER) {
					include_once ('card_items.php');
				}
				?>
				<tr>
					<td>&nbsp;</td>
					<td colspan="4">
            <?php if (!OAL_Manager::getInstance()->isLock($doc_id)){?>
						<input type="button" name="button_save" value="<?php echo $doc_id?"Запази":"Добави"; ?>" class="submit_button <?php echo $doc_id?"button_edit":"button_add"; ?>" onclick="validate_docs('<?php echo $action_save.get_filter(); ?>');">
            <?php } ?>
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel.get_filter(); ?>'">
<!--
						<input type="button" name="export_data" value="Експорт" class="submit_button" onclick="data_export();">
-->
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=5><?php echo get_record_info("docs", "doc_id", $doc_id); ?></td>
				</tr>
		</table>

</form>


<?php		} else if (/*$page_tab == "owner"*/ false) { ?>


<!-- All Docs -->
<?php } else { ?>
<!-- All Docs -->
	<div class="filter_box">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td>Статус</td>
				<td>
				<select id="filter_card_status" name="filter_card_status"  class="ie240">
					<option value="*" <?php echo $pflt_filter["filter_card_status"] == "*"?" selected":""; ?>> - Избери статус - </option>
					<option value="<?php echo CARD_STATUS_W;?>" <?php echo $pflt_filter["filter_card_status"] == CARD_STATUS_W?" selected":""; ?>> <?php echo $GLOBALS["CARD_STATUS"][CARD_STATUS_W]; ?> </option>
					<option value="<?php echo CARD_STATUS_F;?>" <?php echo $pflt_filter["filter_card_status"] == CARD_STATUS_F?" selected":""; ?>> <?php echo $GLOBALS["CARD_STATUS"][CARD_STATUS_F]; ?> </option>
				</select>
				
				</td>
				<td>&nbsp;</td>
				<td>
				&nbsp;
				</td>
				<td style="text-align: right;">
						<input type="button" name="button_filter" value="Филтър" class="submit_button button_add" onclick="pflt_activities_filter();">
						<input type="button" name="button_clear" value="X" class="submit_button button_delete" onclick="pflt_clear_filter('<?php echo $route; ?>');">
				</td>
			</tr>
		</table>
  <script type="text/javascript">
  	$(document).ready(function(){
  		if ($('select[name=\'filter_card_status\'] option:selected').val() != '*') {
				$('select[name=\'filter_card_status\']').css({'border':'1px red solid'});
			}
			
		});
	</script>	
	</div>

<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="100">
		<col width="160">
		<col width="80">
		<col width="120">
		<col width="160">
		<col width="180">
		<col width="160">
		<col width="60">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th>No Поръчка</th>
		<th>Клиент</th>
		<th>Статус</th>
		<th>За дата</th>
		<th>Потребител</th>
		<th>Опсиание</th>
		<th>Контрагент</th>
		<th>Сума</th>
		<th>Действие</th>
	</tr>
	<?php
	//printArray($_SERVER);
//print $_SERVER['']."<br>"; //REMOTE_ADDR 89.106.109.91
/**
Test [KP06050 ( 2121)]
Test [KP06056 ( 2134)]
Test [KP06051 ( 2123)]
Test [KP06049 ( 2119)]
Test [KP06048 ( 2117)]
Test [KP06047 ( 2116)]
Test [KP06046 ( 2115)]
Test [KP06045 ( 2110)]
Test [KP06044 ( 2109)]
Test [KF01069 ( 2108)]
Test [KP06043 ( 2107)]
Test [KP06041 ( 2102)]
Test [KP06022 ( 2064)]
Test [KP06040 ( 2100)]

 */
if ($_SERVER['REMOTE_ADDR'] != '89.106.109.91'){ 
	printArray($activities);
}
	?>
	<?php if (count($activities)) { ?>
	<?php foreach ($activities as $d_id => $item) { 
	$box_title = " - ";
	$box_content = "";	
	$box_content = _clear($box_content);

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
	?>
	
	<?php if (count($item["ITEM_A"])) { ?>
<!--		<tr>	
			<td align="center"><input type="checkbox"></td>
			<td colspan=9><b>Дейности</b></td>
		</tr>	-->
	<?php 
	foreach ($item["ITEM_A"] as $k => $v) { 
	$v["card_status"] = $v["card_status"]?CARD_STATUS_F:CARD_STATUS_W;
	?>
		<tr<?php echo (isForgotten($v) ? ' class="mark-as-forgotten"':''); ?>>	
			<td align="center"><input type="checkbox"></td>
			<td><?php echo _fnes($item["DocNum"]); ?></td>
			<td><?php echo _fnes($item["PName"]); ?></td>
			<td align="center"><?php echo _fnes($GLOBALS["CARD_STATUS"][$v["card_status"]]); ?></td>
			<td align="center"><?php echo _fnes($v["card_date"]); ?></td>
			<td><?php echo _fnes($v["fullName"]); ?></td>
			<td><?php echo _fnes(htmlspecialchars($v["card_notes"])); ?></td>
			<td>-</td>
			<td>-</td>
			<td width="60" class="action"><a class="sadd" href="<?php echo $action_edit.$d_id.get_filter(); ?>"></a></td>
		</tr>	
	<?php } ?>
	<?php } ?>
	<?php if (count($item["ITEM_C"])) { 
	?>
<!--		<tr>	
			<td align="center"><input type="checkbox"></td>
			<td colspan=9><b>Контрагенти</b></td>
		</tr>	-->
	<?php foreach ($item["ITEM_C"] as $k => $v) { 
		$v["CStatus"] = $v["CStatus"]?CARD_STATUS_F:CARD_STATUS_W;
	?>
		<tr<?php echo (isForgotten($v) ? ' class="mark-as-forgotten"':''); ?>>	
			<td align="center"><input type="checkbox"></td>
			<td><?php echo _fnes($item["DocNum"]); ?></td>
			<td><?php echo _fnes($item["PName"]); ?></td>
			<td align="center"><?php echo _fnes($GLOBALS["CARD_STATUS"][$v["CStatus"]]); ?></td>
			<td align="center"><?php echo _fnes($v["CDate"]); ?></td>
			<td><?php echo _fnes($v["fullName"]); ?></td>
			<td><?php echo _fnes(htmlspecialchars($v["CNotes"])); ?></td>
			<td><?php echo _fnes($v["CName"]); ?></td>
			<td align="right"><?php echo _fnes($v["CSum"]); ?></td>
			<td width="60" class="action"><a class="sadd" href="<?php echo $action_edit.$d_id.get_filter(); ?>"></a></td>
		</tr>	
	<?php } ?>
	<?php } ?>
	
	
	<?php } ?>
	<?php } ?>
</table>
<div id="container">
<?php echo $styles; ?>
<?php echo $pagination; ?>
</div>


<!-- All Docs -->

<?php		} ?>


<div id="popup_box">
		<div id="popup_heading">
    <h1></h1>
    <a id="popupBoxClose" href="javascript: void(0);" onclick="hidePopUpBox();">Close</a>
		</div>   
    <div id="popup_box_content">
    </div>
</div>

<script type="text/javascript">


function view_dialog(c_id, opt_id) {

	if (SYSTEM_CALC[c_id]['folder'] == '-') {
		msg_add('Избран е <b>Универсален Калкулатор</b>, шаблона ще бъде импортиран автоматично след натискане на експорт!', MSG_INFO);
	} else {
		var domain = '<?php echo HTTP_CALCULATOR;?>'+SYSTEM_CALC[c_id]['folder']+'/index.html?opt_id='+opt_id;
		setIFramePopUpBox(''+SYSTEM_CALC[c_id]['name']+'', domain);
	}
}

function update_image(image_id) {
}


function getActivities(doc_id) {

}

//view_term
$(document).ready(function(){
/**/

  //$('div#view_term').html()
});

</script>

<?php 
	if (count($messages)) {
		displayMessages($messages);
	}
?>
