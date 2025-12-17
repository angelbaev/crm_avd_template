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
<?php if (isset($_GET['view']) && $_GET['view'] == 'new') { ?>
<?php include_once 'activity_list_view.php'; ?>
<?php } else { ?>
<!-- All Docs -->
<?php include_once 'activity_list_view.php'; ?>
<!-- All Docs -->
<?php } //view ?>
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
