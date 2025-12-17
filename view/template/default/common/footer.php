				</div>
				<!-- page form end-->
			</div>
		</div>
<?php if (isset($_SESSION["uid"]) && ($_SESSION["uid"] == 1 || $_SESSION["uid"] == 2)) {?>

<?php
	$_LOST_DATA = lost_data();
//	printArray($_LOST_DATA);
	$n_index = 0;
	if (count($_LOST_DATA)) {?>
?>
<style type="text/css">
	div#view_log {
		position: fixed;
		top: 140px;
		right: 0px;
		border: 1px #ddd solid;
		background-color: #ffffff;
	}
	div#view_log div.heading {
		padding: 6px 10px 6px 15px;
		border-bottom: 1px #666 solid;
		margin-bottom: 8px;
	}
	div#view_log div.heading h1{
		color: red;
		cursor: pointer;
		font-size: 10px;
			
	}
	div#view_log div.log_content {
		padding: 6px 6px 6px 6px;
		display: none;
		height: 400px;
		overflow: auto;
		background-color: #efefef;
	}
	
	div.a_view {
		font-weight: bold;
		cursor: pointer;
		border-bottom: 1px red solid;
		padding-bottom: 2px;
	}
	div.v_block {
		display: none;
		max-width: 400px;
		max-height: 300px;	
		overflow: auto;	
	}
	.cc {
		background-color: #ccc;
	}
	div#view_log a.edit {
		color: rgb(51,102,255);
		text-decoration: none;
	}
	div#view_log a.delete {
		color: red;
		text-decoration: none;
	}
	
</style>
<script type="text/javascript">
$(document).ready(function(){
  $("div#view_log div.heading h1").click(function(){
  	if ($('div#view_log div.log_content').is(':visible')) {
			$('div#view_log div.log_content').slideUp('slow');
		} else {
			$('div#view_log div.log_content').slideDown('slow');
		}
  });
}); 	

function view_post(id) {
	if ($('#blok_view_'+id).is(':visible')) {
		$('#blok_view_'+id).hide();
		$('#lview_'+id).html('ПРЕГЛЕД');
	} else {
		$('#blok_view_'+id).show();
		$('#lview_'+id).html('ЗАТВОРИ');
	}


}

function send_lostdata(l_id, a) {
			$.ajax({
				type : 'POST',
				url : 'ajax/loss-ajax.php',
				dataType : 'html',
				cache: false,
				data: {'l_id':l_id, 'act':a},
				success : function(data){
					if (data != 'Грешка при възтановяване!' || data != 'Грешка при изтриване!') {
						$('#tr_'+l_id).remove();
					}
					alert(data);
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Error: '+errorThrown, MSG_ERROR);
				}
			});
	
}
</script>

<div id="view_log">
	<div class="heading"><h1>Изгубени данни [Преглед]</h1></div>
	<div class="log_content">
	<table border="1" cellspacing="0" cellpadding="1">
		<colgroup>
			<col width="30">
			<col width="80">
			<col width="100">
			<col width="100">
			<col width="140">
			<col width="100">
			<col width="280">
			<col width="180">
			<col width="110">
   
		</colgroup>
  	<tr class="cc">
  		<th>#</th>
  		<th>Док.No</th>
  		<th>Страница</th>
  		<th>Потребител</th>
  		<th>Дата</th>
  		<th>Таблица</th>
  		<th>Изгубени данни</th>
  		<th>Изпратени данни</th>
  		<th>Действие</th>
  	</tr>
  	<?php foreach($_LOST_DATA as $l_id => $lval) {?>
  	<?php 
		$lval['lost_data'] = unserialize($lval['lost_data']);
		
		?>
  	<tr id="tr_<?php echo $l_id; ?>">
  		<td><?php echo (++$n_index); ?></td>
  		<td><?php echo $lval['DocNum']; ?></td>
  		<td><?php echo $lval['error_from']; ?></td>
  		<td><?php echo $lval['member_login']; ?></td>
  		<td><?php echo dt_convert($lval['lost_time'], DT_SQL, DT_BG, DT_DATETIME); ?></td>
  		<td><?php echo $lval['table_name']; ?></td>
  		<td>
			<?php if ($lval['table_name'] == 'supplier_items') { ?>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr class="cc">
						<th>Док.No</th>
						<th>Потребител</th>
						<th>Контрагент</th>
						<th>За дата</th>
						<th>Опсиание</th>
						<th>Сума</th>
						<th>Статус</th>
					</tr>
				<?php foreach($lval['lost_data'] as $key => $val){?>
					<tr>
						<td><?php echo $lval['DocNum']; ?></td>
						<td><?php echo _h(getUserById($val['uid'])); ?></td>
						<td><?php echo _h(getSupplierById($val['c_id'])); ?></td>
						<td><?php echo dt_convert($val['CDate'], DT_SQL, DT_BG, DT_DATE); ?></td>
						<td><?php echo _h($val['CNotes']); ?></td>
						<td><?php echo _h($val['CSum']); ?></td>
						<td><?php echo $GLOBALS["CARD_STATUS"][$val['CStatus']]; ?></td>
					</tr>
				<?php }?>
				</table>
			<?php } else {?>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr class="cc">
						<th>Док.No</th>
						<th>Потребител</th>
						<th>За дата</th>
						<th>Опсиание</th>
						<th>Статус</th>
					</tr>
				<?php foreach($lval['lost_data'] as $key => $val){?>
					<tr>
						<td><?php echo $lval['DocNum']; ?></td>
						<td><?php echo _h(getUserById($val['uid'])); ?></td>
						<td><?php echo dt_convert($val['card_date'], DT_SQL, DT_BG, DT_DATE); ?></td>
						<td><?php echo _h($val['card_notes']); ?></td>
						<td><?php echo $GLOBALS["CARD_STATUS"][$val['card_status']]; ?></td>
					</tr>
				<?php }?>
				</table>
			<?php }?>
			</td>
  		<td align="center">
  		<div class="a_view" onclick="view_post('<?php echo $n_index;?>')" id="lview_<?php echo $n_index;?>">ПРЕГЛЕД</div>
  		<div class="v_block" id="blok_view_<?php echo $n_index;?>">
			<?php 
				printArray(unserialize($lval['post_all']));
			 ?>
			</div>
			</td>
  		<td><a href="javascript: void(0);" onclick="send_lostdata('<?php echo $l_id; ?>', 'resolve')" class="edit">[Възтанови]</a>&nbsp;<a href="javascript: void(0);" class="delete" onclick="send_lostdata('<?php echo $l_id; ?>', 'delete')">[X]</a></td>
  	</tr>
  	<?php } ?>
 </table>
	</div>
</div>
<?php }?>		

<?php }?>
	<div id="__eloaded__"></div>
			
  </body>
</html>
