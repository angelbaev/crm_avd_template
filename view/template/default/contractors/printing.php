<div class="filter_box">
    <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="upload">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td>Файл</td>
				<td>
				<input type="file" name="file_excel" class="ie240" value="" accept="application/vnd.ms-excel">
				</td>
				<td style="text-align: right;">
						<input type="submit" name="upload_file" value="Качи"  class="submit_button button_add">
				</td>
			</tr>
		</table>
    </form>
</div>

<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<tr>	
		<th><input type="checkbox"></th>
		<th>Вид материал</th>
		<th>Размер</th>
		<th>Печат</th>
		<th>Тираж</th>
		<th>Вид хартия</th>
		<th>Размер хартия</th>
		<th>Макулатури</th>
		<th>Монтаж</th>
		<th>Печатница</th>
		<th>Забележка</th>
		<th>Действие</th>
	</tr>
	<?php if (count($printings)) { ?>
	<?php foreach ($printings as $id => $item) { ?>
		<tr>	
		<td><input type="checkbox"></td>
		<td><?php echo $item['material_type']; ?></td>
		<td><?php echo $item['size']; ?></td>
		<td><?php echo $item['print']; ?></td>
		<td><?php echo $item['drawing']; ?></td>
		<td><?php echo $item['paper_type']; ?></td>
		<td><?php echo $item['paper_size']; ?></td>
		<td><?php echo $item['waste']; ?></td>
		<td><?php echo $item['installation']; ?></td>
		<td><?php echo $item['printing_company']; ?></td>
		<td><?php echo $item['note']; ?></td>
		<td>-</td>
	</tr>

	<?php } ?>
	<?php } ?>

</table>