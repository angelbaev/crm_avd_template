		<script type="text/javascript">
			$(function() {
				$.datepicker.setDefaults($.datepicker.regional['bg']);
				$( "#case_date" ).datepicker();
			});		
		</script>
<?php if ($product_id) { ?> 
<form action="<?php echo $action_edit_stock;?>" name="fEdit" method="post">
<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
		<colgroup>
			<col width="120">
			<col  width="280">
			<col width="120">
			<col  width="280">
			<col>
		</colgroup>

		<tr>
			<td class="label">Номер/Модел</td>
			<td colspan="4"><?php echo $product_stock["number"]; ?></td>
		</tr>
		<tr>
			<td class="label">Наименование</td>
			<td colspan="4"><?php echo $product_stock["name"]; ?></td>
		</tr>
		<tr>
			<td class="label">Количество</td>
			<td colspan="4"><?php echo $product_stock["quantity"]; ?> бр.</td>
		</tr>
		<tr>
			<td class="label">Тип</td>
			<td>
			  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <select class="ie240" name="stock[type]">
          <option value="0">ИЗТЕГЛЯНЕ</option>
          <option value="1">ДОСТАВКА</option>
        </select>
      </td>
			<td class="label">Количество</td>
			<td><input type="number" name="stock[quantity]" value="1" class="ie80" min="1"> бр.</td>
			<td>
			 <a class="add" href="javascript: void(0);" onclick="document.fEdit.submit();"></a>
			</td>
		</tr>
		<tr>
			<td class="label">Бележка</td>
			<td colspan="4"><textarea name="stock[note]" class="ie360"></textarea></td>
		</tr>
		<tr class="line">
			<td colspan=5>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5">
        <p>&nbsp;</p>	
        <h3>История </h3>
        <p>&nbsp;</p>	
        <table class="list" border="1" cellspacing="0" cellpadding="1">
        	<colgroup>
        		<col width="180"><!--Дата-->
        		<col width="110"><!--Тип-->
        		<col width="110"><!--Количество-->
        		<col width="450"><!--Бележка-->
        	</colgroup>
        	<tr>	
        		<th>Дата</th>
        		<th>Тип</th>
        		<th>Количество</th>
        		<th>Бележка</th>
        	</tr>
        	<?php if (count($product_stock_history)) {?>
        	<?php foreach ($product_stock_history as $key => $item) {?>
        	<tr>	
        		<td align="center"><?php echo _fnes($item["date_order"]);?></td>
        		<td align="left"><?php echo (empty($item["type"])? '<span style="color: red;">ИЗТЕГЛЯНЕ</span>':'<span style="color: green;">ДОСТАВКА</span>');?></td>
        		<td align="right"><?php echo _fnes($item["quantity"]);?> бр.</td>
        		<td align="right"><?php echo _fnes($item["note"]);?></td>
        	</tr>
        	
        	<?php }?>
        	<?php }?>
        </table>
			</td>
		</tr>

</table>
</form>
<?php } else { ?>
<form action="<?php echo $action_new;?>" name="fEdit" method="post">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="120">
					<col  width="280">
					<col width="120">
					<col  width="280">
					<col>
				</colgroup>

				<tr>
					<td class="label">Номер/Модел</td>
					<td colspan="4"><input type="text" name="stock[number]" class="ie240"value=""></td>
				</tr>
				<tr>
					<td class="label">Наименование</td>
					<td colspan="4"><input type="text" id="product_name" name="stock[name]" class="ie360" style="width:520px;" value=""></td>
				</tr>
				<tr>
					<td class="label">Количество</td>
					<td colspan="4"><input type="text" id="product_quantity" name="stock[quantity]" class="ie80" value=""> бр.</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="4">
						<input type="button" name="button_save" value="Добави" class="submit_button button_add" onclick="validate_product('<?php echo $action_new.get_filter(); ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action.get_filter(); ?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=5>&nbsp;</td>
				</tr>
		</table>
</form>
<?php } ?>