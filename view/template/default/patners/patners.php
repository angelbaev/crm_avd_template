<?php if ($page_tab == "new_partner" || $page_tab == "edit_partner") { ?>
<form action="<?php echo $action;?>" name="fEdit" method="post">
<?php 
?>
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="120">
					<col  width="280">
					<col width="120">
					<col  width="280">
					<col>
				</colgroup>
				<?php if ($partner_id) { ?>
				<tr>
					<td class="label">Име</td>
					<td>
					<select id="partner_id" class="ie240s">
						<option value=""> - Избери клиент - </option>
						<?php if (count($all_patners)) { ?>
						<?php foreach ($all_patners as $pid => $item) { ?>
						<option value="<?php echo $pid; ?>" <?php echo ($pid == $partner_id? " selected":""); ?>> <?php echo $item["PName"];?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td colspan="3">	<input type="button" name="button_load" value="Зареди" class="submit_button button_edit" onclick="update_settings('<?php echo $action_edit; ?>'+$('select#partner_id option:selected').val());"></td>
				</tr>
				<?php } ?>

				<tr>
					<td class="label">Име</td>
					<td colspan="3"><input id="PName" type="text" name="patner[PName]" class="ie360" value="<?php echo $patner["PName"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Държава</td>
					<td><input type="text" name="patner[PCountry]" class="ie240" value="<?php echo $patner["PCountry"]; ?>"></td>
					<td class="label">Лице за контакти</td>
					<td><input type="text" name="patner[PPerson]" class="ie240" value="<?php echo $patner["PPerson"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Град</td>
					<td><input type="text" name="patner[PCity]" class="ie240" value="<?php echo $patner["PCity"]; ?>"></td>
					<td class="label">Телефон</td>
					<td><input type="text" name="patner[PPhone]" class="ie240" value="<?php echo $patner["PPhone"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Адрес</td>
					<td><input type="text" name="patner[PAddr]" class="ie240" value="<?php echo $patner["PAddr"]; ?>"></td>
					<td class="label">Е-mail</td>
					<td><input id="partner_email" type="text" name="patner[PEmail]" class="ie240" value="<?php echo $patner["PEmail"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">МОЛ</td>
					<td><input type="text" name="patner[PMol]" class="ie240" value="<?php echo $patner["PMol"]; ?>"></td>
					<td class="label">Адрес за доставка</td>
					<td><input type="text" name="patner[PCAddr]" class="ie240" value="<?php echo $patner["PCAddr"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Булстат</td>
					<td><input type="text" name="patner[PBulstat]" class="ie240" value="<?php echo $patner["PBulstat"]; ?>"></td>
					<td class="label">Website</td>
					<td><input type="text" name="patner[PWebsite]" class="ie240" value="<?php echo $patner["PWebsite"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Регистрация по ДДС</td>
					<td><?php echo input_cb("patner[PZDDS]", $patner["PZDDS"]) ?></td>
					<td class="label">Вид</td>
					<td>
					<select id="ptype" name="patner[PType]" class="ie120">
						<option value=""> - Вид - </option>
						<?php foreach ($patner_types as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $patner["PType"]? " selected":""); ?>> <?php echo $val; ?> </option>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
				</tr>

				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td colspan="4">
						<input type="button" name="button_save" value="<?php echo ($partner_id? "Запис":"Добави"); ?>" class="submit_button <?php echo ($partner_id? "button_edit":"button_add"); ?>" onclick="validate_partner('<?php echo $action_save.get_filter(); ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel.get_filter(); ?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=5><?php echo get_record_info("partners", "partner_id", $partner_id); ?></td>
				</tr>
		</table>

</form>

<?php } else { ?>
	<div class="filter_box">

		<table class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="120">
					<col  width="280">
					<col width="120">
					<col  width="220">
					<col>
				</colgroup>
				<tr>
					<td class="label">Търси</td>
					<td><input type="text" name="filter_partner_name" id="filter_partner_name" class="ie240" value="<?php echo $pflt_filter["filter_partner_name"]; ?>" onclick="$('input#filter_partner_name').val('');"></td>
					<td class="label">Вид</td>
					<td>
						<select id="filter_partner_type" name="filter_partner_type" class="ie120">
							<option value="*"> - Вид - </option>
							<?php foreach ($patner_types as $id => $val) { ?>
							<option value="<?php echo $id; ?>" <?php echo ($id == $pflt_filter["filter_partner_type"]? " selected":""); ?>> <?php echo $val; ?> </option>
							<?php } ?>
						</select>
					</td>
				<td style="text-align: right;">
						<input type="button" name="button_filter" value="Филтър" class="submit_button button_add" onclick="pflt_partner_filter();">
						<input type="button" name="button_clear" value="X" class="submit_button button_delete" onclick="pflt_clear_filter('<?php echo $route; ?>');">
				</td>
				</tr>
		</table>
  <script type="text/javascript">
  	$(document).ready(function(){
  		if ($('input[name=\'filter_partner_name\']').val() != '') {
				$('input[name=\'filter_partner_name\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_partner_type\'] option:selected').val() != '*') {
				$('select[name=\'filter_partner_type\']').css({'border':'1px red solid'});
			}
			
			$('input[name=\'filter_partner_name\']').keypress(function(event){
			  if (event.which == 13) {
			    event.preventDefault();
			    pflt_partner_filter();
			  }  
			});
			
		});
	</script>	
		
	</div>	

<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="200">
		<col width="80">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="120">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th>Наименование</th>
		<th>Вид</th>
		<th>Държава</th>
		<th>Град</th>
		<th>Телефон</th>
		<th>Email</th>
		<th>Действие</th>
	</tr>
	<?php if (count($patners)) { ?>
	<?php foreach ($patners as $partner_id => $item) { ?>
	<?php
		$box_title = ' &raquo; '._clear($item["PName"]);
		$box_content = "
		<table class=\"form\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
				<colgroup>
					<col width=\"120\">
					<col  width=\"240\">
					<col width=\"120\">
					<col  width=\"240\">
					<col>
				</colgroup>

				<tr>
					<td class=\"label\">Име</td>
					<td colspan=\"3\">".$item["PName"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Държава</td>
					<td>".$item["PCountry"]."</td>
					<td class=\"label\">Лице за контакти</td>
					<td>".$item["PPerson"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Град</td>
					<td>".$item["PCity"]."</td>
					<td class=\"label\">Телефон</td>
					<td>".$item["PPhone"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Адрес</td>
					<td>".$item["PAddr"]."</td>
					<td class=\"label\">Е-mail</td>
					<td>".$item["PEmail"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">МОЛ</td>
					<td>".$item["PMol"]."</td>
					<td class=\"label\">Адрес за доставка</td>
					<td>".$item["PCAddr"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Булстат</td>
					<td>".$item["PBulstat"]."</td>
					<td class=\"label\">Website</td>
					<td>".$item["PWebsite"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Регистрация по ДДС</td>
					<td>".input_cb("", $item["PZDDS"])."</td>
					<td class=\"label\">Вид</td>
					<td>
					".$patner_types[$item["PType"]]."
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr class=\"line\">
					<td colspan=5>&nbsp;</td>
				</tr>
		</table>
		
		";
		$box_content = _clear($box_content);
		
		if (strlen($item["PEmail"]) > 23) {
			$make_hint = true;
		} else {
			$make_hint = false;
		}
		if (strlen($item["PPhone"]) > 20) {
			$make_hint2 = true;
		} else {
			$make_hint2 = false;
		}
	?>
	<tr>	
		<td align="center"><input type="checkbox"></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo $item["PName"]; ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');" align="center"><?php echo $GLOBALS["PARTNER_TYPES"][$item["PType"]]; ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo $item["PCountry"]; ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo $item["PCity"]; ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"<?php echo ($make_hint2?' title="'.$item["PPhone"].'"':'');?>><?php echo ($make_hint2?substr($item["PPhone"], 0, 18)."..":$item["PPhone"]); ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');" <?php echo ($make_hint?' title="'.$item["PEmail"].'"':'');?>><?php echo ($make_hint?substr($item["PEmail"], 0, 20)."..":$item["PEmail"]); ?></td>
		<td width="80" class="action">
		<a class="sadd" href="<?php echo $action_edit.$partner_id.get_filter(); ?>"></a>
		<a class="sremove" href="javascript: void(0)" onclick="delete_settings('<?php echo $action_delete.$partner_id.get_filter(); ?>');"></a>
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

<div id="popup_box">
		<div id="popup_heading">
    <h1></h1>
    <a id="popupBoxClose" href="javascript: void(0);" onclick="hidePopUpBox();">Close</a>
		</div>   
    <div id="popup_box_content">
    </div>
</div>

<?php 
	if (count($messages)) {
		displayMessages($messages);
	}
?>
