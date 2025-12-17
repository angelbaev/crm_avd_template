	<?php 
		print "
		<script type=\"text/javascript\">
			var SYSTEM_CALC = new Object();
		";
		$js = array();
		foreach ($GLOBALS["SYSTEM_CALC"] as $id =>$item) {
			$js[] = "'".$id."':{'id':'".$id."', 'name':'".$item["NAME"]."', 'folder':'".$item["FOLDER"]."'} ";
		}
		
		if (count($js)) {
			print "SYSTEM_CALC = {".implode(", ", $js)."};";
		}
		print "
		</script>
		";
	?>

		<script type="text/javascript">
			$(function() {

				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$( "#Docdate" ).datepicker();
				$( "#DocPeceiptDate" ).datepicker();
			});		
		</script>
<?php		if ($page_tab == "edit_doc") { ?>
<?php if ($document["DType"] == DOC_TYPE_ORDER){ 
?>
<?php } else { ?>
<style type="text/css">
	.is_order {
		display: none;
	}
</style>
<?php } ?>

<?php

/*
function extract_images($content)
{
    $img    = strip_tags(html_entity_decode($content),'<img>');
    $regex  = '~src="[^"]*"~';    

    preg_match_all($regex, $img, $all_images);

    return $all_images;
}
*/
if(isset($document["doc_options"])) {
	foreach($document["doc_options"] as $key => $val) {
		//$images = extract_images($val['DExportData']);
		preg_match('#(<img.*?\>)#', $val['DExportData'], $results);
		$images = array();
		if(isset($results[0])) {
			preg_match( '/src="([^"]*)"/i', $results[0], $_src ) ;
			preg_match( '/width="([^"]*)"/i', $results[0], $_width ) ;
			preg_match( '/height="([^"]*)"/i', $results[0], $_height ) ;
			if(isset($_src[1])) {
				$r_image = '<img id="print_image_'.$val["option_id"].'" src="'.$_src[1].'" onclick="update_image(\''.$val["option_id"].'\');" border="0" height="'.(isset($_height[1])?$_height[1]:'210').'" width="'.(isset($_width[1])?$_width[1]:'210').'">';
			 	$o_image = $results[0];
			 	//print "test: ".htmlspecialchars($r_image)."<br>";
				$document["doc_options"][$key]['DExportData'] = str_replace($o_image, $r_image, $val['DExportData']);
			}
			/*
			$images = extract_images($results[0]);
			if(isset($images[0][0])) {
			 preg_match( '/src="([^"]*)"/i', $images[0][0], $array ) ;
			 if(isset($array[1])) {
				$r_image = '<img id="print_image_'.$val["option_id"].'" src="'.$array[1].'" onclick="update_image(\''.$val["option_id"].'\');" border="0" height="210" width="210">';
			 	$o_image = $results[1];
			 	$document["doc_options"][$key]['DExportData'] = str_replace($o_image, $r_image, $val['DExportData']);
			 }
			}
			*/
		}
		//echo strip_tags($val['DExportData'], '<img>');
	}
}
?>
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
					<select id="DStatus" name="doc[DStatus]" class="ie120">
						<?php if (count($order_status)) { ?>
						<?php foreach ($order_status as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["DStatus"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td colspan="2">
					<?php if ($document["DType"] == DOC_TYPE_OFFER) { ?>
						<?php if (canDoOnOrder($doc_id)) { ?>
						<a class="order" href="<?php echo $action_edit.canDoOnOrder($doc_id); ?>"> <b>&raquo;</b> Към поръчката!</a>
						<?php } else { ?>
						<a class="order" href="<?php echo $action_order; ?>"> <b>&raquo;</b> Направи поръчка от тази оферта!</a>
						<?php } ?>
					<?php } else { ?>
						<?php if ($document["from_doc_id"]) { ?>
							<a class="offer" href="<?php echo $action_edit.$document["from_doc_id"]; ?>"> <b>&raquo;</b> Към оферта!</a>
						<?php } ?>
					<?php } ?>
					&nbsp;
					</td>
					<td align="right">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<a class="email_send" href="<?php echo $action_email; ?>"></a>
							</td>
							<td>
									<a class="print_doc" title="Оферта" href="<?php echo HTTP_SERVER."print/print-doc.php?id=".$doc_id;?>" target="_blank"></a>
							</td>
							<td>
									<a class="print_doc_rec" title="Заявка за изработка" href="<?php echo HTTP_SERVER."print/print-request-for-production.php?id=".$doc_id;?>" target="_blank"></a>
							</td>
						</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td class="label">Тип</td>
					<td>
                                        <?php if (!empty($document['doc_id'])){ ?>
                                            <input type="hidden" name="doc[DType]" value="<?php echo $document["DType"]; ?>">
                                        <?php } ?>
					<select id="DType" name="" class="ie120" onchange="showOrderBox(this.value);" <?php echo (!empty($document['doc_id'])? 'disabled':''); ?> >
						<?php if (count($doc_types)) { ?>
						<?php foreach ($doc_types as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["DType"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td class="label">Номер</td>
					<td><input type="text" name="doc[DocNum]" class="ie240" value="<?php echo $document["DocNum"]; ?>" readonly="readonly"></td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td class="label">Клиент</td>
					<td>
 					   <input type="hidden" id="partner_id" name="doc[partner_id]" value="<?php echo $document["partner_id"]; ?>">
            <div class = "ui-widget">
               <input id="autocomplete-partner" class="ie240" value="<?php echo (isset($all_partners[$document["partner_id"]]["PName"]) ? $all_partners[$document["partner_id"]]["PName"] : '');?>">
            </div>
     <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<?php 
		print "
		<script type=\"text/javascript\">
			var partners = [];
		";
		$js = array();
		foreach ($all_partners as $id =>$item) {
		  $name = str_replace("'", "", $item["PName"]);
		  $name = str_replace('"', "", $item["PName"]);
			$js[] = '{id: '.$id.', label: "'.$name.'", value: "'.$name.'"}';
		}
		
		if (count($js)) {
			print "partners = [".implode(", ", $js)."];";
		}
		print "
		</script>
		";
	?>      
      <script>
         $(function() {
            $( "#autocomplete-partner" ).autocomplete({
               source: partners,
               select: function( event, ui ) {
                  document.getElementById('partner_id').value = ui.item.id;
               }               
            });
         });
      </script>     
      <!--					
						<select id="partner_id" name="doc[partner_id]"  class="ie240s">
							<option value=""> - Избери клиент - </option>
							<?php if (count($all_partners)) { ?>
							<?php foreach ($all_partners as $pid => $item) { ?>
							<option value="<?php echo $pid; ?>" <?php echo ($pid == $document["partner_id"]? " selected":""); ?>> <?php echo $item["PName"];?> </option>
							<?php } ?>
							<?php } ?>
						</select>
					-->
					</td>

				<td class="label is_order">Плащане</td>
				<td class="is_order">
					<select id="DPayment" name="doc[DPayment]" class="ie120">
						<option value=""> - Плащане - </option>
						<?php if (count($order_payment)) { ?>
						<?php foreach ($order_payment as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["DPayment"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
				</td>
				<td>&nbsp;</td>	
				</tr>
				<tr>
					<td class="label">Дата</td>
					<td><input type="text" id="Docdate" name="doc[Docdate]" class="ie240" value="<?php echo $document["Docdate"]; ?>"></td>

					<td class="label is_order">Аванс</td>
					<td class="is_order"><input type="text" name="doc[DAdvance]" class="ie240" value="<?php echo $document["DAdvance"]; ?>"></td>
					<td  class="is_order" style="background-color: #eeeeee;"><?php print input_cb('doc[doc_payed]', $document["doc_payed"], false, "doc_payed", "Платено"); ?></td>	
				</tr>

				<tr class="is_order">
					<td class="label is_order">Дата за получаване</td>
					<td class="is_order"><input type="text" id="DocPeceiptDate" name="doc[DocPeceiptDate]" class="ie240" value="<?php echo $document["DocPeceiptDate"]; ?>"></td>

					<td class="label is_order">Доплащане</td>
					<td class="is_order"><input type="text" name="doc[DSurcharge]" class="ie240" value="<?php echo $document["DSurcharge"]; ?>"></td>
					<td  class="is_order" style="background-color: #eeeeee;"><?php print input_cb('doc[doc_payed2]', $document["doc_payed2"], false, "doc_payed2", "Платено"); ?></td>	
				</tr>

				<tr class="is_order">
					<td class="label is_order">ТОТАЛ</td>
					<td class="is_order"><input type="text" name="doc[DTotal]" class="ie240" value="<?php echo $document["DTotal"]; ?>"></td>

					<td class="label is_order">Корекции</td>
					<td class="is_order"><input type="text" name="doc[DCorrections]" class="ie240" value="<?php echo $document["DCorrections"]; ?>"></td>
				<td>&nbsp;</td>	
				</tr>

				<tr>
					<td class="label">Обслужва</td>
					<td><?php echo userSelect("doc[uid]", "", $document["uid"]); ?></td>

					<td class="label is_order">Място за получаване</td>
					<td class="is_order"><input type="text" name="doc[DCAddr]" class="ie240" value="<?php echo $document["DCAddr"]; ?>"></td>
					<td>&nbsp;</td>	
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
							<?php if (count($document["doc_options"]) == 0) { ?>
							<tr id="calculator_1">
								<td class="label">Позиция 1</td>
								<td>
								<?php echo cSelect("doc[doc_options][1][c_id]", "c_id_1", '1'); ?>								
								</td>
								<td colspan=2>
								<input type="button" name="calc_load" value="Зареди" class="submit_button button_edit" onclick="view_dialog($('select#c_id_1 option:selected').val(), '1');">
								</td>
								<td>
								<a class="remove" href="javascript: void(0);" onclick="removeOption(1)"></a>
								</td>
							</tr>
							<tr id="data_1">
								<td colspan=5>
								<textarea id="c_export_1" name="doc[doc_options][1][DExportData]" rows="10" style="width:80%;display:none;">
								</textarea>
								<div id="c_export_view_1"></div>	
								</td>
							</tr>
									<tr id="notes_1">
										<td class="label">Забележки</td>
										<td colspan=3><input type="text" name="doc[doc_options][1][DNotes]" class="ie360" value=""> &nbsp;&nbsp;<b class="label">Тотал: </b><input type="text" name="doc[doc_options][1][PSum]" class="ie80" value=""></td>
									</tr>
							<?php } else {?>
							<?php foreach ($document["doc_options"] as $option_id => $item) { ?>
									<tr id="calculator_<?php echo $option_id; ?>">
										<td class="label">Позиция <?php echo $option_id; ?></td>
										<td>
										<?php echo cSelect("doc[doc_options][".$option_id."][c_id]", "c_id_".$option_id, $item["c_id"]); ?>
										</td>
										<td colspan=2>
										<input type="button" name="calc_load" value="Зареди" class="submit_button button_edit" onclick="view_dialog($('select#c_id_<?php echo $option_id; ?> option:selected').val(), '<?php echo $option_id; ?>')">
										</td>
										<td>
										<a class="remove" href="javascript: void(0);" onclick="removeOption(<?php echo $option_id; ?>)"></a>
										</td>
									</tr>
									<tr id="data_<?php echo $option_id; ?>">
										<td colspan=5>
										<?php 
										$item["DExportData"] = trim($item["DExportData"]); 
										if (!empty($item["DExportData"])) { 
										?>	
										<div class="edit_wrapp">
											<div class="edit_heading"> <div class="edit_icon"></div></div>
											<div class="edit_content">
										<?php } ?>	
										
<!-- Editable content begin -->										
											<textarea id="c_export_<?php echo $option_id; ?>" name="doc[doc_options][<?php echo $option_id; ?>][DExportData]" rows="10" style="width:80%;display:none;"><?php echo trim($item["DExportData"]); ?></textarea>

												<div id="c_export_view_<?php echo $option_id; ?>" class="editable" contenteditable="false"><?php echo trim($item["DExportData"]); ?></div>	
<!-- Editable content end -->										
										<?php if (!empty($item["DExportData"])) { ?>	
								
											</div>	
										</div>
										<?php } ?>	
										</td>
									</tr>
									<tr id="notes_<?php echo $option_id; ?>">
										<td class="label">Забележки</td>
										<td colspan=3><input type="text" name="doc[doc_options][<?php echo $option_id; ?>][DNotes]" class="ie360" value="<?php echo $item["DNotes"]; ?>"> &nbsp;&nbsp;<b class="label">Тотал: </b><input type="text" name="doc[doc_options][<?php echo $option_id; ?>][PSum]" class="ie80" value="<?php echo $item["PSum"]; ?>"></td>
									</tr>
					
							<?php }?>
							<?php }?>
						</table>
					</td>
				</tr>
<!-- Repeat items -->

				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=5>
					<div id="view_term" style="<?php echo (!empty($document["view_term"])?"display: block;":"display: none;");?>">
					<?php echo $GLOBALS["TERMS_HTML"]; ?>
					</div>
					</td>
				</tr>
				
				<tr>
					<td class="label">Срок за изработка</td>
					<td>
					<input id="term_work" type="text" name="doc[term_work]" class="ie240" value="<?php echo $document["term_work"];?>">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Доставка</td>
					<td>
					<select id="delivery_place" name="doc[delivery_place]" class="ie240s">
						<?php if (count($delivery_place)) { ?>
						<?php foreach ($delivery_place as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["delivery_place"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">ДДС</td>
					<td>
					<select id="vat_id" name="doc[vat_id]" class="ie240s">
						<?php if (count($price_vat)) { ?>
						<?php foreach ($price_vat as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["vat_id"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Плащане</td>
					<td colspan="2">
					Аванс &nbsp;
					<input id="Dpay_advance" type="text" name="doc[Dpay_advance]" class="ie60" value="<?php echo $document["Dpay_advance"];?>">
					&nbsp;
					Доплащане &nbsp;
					<input id="Dpay_surcharge" type="text" name="doc[Dpay_surcharge]" class="ie60" value="<?php echo $document["Dpay_surcharge"];?>">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Покажи усковията</td>
					<td>
					<select id="view_term" name="doc[view_term]" class="ie120">
						<?php if (count($view_term)) { ?>
						<?php foreach ($view_term as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["view_term"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

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
						<input type="button" name="add_position" value="Добави позиция" class="submit_button button_add" onclick="addOption();">
            <?php if (!OAL_Manager::getInstance()->isLock($doc_id)){?>
            <?php 
              $validateAll = (isset($document["DType"]) && $document["DType"] == DOC_TYPE_ORDER ? 1:0);
            ?>
						<input type="button" name="button_save" value="<?php echo $doc_id?"Запази":"Добави"; ?>" class="submit_button <?php echo $doc_id?"button_edit":"button_add"; ?>" onclick="validate_docs('<?php echo $action_save.get_filter(); ?>', '<?php echo $validateAll; ?>');">
            <?php }  ?>
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
<?php
//printArray($document["doc_options"]);

//	printArray($document["doc_options"]);
?>

<?php		} else if ($page_tab == "all") { ?>
<!-- All Docs -->
	<div class="filter_box">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td>Номер</td>
				<td><input type="text" name="filter_doc_num" class="ie120" value="<?php echo $pflt_filter["filter_doc_num"]; ?>"></td>
				<td>Тип</td>
				<td>
				<select name="filter_doc_type" class="ie120">
					<option value="*"> - Всички - </option>
					<?php if (count($doc_types)) { ?>
					<?php foreach ($doc_types as $id => $val) { ?>
					<option value="<?php echo $id; ?>" <?php echo($id == $pflt_filter["filter_doc_type"]? " selected":""); ?>> <?php echo $val;?> </option>
					<?php } ?>
					<?php } ?>
				</select>
				
				</td>
				<td>Статус</td>
				<td>
				<select name="filter_doc_status" class="ie120">
					<option value="*"> - Всички - </option>
					<?php if (count($order_status)) { ?>
					<?php foreach ($order_status as $id => $val) { ?>
					<option value="<?php echo $id; ?>" <?php echo ($id == $pflt_filter["filter_doc_status"]? " selected":""); ?>> <?php echo $val;?> </option>
					<?php } ?>
					<?php } ?>
				</select>
				</td>
				<td style="text-align: right;">
						<input type="button" name="button_filter" value="Филтър" class="submit_button button_add" onclick="pflt_doc_filter();">
						<input type="button" name="button_clear" value="X" class="submit_button button_delete" onclick="pflt_clear_filter('<?php echo $route; ?>');">
				</td>
			</tr>
			<tr>
				<td>Обслужва</td>
				<td colspan=5>
				<?php echo userSelect("filter_doc_user", "", $pflt_filter["filter_doc_user"], "", true); ?>
				
				Клиент 
				<select id="filter_doc_partner_id" name="filter_doc_partner_id"  class="ie240">
					<option value="*"> - Избери клиент - </option>
					<?php if (count($all_partners)) { ?>
					<?php foreach ($all_partners as $pid => $item) { ?>
					<option value="<?php echo $pid; ?>" <?php echo ($pid == $pflt_filter["filter_doc_partner_id"]? " selected":""); ?>> <?php echo $item["PName"];?> </option>
					<?php } ?>
					<?php } ?>
				</select>
				
				</td>
				<td colspan=2>
				&nbsp;
				</td>
			</tr>
		</table>
  <script type="text/javascript">
  	$(document).ready(function(){
  		if ($('input[name=\'filter_doc_num\']').val() != '') {
				$('input[name=\'filter_doc_num\']').css({'border':'1px red solid'});
			}
			
  		if ($('select[name=\'filter_doc_type\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_type\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_doc_status\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_status\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_doc_user\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_user\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_doc_partner_id\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_partner_id\']').css({'border':'1px red solid'});
			}


			$('input[name=\'filter_doc_num\']').keypress(function(event){
			  if (event.which == 13) {
			    event.preventDefault();
			    pflt_doc_filter();
			  }  
			});
			
		});
	</script>	
	</div>
<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="100">
		<col width="80">
		<col width="120">
		<col width="120">
		<col width="160">
		<col width="160">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th>Тип</th>
		<th>Номер</th>
		<th>Дата</th>
		<th>Дата за получаване</th>
		<th>Клиент</th>
		<th>Обслужва</th>
		<th>Действие</th>
	</tr>
	<?php if (count($documents)) { ?>
	<?php foreach ($documents as $d_id => $item) { 
	$box_title = $GLOBALS["DOC_TYPES"][$item["DType"]]."-".$item["DocNum"];

	$d_place = $GLOBALS["DELIVERY_PLACE"][$item["delivery_place"]];
	
	$opt = "";
	if(isset($item["doc_options"])) {
		if (count($item["doc_options"])) {
			foreach ($item["doc_options"] as $key => $val) {
				$opt .= $val["DExportData"];
				$opt .= "<br>".$val["Notes"];
			}
		}
	$opt .= '
	<table width="100%">
 	<tr>
 		<td colspan="2" style="background-color: #eee;">
<p style="padding: 5px;">
<strong><u>Срок за изработка:</u></strong><br>
	Срок за печат до '. $item["term_work"] .' след одобрение на проекта.

</p> 		
<p style="padding: 5px;">
<strong><u>Плащане:</u></strong><br>
Поръчката за печат се стартира след '.$item["Dpay_advance"] .' авансово плащане и при получаване на продукта – '. $item["Dpay_surcharge"] .'<br>
При използване на готови рекламни продукти същите се заплащат 100% авансово.
</p>

<p style="padding: 5px;">
<strong><u>Валидност на офертата:</u></strong><br>
20 дни от датата на издаването
</p>
<p style="padding: 5px;">
<strong><u>Цени:</u></strong><br>
<ul>
	<li>Всички посочени цени в настоящата оферта са 
	'.($item["vat_id"] == 2? 'с начислен ДДС':'без начислен ДДС').'
	, ако не е отбелязано друго.</li>
	<li>Цените в настоящата оферта важат само за посочените количества.</li>
	<li>Посочените в офертата цени не включват дизайн, предпечатна подготовка или обработка на файлове за печат, ако не е отбелязано друго.</li>
	<li>Плащанията се извършват в български лева (BGN) по официалния курс на БНБ за деня.</li>
	<li>АВ Дизайн Груп ДЗЗД си запазва правото на промяна в цената, ако оферираното не съответства на предоставения от клиента дизайн, вид печат, вид материали, или количество и причината за различието не е във фирма АВ Дизайн Груп ДЗЗД.</li>
</ul>
</p>
<p style="padding: 5px;">
<strong><u>Отклонения:</u></strong><br>
Възможни отклонения в крайното количество +2%/-2%, които се отбелязват в сумата при доплащане.
</p>
<p style="padding: 5px;">
<strong><u>Доставка:</u></strong><br>
	'.$GLOBALS["DELIVERY_PLACE"][$item["delivery_place"]].'
</p>

		</td>
 	</tr>
	</table>
	
	';	
	}
	
	$cards = "";
	if (isset($item["doc_cards"])) {
		if (count($item["doc_cards"])) {
			$cards .= "<b>Дейности</b>";
			foreach ($item["doc_cards"] as $key => $val) {
					$cards .= "
					<div>Потребител: ".$val["member_name"]." ".$val["member_family"]."</div>
					<div>".$val["card_notes"]."</div>
					<div>За Дата: ".$val["card_date"]." </div>
					";
			}
			$cards .= "<br><br>";
		}
	}
	$_suppliers = "";
	if (isset($item["doc_suppliers"])) {
		if (count($item["doc_suppliers"])) {
					$_suppliers .= "<b>Контрагенти</b>";
			foreach ($item["doc_suppliers"] as $key => $val) {
					$_suppliers .= "
					<div>Контрагент: ".$val["CName"]."</div>
					<div>".$val["CNotes"]."</div>
					<div>За Дата: ".$val["CDate"]."  Отговорен: ".$val["member_name"]." ".$val["member_family"]." Сума: ".number_format((float)$val["CSum"], 2)." лв.</div>
					";
			}
			$_suppliers .= "<br><br>";
		}
	}

	$box_content = "
	<br>
	<div><a class=\"print_doc\" href=\"".HTTP_SERVER."print/print-doc.php?id=".$d_id."\" target=\'_blank\'></a></div>
	<table width=\'600\' border=\'1\' cellspacing=\'0\' cellpadding=\'3\'>
	<tr>
	<td width=\'120\'><b>Статус</b></td>
	<td width=\'180\'>".$GLOBALS["ORDER_STATUS"][$item["DStatus"]]."</td>
	<td width=\'120\'><b>Тип</b></td><td width=\'180\'>".$GLOBALS["DOC_TYPES"][$item["DType"]]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Номер</b></td>
	<td width=\'180\'>".$item["DocNum"]."</td>
	<td width=\'120\'><b>Клиент</b></td>
	<td width=\'180\'>".$item["PName"]."</td>
	</tr>
	".($item["DType"] == DOC_TYPE_ORDER?
	"<tr>
	<td width=\'120\'><b>Дата</b></td>
	<td width=\'180\'>".$item["Docdate"]."</td>
	<td width=\'120\'><b>Плащане</b></td>
	<td width=\'180\'>".$GLOBALS["ORDER_PAYMENT"][$item["DPayment"]]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Дата за получаване</b></td>
	<td width=\'180\'>".$item["DocPeceiptDate"]."</td>
	<td width=\'120\'><b>Аванс</b></td><td width=\'180\'>".$item["DAdvance"]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>ТОТАЛ</b></td>
	<td width=\'180\'>".$item["DTotal"]."</td>
	<td width=\'120\'><b>Доплащане</b></td>
	<td width=\'180\'>".$item["DSurcharge"]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Корекции</b></td>
	<td width=\'180\'>".$item["DCorrections"]."</td>
	<td width=\'120\'><b>Обслужва</b></td>
	<td width=\'180\'>".$item["member_name"]." ".$item["member_family"]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Място за получаване</b></td>
	<td width=\'180\'>".$item["DCAddr"]."</td>
	<td width=\'120\'>&nbsp;</td><td width=\'180\'>&nbsp;</td>
	</tr>":
	"<tr>
	<td width=\'120\'><b>Дата</b></td>
	<td width=\'180\'>".$item["Docdate"]."</td>
	<td width=\'120\'><b>Обслужва</b></td><td width=\'180\'>".$item["member_name"]." ".$item["member_family"]."</td>
	</tr>
	")."
	
	<tr>
	<td colspan=4>".$opt."</td>
	</tr>
	".($item["DType"] == DOC_TYPE_ORDER?"
	<tr>
	<td colspan=4>".$cards."<br>".$_suppliers."</td>
	</tr>
	":"")."
	<tr>
	<td colspan=4>&nbsp;</td>
	</tr>
	</table>";
	$box_content = _clear($box_content);
	//<div><b>Тип </b> ".$GLOBALS["DOC_TYPES"][$item["DType"]]." <b>Номер </b> ".$item["DocNum"]."</div><div><b>Клиент </b> ".$item["member_name"]." ".$item["member_family"]." </div>";
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
	<tr>	
		<td align="center"><input type="checkbox"></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($GLOBALS["DOC_TYPES"][$item["DType"]]); ?></td>
		<td class="<?php echo $doc_class; ?>" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["DocNum"]); ?></td>
		<td style="cursor:pointer;" align="center" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["Docdate"]); ?></td>
		<td style="cursor:pointer;" align="center" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["DocPeceiptDate"]); ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["PName"]); ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["member_name"]." ".$item["member_family"]); ?></td>
		<td width="80" class="action">
			<a class="sadd" href="<?php echo $action_edit.$d_id.get_filter(); ?>"></a>
			<a class="sremove" href="javascript: void(0);" onclick="delete_settings('<?php echo $action_delete.$d_id.get_filter(); ?>');"></a>
		</td>
	</tr>
	
	<?php } ?>
	<?php } ?>
</table>
<div id="container">
<?php echo $styles; ?>
<?php echo $pagination; ?>
</div>


<!-- All Docs -->
<?php } else if ($page_tab == "not_payed") {?>

<!-- All Docs -->
	<div class="filter_box" style="display:none;">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td>Номер</td>
				<td><input type="text" name="filter_doc_num" class="ie120" value="<?php echo $pflt_filter["filter_doc_num"]; ?>"></td>
				<td>Тип</td>
				<td>
				<select name="filter_doc_type" class="ie120">
					<option value="*"> - Всички - </option>
					<?php if (count($doc_types)) { ?>
					<?php foreach ($doc_types as $id => $val) { ?>
					<option value="<?php echo $id; ?>" <?php echo($id == $pflt_filter["filter_doc_type"]? " selected":""); ?>> <?php echo $val;?> </option>
					<?php } ?>
					<?php } ?>
				</select>
				
				</td>
				<td>Статус</td>
				<td>
				<select name="filter_doc_status" class="ie120">
					<option value="*"> - Всички - </option>
					<?php if (count($order_status)) { ?>
					<?php foreach ($order_status as $id => $val) { ?>
					<option value="<?php echo $id; ?>" <?php echo ($id == $pflt_filter["filter_doc_status"]? " selected":""); ?>> <?php echo $val;?> </option>
					<?php } ?>
					<?php } ?>
				</select>
				</td>
				<td style="text-align: right;">
						<input type="button" name="button_filter" value="Филтър" class="submit_button button_add" onclick="pflt_doc_filter();">
						<input type="button" name="button_clear" value="X" class="submit_button button_delete" onclick="pflt_clear_filter('<?php echo $route; ?>');">
				</td>
			</tr>
			<tr>
				<td>Обслужва</td>
				<td colspan=5>
				<?php echo userSelect("filter_doc_user", "", $pflt_filter["filter_doc_user"], "", true); ?>
				
				Клиент 
				<select id="filter_doc_partner_id" name="filter_doc_partner_id"  class="ie240">
					<option value="*"> - Избери клиент - </option>
					<?php if (count($all_partners)) { ?>
					<?php foreach ($all_partners as $pid => $item) { ?>
					<option value="<?php echo $pid; ?>" <?php echo ($pid == $pflt_filter["filter_doc_partner_id"]? " selected":""); ?>> <?php echo $item["PName"];?> </option>
					<?php } ?>
					<?php } ?>
				</select>
				
				</td>
				<td colspan=2 align="center">
					
				&nbsp;
				</td>
			</tr>
		</table>
  <script type="text/javascript">
  	$(document).ready(function(){
  		if ($('input[name=\'filter_doc_num\']').val() != '') {
				$('input[name=\'filter_doc_num\']').css({'border':'1px red solid'});
			}
			
  		if ($('select[name=\'filter_doc_type\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_type\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_doc_status\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_status\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_doc_user\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_user\']').css({'border':'1px red solid'});
			}
  		if ($('select[name=\'filter_doc_partner_id\'] option:selected').val() != '*') {
				$('select[name=\'filter_doc_partner_id\']').css({'border':'1px red solid'});
			}


			$('input[name=\'filter_doc_num\']').keypress(function(event){
			  if (event.which == 13) {
			    event.preventDefault();
			    pflt_doc_filter();
			  }  
			});
			
		});
	</script>	
	</div>
<div style="display:none; background-color: rgb(255,102,102);padding: 8px 14px 8px 14px; float: right;color: #fff; font-weight: bold;">
	<?php 
	$NO_TOTAL = 0;

	foreach ($documents as $d_id => $item) {
		if ($item["doc_payed"]) $item["DAdvance"] = 0;
		if ($item["doc_payed2"]) $item["DSurcharge"] = 0;
		$NO_TOTAL += ($item["DAdvance"] + $item["DSurcharge"]);
	} 
	print bg_money($NO_TOTAL)." лв.";
	?>
</div>
	
<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="100">
		<col width="80">
		<col width="180">
		<col width="80">
		<col width="80">
		<col width="80">
		<col width="160">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th>Тип</th>
		<th>Номер</th>
		<th>Клиент</th>
		<th>Аванс</th>
		<th>Доплащане</th>
		<th>Тотал</th>
		<th>Обслужва</th>
		<th>Действие</th>
	</tr>
	<?php if (count($documents)) { ?>
	<?php foreach ($documents as $d_id => $item) { 
	$box_title = $GLOBALS["DOC_TYPES"][$item["DType"]]."-".$item["DocNum"];

	$d_place = $GLOBALS["DELIVERY_PLACE"][$item["delivery_place"]];
	
	$opt = "";
	if(isset($item["doc_options"])) {
		if (count($item["doc_options"])) {
			foreach ($item["doc_options"] as $key => $val) {
				$opt .= $val["DExportData"];
				$opt .= "<br>".$val["Notes"];
			}
		}
	$opt .= '
	<table width="100%">
 	<tr>
 		<td colspan="2" style="background-color: #eee;">
<p style="padding: 5px;">
<strong><u>Срок за изработка:</u></strong><br>
	Срок за печат до '. $item["term_work"] .' след одобрение на проекта.

</p> 		
<p style="padding: 5px;">
<strong><u>Плащане:</u></strong><br>
Поръчката за печат се стартира след '.$item["Dpay_advance"] .' авансово плащане и при получаване на продукта – '. $item["Dpay_surcharge"] .'<br>
При използване на готови рекламни продукти същите се заплащат 100% авансово.
</p>

<p style="padding: 5px;">
<strong><u>Валидност на офертата:</u></strong><br>
20 дни от датата на издаването
</p>
<p style="padding: 5px;">
<strong><u>Цени:</u></strong><br>
<ul>
	<li>Всички посочени цени в настоящата оферта са 
	'.($item["vat_id"] == 2? 'с начислен ДДС':'без начислен ДДС').'
	, ако не е отбелязано друго.</li>
	<li>Цените в настоящата оферта важат само за посочените количества.</li>
	<li>Посочените в офертата цени не включват дизайн, предпечатна подготовка или обработка на файлове за печат, ако не е отбелязано друго.</li>
	<li>Плащанията се извършват в български лева (BGN) по официалния курс на БНБ за деня.</li>
	<li>АВ Дизайн Груп ДЗЗД си запазва правото на промяна в цената, ако оферираното не съответства на предоставения от клиента дизайн, вид печат, вид материали, или количество и причината за различието не е във фирма АВ Дизайн Груп ДЗЗД.</li>
</ul>
</p>
<p style="padding: 5px;">
<strong><u>Отклонения:</u></strong><br>
Възможни отклонения в крайното количество +2%/-2%, които се отбелязват в сумата при доплащане.
</p>
<p style="padding: 5px;">
<strong><u>Доставка:</u></strong><br>
	'.$GLOBALS["DELIVERY_PLACE"][$item["delivery_place"]].'
</p>

		</td>
 	</tr>
	</table>
	
	';	
	}
	
	$cards = "";
	if (isset($item["doc_cards"])) {
		if (count($item["doc_cards"])) {
			$cards .= "<b>Дейности</b>";
			foreach ($item["doc_cards"] as $key => $val) {
					$cards .= "
					<div>Потребител: ".$val["member_name"]." ".$val["member_family"]."</div>
					<div>".$val["card_notes"]."</div>
					<div>За Дата: ".$val["card_date"]." </div>
					";
			}
			$cards .= "<br><br>";
		}
	}
	$_suppliers = "";
	if (isset($item["doc_suppliers"])) {
		if (count($item["doc_suppliers"])) {
					$_suppliers .= "<b>Контрагенти</b>";
			foreach ($item["doc_suppliers"] as $key => $val) {
					$_suppliers .= "
					<div>Контрагент: ".$val["CName"]."</div>
					<div>".$val["CNotes"]."</div>
					<div>За Дата: ".$val["CDate"]."  Отговорен: ".$val["member_name"]." ".$val["member_family"]." Сума: ".number_format((float)$val["CSum"], 2)." лв.</div>
					";
			}
			$_suppliers .= "<br><br>";
		}
	}

	$box_content = "
	<br>
	<div><a class=\"print_doc\" href=\"".HTTP_SERVER."print/print-doc.php?id=".$d_id."\" target=\'_blank\'></a></div>
	<table width=\'600\' border=\'1\' cellspacing=\'0\' cellpadding=\'3\'>
	<tr>
	<td width=\'120\'><b>Статус</b></td>
	<td width=\'180\'>".$GLOBALS["ORDER_STATUS"][$item["DStatus"]]."</td>
	<td width=\'120\'><b>Тип</b></td><td width=\'180\'>".$GLOBALS["DOC_TYPES"][$item["DType"]]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Номер</b></td>
	<td width=\'180\'>".$item["DocNum"]."</td>
	<td width=\'120\'><b>Клиент</b></td>
	<td width=\'180\'>".$item["PName"]."</td>
	</tr>
	".($item["DType"] == DOC_TYPE_ORDER?
	"<tr>
	<td width=\'120\'><b>Дата</b></td>
	<td width=\'180\'>".$item["Docdate"]."</td>
	<td width=\'120\'><b>Плащане</b></td>
	<td width=\'180\'>".$GLOBALS["ORDER_PAYMENT"][$item["DPayment"]]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Дата за получаване</b></td>
	<td width=\'180\'>".$item["DocPeceiptDate"]."</td>
	<td width=\'120\'><b>Аванс</b></td><td width=\'180\'>".$item["DAdvance"]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>ТОТАЛ</b></td>
	<td width=\'180\'>".$item["DTotal"]."</td>
	<td width=\'120\'><b>Доплащане</b></td>
	<td width=\'180\'>".$item["DSurcharge"]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Корекции</b></td>
	<td width=\'180\'>".$item["DCorrections"]."</td>
	<td width=\'120\'><b>Обслужва</b></td>
	<td width=\'180\'>".$item["member_name"]." ".$item["member_family"]."</td>
	</tr>
	<tr>
	<td width=\'120\'><b>Място за получаване</b></td>
	<td width=\'180\'>".$item["DCAddr"]."</td>
	<td width=\'120\'>&nbsp;</td><td width=\'180\'>&nbsp;</td>
	</tr>":
	"<tr>
	<td width=\'120\'><b>Дата</b></td>
	<td width=\'180\'>".$item["Docdate"]."</td>
	<td width=\'120\'><b>Обслужва</b></td><td width=\'180\'>".$item["member_name"]." ".$item["member_family"]."</td>
	</tr>
	")."
	
	<tr>
	<td colspan=4>".$opt."</td>
	</tr>
	".($item["DType"] == DOC_TYPE_ORDER?"
	<tr>
	<td colspan=4>".$cards."<br>".$_suppliers."</td>
	</tr>
	":"")."
	<tr>
	<td colspan=4>&nbsp;</td>
	</tr>
	</table>";
	$box_content = _clear($box_content);
	//<div><b>Тип </b> ".$GLOBALS["DOC_TYPES"][$item["DType"]]." <b>Номер </b> ".$item["DocNum"]."</div><div><b>Клиент </b> ".$item["member_name"]." ".$item["member_family"]." </div>";
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
		<td align="center"><input type="checkbox"></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($GLOBALS["DOC_TYPES"][$item["DType"]]); ?></td>
		<td class="<?php echo $doc_class; ?>" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["DocNum"]); ?></td>
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["PName"]); ?></td>
		
		<td class="<?php echo $pay1_class; ?>" style="cursor:pointer;" align="right" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes(bg_money($item["DAdvance"])); ?></td>
		<td class="<?php echo $pay2_class; ?>" style="cursor:pointer;" align="right" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes(bg_money($item["DSurcharge"])); ?></td>
		<td style="cursor:pointer;" align="right" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes(bg_money(($item["DAdvance"]+$item["DSurcharge"]))); ?></td>
		
		<td style="cursor:pointer;" onclick="setPopUpBox('<?php echo $box_title;?>', '<?php echo $box_content;?>');"><?php echo _fnes($item["member_name"]." ".$item["member_family"]); ?></td>
		<td width="80" class="action">
			<a class="sadd" href="<?php echo $action_edit.$d_id.get_filter(); ?>"></a>
			<a class="sremove" href="javascript: void(0);" onclick="delete_settings('<?php echo $action_delete.$d_id.get_filter(); ?>');"></a>
		</td>
	</tr>
	
	<?php } ?>
	<?php } ?>
</table>
<div id="container">
<?php echo $styles; ?>
<?php echo $pagination; ?>
</div>

<?php
} else if ($page_tab == "send_email") {
//printArray($email_info);
?>
<form action="<?php echo $action;?>" name="fEmail" method="post">
		<input type="hidden" name="email[From]" value="<?php echo $email_info['from']; ?>">
  
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="120">
					<col  width="580">
					<col>
				</colgroup>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><h1><?php echo $email_info['partner']; ?></h1></td>
				</tr>
				<tr>
					<td class="label">Получател:</td>
					<td><input type="text" name="email[To]" class="ie360" style="width:550px;" value="<?php echo $email_info['to']; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Относно:</td>
					<td><input type="text" name="email[Subject]" class="ie360" style="width:550px;" value="<?php echo $email_info['subject']; ?>"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label" valign="top">Съобщение:</td>
					<td><textarea name="email[content]" rows="10" class="ie360" style="width:550px;" ></textarea></td>
					<td>&nbsp;</td>
				</tr>
				<?php if ($doc_id) { ?>
				<tr>
					<td class="label">Оферта/Поръчка:</td>
					<td colspan=2>&nbsp; <div id="offer_togggle">[+]</div></td>
				</tr>
				<tr>
					<td colspan=3>
					<div id="view_offer">
					<?php echo $email_info['doc_print']; ?>
					<textarea name="email[doc_print]" rows="10" class="ie360" style="display: none;" ><?php echo $email_info['doc_print']; ?></textarea>
					<div>
					<style type="text/css">
      			div#nav_bar, div#view_offer {
							display: none;
						}
						div#offer_togggle {
							cursor: pointer;
							color: red;
							width: auto;
							float: left;
						}
     				</style>
     				<script type="text/javascript">
     					$(document).ready(function(){
     						$('div#offer_togggle').click(function(){
									if ($('div#view_offer').is(':visible')) {
										$('div#offer_togggle').html('[+]');
										$('div#view_offer').hide();
									} else {
										$('div#offer_togggle').html('[-]');
										$('div#view_offer').show();
									} 
								});
							});
     				</script>
					</td>
				</tr>
				<?php } ?>
				<tr class="line">
					<td colspan=3>&nbsp;</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td colspan="3">
						<input type="button" name="button_save" value="Изпрати" class="submit_button button_add" onclick="document.fEmail.submit();">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel.get_filter(); ?>'">
					</td>
				</tr>

		</table>
</form>
<?php

} else { ?>
<?php if ($DType == DOC_TYPE_ORDER){ ?>
<style type="text/css">
	.is_order {
		display: block;
	}
</style>
<?php } else { ?>
<style type="text/css">
	.is_order {
		display: none;
	}
</style>
<?php } ?>

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
					<select id="DStatus" name="doc[DStatus]" class="ie120">
						<?php if (count($order_status)) { ?>
						<?php foreach ($order_status as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["DStatus"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td class="label">Тип</td>
					<td>
					<select id="DType" name="doc[DType]" class="ie120" onchange="showOrderBox(this.value);">
						<?php if (count($doc_types)) { ?>
						<?php foreach ($doc_types as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["DType"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td class="label">Номер</td>
					<td><input type="text" name="doc[DocNum]" class="ie240" value="<?php echo $document["DocNum"]; ?>"></td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td class="label">Клиент</td>
					<td>
					   <input type="hidden" id="partner_id" name="doc[partner_id]" value="">
            <div class = "ui-widget">
               <input id="autocomplete-partner" class="ie240">
            </div>
      <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<?php 
		print "
		<script type=\"text/javascript\">
			var partners = [];
		";
		$js = array();
		foreach ($all_partners as $id =>$item) {
		  $name = str_replace("'", "", $item["PName"]);
		  $name = str_replace('"', "", $item["PName"]);
			$js[] = '{id: '.$id.', label: "'.$name.'", value: "'.$name.'"}';
		}
		
		if (count($js)) {
			print "partners = [".implode(", ", $js)."];";
		}
		print "
		</script>
		";
	?>      
      <script>
         $(function() {
            $( "#autocomplete-partner" ).autocomplete({
               source: partners,
               select: function( event, ui ) {
                  document.getElementById('partner_id').value = ui.item.id;
               }               
            });
         });
      </script>            
              <!--
						<select id="partner_id" name="doc[partner_id]"  class="ie240s">
							<option value=""> - Избери клиент - </option>
							<?php if (count($all_partners)) { ?>
							<?php foreach ($all_partners as $pid => $item) { ?>
							<option value="<?php echo $pid; ?>" <?php echo ($pid == $document["partner_id"]? " selected":""); ?>> <?php echo $item["PName"];?> </option>
							<?php } ?>
							<?php } ?>
						</select>
					-->
					</td>

				<td class="label is_order">Плащане</td>
				<td class="is_order">
					<select id="DPayment" name="doc[DPayment]" class="ie120">
						<option value=""> - Плащане - </option>
						<?php if (count($order_payment)) { ?>
						<?php foreach ($order_payment as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["DPayment"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
				</td>
					
				</tr>

				<tr>
					<td class="label">Дата</td>
					<td><input type="text" id="Docdate" name="doc[Docdate]" class="ie240" value="<?php echo $document["Docdate"]; ?>"></td>

					<td class="label is_order">Аванс</td>
					<td class="is_order"><input type="text" name="doc[DAdvance]" class="ie240" value="<?php echo $document["DAdvance"]; ?>"></td>
					<td class="is_order" style="background-color: #eeeeee;"><?php print input_cb('doc[doc_payed]', $document["doc_payed"], false, "doc_payed", "Платено"); ?></td>	
				</tr>

				<tr>
					<td class="label is_order">Дата за получаване</td>
					<td class="is_order"><input type="text" id="DocPeceiptDate" name="doc[DocPeceiptDate]" class="ie240" value="<?php echo $document["DocPeceiptDate"]; ?>"></td>

					<td class="label is_order">Доплащане</td>
					<td class="is_order"><input type="text" name="doc[DSurcharge]" class="ie240" value="<?php echo $document["DSurcharge"]; ?>"></td>
					<td  class="is_order" style="background-color: #eeeeee;"><?php print input_cb('doc[doc_payed2]', $document["doc_payed2"], false, "doc_payed2", "Платено"); ?></td>	
				</tr>

				<tr>
					<td class="label is_order">ТОТАЛ</td>
					<td class="is_order"><input type="text" name="doc[DTotal]" class="ie240" value="<?php echo $document["DTotal"]; ?>"></td>

					<td class="label is_order">Корекции</td>
					<td class="is_order"><input type="text" name="doc[DCorrections]" class="ie240" value="<?php echo $document["DCorrections"]; ?>"></td>
				</tr>

				<tr>
					<td class="label">Обслужва</td>
					<td>
					<?php echo userSelect("doc[uid]", "", $document["uid"]); ?>
					</td>

					<td class="label is_order">Място за получаване</td>
					<td class="is_order"><input type="text" name="doc[DCAddr]" class="ie240" value="<?php echo $document["DCAddr"]; ?>"></td>
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
							<?php if (count($document["doc_options"]) == 0) { ?>
							<tr id="calculator_1">
								<td class="label">Позиция 1</td>
								<td>
								<?php echo cSelect("doc[doc_options][1][c_id]", "c_id_1", '1'); ?>								
								</td>
								<td colspan=2>
								<input type="button" name="calc_load" value="Зареди" class="submit_button button_edit" onclick="view_dialog($('select#c_id_1 option:selected').val(), '1')">
								</td>
								<td>
								<a class="remove" href="javascript: void(0);" onclick="removeOption(1)"></a>
								</td>
							</tr>
							<tr id="data_1">
								<td colspan=5>
								<textarea id="c_export_1" name="doc[doc_options][1][DExportData]" rows="10" style="width:80%;display:none;"></textarea>
								<div id="c_export_view_1"></div>	
								</td>
							</tr>
							<tr id="notes_1">
								<td class="label">Забележки</td>
								<td colspan=3><input type="text" name="doc[doc_options][1][DNotes]" class="ie360" value=""> &nbsp;&nbsp;<b class="label">Тотал: </b><input type="text" name="doc[doc_options][1][PSum]" class="ie80" value=""></td>
							</tr>
							<?php } else {?>
							<?php foreach ($document["doc_options"] as $option_id => $item) { ?>
									<tr id="calculator_<?php echo $option_id; ?>">
										<td class="label">Позиция <?php echo $option_id; ?></td>
										<td>
										<?php echo cSelect("doc[doc_options][".$option_id."][c_id]", "c_id_".$option_id, $item["c_id"]); ?>
										</td>
										<td colspan=2>
										<input type="button" name="calc_load" value="Зареди" class="submit_button button_edit" onclick="view_dialog($('select#c_id_<?php echo $option_id; ?> option:selected').val(), '<?php echo $option_id; ?>')">
										</td>
										<td>
										<a class="remove" href="javascript: void(0);" onclick="removeOption(<?php echo $option_id; ?>)"></a>
										</td>
									</tr>
									<tr id="data_<?php echo $option_id; ?>">
										<td colspan=5>
										<textarea id="c_export_<?php echo $option_id; ?>" name="doc[doc_options][<?php echo $option_id; ?>][DExportData]" rows="10" style="width:80%;display:none;"><?php echo trim($item["DExportData"]); ?></textarea>
										<div id="c_export_view_<?php echo $option_id; ?>"><?php echo trim($item["DExportData"]); ?></div>	
										</td>
									</tr>
									<tr id="notes_<?php echo $option_id; ?>">
										<td class="label">Забележки</td>
										<td colspan=3><input type="text" name="doc[doc_options][<?php echo $option_id; ?>][DNotes]" class="ie360" value="<?php echo $item["DNotes"]; ?>"> &nbsp;&nbsp;<b class="label">Тотал: </b><input type="text" name="doc[doc_options][<?php echo $option_id; ?>][PSum]" class="ie80" value="<?php echo $item["PSum"]; ?>"></td>
									</tr>
					
							<?php }?>
							<?php }?>
						</table>
					</td>
				</tr>
<!-- Repeat items -->

				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>

				<tr>
					<td colspan=5>
					<div id="view_term" style="<?php echo (!empty($document["view_term"])? "display:block;":"display:none;"); ?>">
					<?php echo $GLOBALS["TERMS_HTML"]; ?>
					</div>
					</td>
				</tr>
				
				<tr>
					<td class="label">Срок за изработка</td>
					<td>
					<input id="term_work" type="text" name="doc[term_work]" class="ie240" value=" 7 работни дни">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Доставка</td>
					<td>
					<select id="delivery_place" name="doc[delivery_place]" class="ie240s">
						<?php if (count($delivery_place)) { ?>
						<?php foreach ($delivery_place as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["delivery_place"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">ДДС</td>
					<td>
					<select id="vat_id" name="doc[vat_id]" class="ie240s">
						<?php if (count($price_vat)) { ?>
						<?php foreach ($price_vat as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["vat_id"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td class="label">Плащане</td>
					<td colspan="2">
					Аванс &nbsp;
					<input id="Dpay_advance" type="text" name="doc[Dpay_advance]" class="ie60" value="50%">
					&nbsp;
					Доплащане &nbsp;
					<input id="Dpay_surcharge" type="text" name="doc[Dpay_surcharge]" class="ie60" value="50%">
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
					<td class="label">Покажи усковията</td>
					<td>
					<select id="view_term" name="doc[view_term]" class="ie120">
						<?php if (count($view_term)) { ?>
						<?php foreach ($view_term as $id => $val) { ?>
						<option value="<?php echo $id; ?>" <?php echo ($id == $document["view_term"]? " selected":""); ?>> <?php echo $val;?> </option>
						<?php } ?>
						<?php } ?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>


				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td colspan="4">
						<input type="button" name="add_position" value="Добави позиция" class="submit_button button_add" onclick="addOption();">

						<input type="button" name="button_save" value="Добави" class="submit_button button_add" onclick="validate_docs('<?php echo $action_save; ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel; ?>'">
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
var startOption = parseInt('<?php echo $index_Option; ?>');
function addOption() {
/*
"doc[doc_options][][c_id]"
"c_id_"
*/
	html = ''+
							'<tr id="calculator_'+startOption+'">'+
							'	<td class="label">Позиция '+startOption+'</td>'+
							'	<td>'+
							'<?php echo cSelect("doc[doc_options]['+startOption+'][c_id]", "c_id_'+startOption+'", 1); ?>'+
							'	</td>'+
							'	<td colspan=2>'+
							'	<input type="button" name="calc_load" value="Зареди" class="submit_button button_edit" onclick="view_dialog($(\'select#c_id_'+startOption+' option:selected\').val(), \''+startOption+'\')">'+
							'	</td>'+
							'	<td>'+
							'	<a class="remove" href="javascript: void(0);" onclick="removeOption(\''+startOption+'\')"></a>'+
							'	</td>'+

							'</tr>'+
							'<tr id="data_'+startOption+'">'+
							'	<td colspan=5>'+
							'	<textarea id="c_export_'+startOption+'" name="doc[doc_options]['+startOption+'][DExportData]" rows="10" style="width:80%;display:none;">'+
							'	</textarea>'+
							'<div id="c_export_view_'+startOption+'"></div>'+
							'	</td>'+
							'</tr>'+
							'<tr id="notes_'+startOption+'">'+
							'	<td class="label">Забележки</td>'+
							'	<td colspan=3><input type="text" name="doc[doc_options]['+startOption+'][DNotes]" class="ie360" value="">  &nbsp;&nbsp;<b class="label">Тотал: </b><input type="text" name="doc[doc_options]['+startOption+'][PSum]" class="ie80" value=""></td>'+
							'	</td>'+
							'</tr>';
		startOption++;					
		$('#doc_pos').append(html);
}

function removeOption(tr_id) {
	if (confirm ('Изтриване на Позиция '+tr_id+' !')) {
		$('#calculator_'+tr_id+'').remove();
		$('#data_'+tr_id+'').remove();
		$('#notes_'+tr_id+'').remove();
	}
}


function view_dialog(c_id, opt_id) {

	if (SYSTEM_CALC[c_id]['folder'] == '-') {
		msg_add('Избран е <b>Универсален Калкулатор</b>, шаблона ще бъде импортиран автоматично след натискане на експорт!', MSG_INFO);
	} else {
		var domain = '<?php echo HTTP_CALCULATOR;?>'+SYSTEM_CALC[c_id]['folder']+'/index.html?opt_id='+opt_id;
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		setIFramePopUpBox(''+SYSTEM_CALC[c_id]['name']+'', domain);
		
	}
}

function showOrderBox(DType) {
	if (DType == '<?php echo DOC_TYPE_ORDER; ?>') {
		$('.is_order').show();
		build_select('<?php echo DOC_TYPE_ORDER; ?>');
	} else {
		$('.is_order').hide();
		build_select('<?php echo DOC_TYPE_OFFER; ?>');
	}
}

function update_image(image_id) {
  setIFramePopUpBox('Мениджър Изображения', '<?php echo HTTP_SERVER."/file_manager/index.php?field=print_image_";?>'+image_id);
  $('#popup_box #popup_box_content').css({'width':'800px', 'height':'460px'});
  $('iframe#box_iframe').css({'width':'800px', 'height':'440px'});
/*
	setIFramePopUpBox('Мениджър Изображения', '<?php echo HTTP_SERVER."system/browse.php?image_id=";?>'+image_id);
	$('#popup_box #popup_box_content').css({'width':'650px', 'height':'400px'});
	$('iframe#box_iframe').css({'width':'640px', 'height':'380px'});
	*/
}



function build_select(type) {
	//$('select#DStatus option').remove();
	var select = $('select#DStatus');

	if(select.prop) {
		var options = select.prop('options');
	}else {
		var options = select.attr('options');
	}
	$('option', select).remove();
	
	
	if (type == '<?php echo DOC_TYPE_ORDER; ?>') {
		var newOptions = {
		    '3' : 'Активна',
		    '4' : 'Плиключила'
		};	
	} else {
	//$document["DStatus"]
		var newOptions = {
		    '1' : 'Нереализирана',
		    '2' : 'Реализирана'
		};	
	}
//alert('<?php echo $document["DStatus"]; ?>');
	var selectedOption = '<?php echo $document["DStatus"]; ?>';
	if (document.getElementById('DStatus') != null) {
		$.each(newOptions, function(val, text) {
		    options[options.length] = new Option(text, val);
		});
		select.val(selectedOption);	
	}
}
//view_term
$(document).ready(function(){
/**/

if ($('select#DType option:selected').val() == '<?php echo DOC_TYPE_ORDER; ?>') {
	build_select('<?php echo DOC_TYPE_ORDER; ?>');
} else {
	build_select('<?php echo DOC_TYPE_OFFER; ?>');
}
//DStatus
/**/
	$('span#w_days').html($('input#term_work').val());
	$('span#trm_advance').html($('input#Dpay_advance').val());
	$('span#trm_surcharge').html($('input#Dpay_surcharge').val());
	if ($('select#vat_id option:selected').val() == 2) {
		$('span#trm_vat').html('с начислен ДДС');
	} else {
		$('span#trm_vat').html('без начислен ДДС');
	}
	$('span#trm_delivery_place').html($('select#delivery_place option:selected').text());
	
  $('select#view_term').change(function(){
  	if($(this).val() == '1') {
  		$('span#w_days').html($('input#term_work').val());
  		$('span#trm_advance').html($('input#Dpay_advance').val());
  		$('span#trm_surcharge').html($('input#Dpay_surcharge').val());
  		if ($('select#vat_id option:selected').val() == 2) {
				$('span#trm_vat').html('с начислен ДДС');
			} else {
				$('span#trm_vat').html('без начислен ДДС');
			}
			$('span#trm_delivery_place').html($('select#delivery_place option:selected').text());
			$('div#view_term').show();
//		  $('textarea#DTermData').val($('div#view_term').html());
		} else {
  		$('span#w_days').html($('input#term_work').val());
  		$('span#trm_advance').html($('input#Dpay_advance').val());
  		$('span#trm_surcharge').html($('input#Dpay_surcharge').val());

  		if ($('select#vat_id option:selected').val() == 2) {
				$('span#trm_vat').html('с начислен ДДС');
			} else {
				$('span#trm_vat').html('без начислен ДДС');
			}
			$('span#trm_delivery_place').html($('select#delivery_place option:selected').text());

			$('div#view_term').hide();
//		  $('textarea#DTermData').val($('div#view_term').html());
		}
  });
//  $('textarea#DTermData').val($('div#view_term').html());
  //$('div#view_term').html()
});

</script>

<?php 
/*
printArray($_SESSION["CALCULATORS"]);

if(isset($_SESSION["CALCULATORS"])) {
	print "DA";
} else {
	print "NE";
}
*/
	if (count($messages)) {
		displayMessages($messages);
	}
?>
