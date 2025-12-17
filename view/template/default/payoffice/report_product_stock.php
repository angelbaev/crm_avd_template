		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="120">
					<col  width="280">
					<col width="120">
					<col  width="280">
					<col>
				</colgroup>
				<tr>
					<td>&nbsp;</td>
					<td colspan="4" align="right">
						<input type="button" name="button_save" value="Добави продукт" class="submit_button button_add" onclick="window.location='<?php echo $action_new; ?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
		</table>
<p>&nbsp;</p>	
<table class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="180"><!--Номер-->
		<col width="450"><!--Наименование-->
		<col width="110"><!--Количество-->
		<col width="60"><!--Действие-->
	</colgroup>
	<tr>	
		<th>Номер</th>
		<th>Наименование</th>
		<th>Количество</th>
		<th>Действие</th>
	</tr>
	<?php if (count($report_product_stock)) {?>
	<?php foreach ($report_product_stock as $key => $item) {?>
	<tr>	
		<td align="center"><?php echo _fnes($item["number"]);?></td>
		<td align="left"><?php echo _fnes($item["name"]);?></td>
		<td align="right"><?php echo _fnes((int)$item["quantity"]);?> бр.</td>
		<td width="80" class="action">
		<a class="sadd" href="<?php echo $action_edit_stock.$key; ?>"></a>
		<?php if(canDoOnDelete()) { ?>
		<a class="sremove" href="javascript: void(0)" onclick="delete_settings('<?php echo $action_delete_stock.$key.get_filter(); ?>');"></a>
		<?php } ?>
		</td>
	</tr>
	
	<?php }?>
	<?php }?>
</table>
