<?php if ($page_tab == "new_contractor" || $page_tab == "edit_contractor") { ?>

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
				<?php if ($c_id) { ?>
				<tr>
					<td class="label">Котрегенти</td>
					<td>
					<select id="c_id" class="ie240s">
						<option value=""> - Избери контрагент - </option>
						<?php if (count($all_suppliers)) { ?>
						<?php foreach ($all_suppliers as $cid => $item) { ?>
						<option value="<?php echo $cid; ?>" <?php echo ($cid == $c_id? " selected":""); ?>> <?php echo $item["CName"];?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td colspan="3">	<input type="button" name="button_load" value="Зареди" class="submit_button button_edit" onclick="update_settings('<?php echo $action_edit; ?>'+$('select#c_id option:selected').val());"></td>
				</tr>
				<?php } ?>
				<tr>
					<td class="label">Име</td>
					<td><input type="text" id="CName" name="supplier[CName]" class="ie240" value="<?php echo $supplier["CName"]; ?>"></td>
					<td class="label">Лице за контакти</td>
					<td><input type="text" name="supplier[CPerson]" class="ie240" value="<?php echo $supplier["CPerson"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Град</td>
					<td><input type="text" name="supplier[CCity]" class="ie240" value="<?php echo $supplier["CCity"]; ?>"></td>
					<td class="label">Телефон</td>
					<td><input type="text" name="supplier[CPhone]" class="ie240" value="<?php echo $supplier["CPhone"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Адрес</td>
					<td><input type="text" name="supplier[CAddr]" class="ie240" value="<?php echo $supplier["CAddr"]; ?>"></td>
					<td class="label">Е-mail</td>
					<td><input id="supplier_email" type="text" name="supplier[CEmail]" class="ie240" value="<?php echo $supplier["CEmail"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Сектор</td>
					<td><input type="text" name="supplier[CSector]" class="ie240" value="<?php echo $supplier["CSector"]; ?>"></td>
					<td class="label">Website</td>
					<td><input type="text" name="supplier[CWebsite]" class="ie240" value="<?php echo $supplier["CWebsite"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Цени</td>
					<td colspan="3">
					<textarea name="supplier[CPriceList]" id="CPriceList" rows="5" class="ie360" style="width:640px;"><?php echo $supplier["CPriceList"]; ?></textarea>
					</td>
					<td>&nbsp;</td>
				</tr>

				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td colspan="4">
						<input type="button" name="button_save" value="<?php echo ($c_id? "Запис":"Добави"); ?>" class="submit_button <?php echo ($c_id? "button_edit":"button_add"); ?>" onclick="validate_supplier('<?php echo $action_save.get_filter(); ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel.get_filter(); ?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=5><?php echo get_record_info("suppliers", "c_id", $c_id); ?></td>
				</tr>
		</table>

</form>
<?php } else if ($page_tab == "printing") { ?>

<?php include_once('printing.php'); ?>

<?php } else { ?>

	<div class="filter_box">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td>Сектор</td>
				<td><input type="text" name="filter_supplier_sector" class="ie120" value="<?php echo $pflt_filter["filter_supplier_sector"]; ?>"></td>
				<td>Наименование</td>
				<td>
				<input type="text" name="filter_supplier_name" class="ie240" value="<?php echo $pflt_filter["filter_supplier_name"]; ?>">
				</td>
				<td style="text-align: right;">
						<input type="button" name="button_filter" value="Филтър" class="submit_button button_add" onclick="pflt_supplier_filter();">
						<input type="button" name="button_clear" value="X" class="submit_button button_delete" onclick="pflt_clear_filter('<?php echo $route; ?>');">
				</td>
			</tr>
		</table>
  <script type="text/javascript">
  	$(document).ready(function(){
  		if ($('input[name=\'filter_supplier_sector\']').val() != '') {
				$('input[name=\'filter_supplier_sector\']').css({'border':'1px red solid'});
			}
  		if ($('input[name=\'filter_supplier_name\']').val() != '') {
				$('input[name=\'filter_supplier_name\']').css({'border':'1px red solid'});
			}

			$('input[name=\'filter_supplier_sector\']').keypress(function(event){
			  if (event.which == 13) {
			    event.preventDefault();
			    pflt_supplier_filter();
			  }  
			});
			$('input[name=\'filter_supplier_name\']').keypress(function(event){
			  if (event.which == 13) {
			    event.preventDefault();
			    pflt_supplier_filter();
			  }  
			});
			
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
		<th>Сектор</th>
		<th>Наименование</th>
		<th>Град</th>
		<th>Телефон</th>
		<th>Email</th>
		<th>Действие</th>
	</tr>
	<?php if (count($suppliers)) { ?>
	<?php foreach ($suppliers as $c_id => $item) { ?>
	<?php 
	//print $item["CPriceList"]."<br>";
	$item["CPriceList"] = trim($item["CPriceList"]);
	if (!empty($item["CPriceList"])) {
		$tbl_item = explode("\n", $item["CPriceList"]);
		$_sub_html = "";
		if (count($tbl_item)) {
			$_sub_html .= "<table>";
			foreach($tbl_item as $k => $v) {
				$v = trim($v);
				if (!empty($v)){
					$_sub_html .= "<tr><td>".$v."</td></tr>";
				}
			}
			$_sub_html .= "</table>";
		}
		 $item["CPriceList"] = $_sub_html;
	}	
		$box_title = ' &raquo; '._clear($item["CName"]);
		$box_content = "
		<table width=\"100%\" class=\"form\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
				<colgroup>
					<col width=\"120\">
					<col  width=\"280\">
					<col width=\"120\">
					<col  width=\"280\">
					<col>
				</colgroup>
				<tr>
					<td class=\"label\">Име</td>
					<td>".$item["CName"]."</td>
					<td class=\"label\">Лице за контакти</td>
					<td>".$item["CPerson"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Град</td>
					<td>".$item["CCity"]."</td>
					<td class=\"label\">Телефон</td>
					<td>".$item["CPhone"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Адрес</td>
					<td>".$item["CAddr"]."</td>
					<td class=\"label\">Е-mail</td>
					<td>".$item["CEmail"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Сектор</td>
					<td>".$item["CSector"]."</td>
					<td class=\"label\">Website</td>
					<td>".$item["CWebsite"]."</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class=\"label\">Цени</td>
					<td colspan=\"3\">
					".$item["CPriceList"]."
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
		if (strlen($item["CPhone"]) > 20) {
			$make_hint2 = true;
		} else {
			$make_hint2 = false;
		}
		
	?>
	<tr>	
		<td align="center"><input type="checkbox"></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo $item["CSector"]; ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo $item["CName"]; ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo $item["CCity"]; ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');" <?php echo ($make_hint2?' title="'.$item["CPhone"].'"':'');?>><?php echo ($make_hint2?substr($item["CPhone"], 0, 18)."..":$item["CPhone"]); ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');" <?php echo ($make_hint?' title="'.$item["CEmail"].'"':'');?>><?php echo ($make_hint?substr($item["CEmail"], 0, 20)."..":$item["CEmail"]); ?></td>
		<td width="80" class="action">
		<a class="sadd" href="<?php echo $action_edit.$c_id.get_filter(); ?>"></a>
		<a class="sremove" href="javascript: void(0)" onclick="delete_settings('<?php echo $action_delete.$c_id.get_filter(); ?>');"></a>
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
