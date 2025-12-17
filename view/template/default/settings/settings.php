<?php if ($page_tab == "roles") { ?>
<?php switch ($act) {
				case "update":
				case "edit":
				case "cancel":
?>
<form action="<?php echo $action;?>" name="fEdit" method="post">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="180">
					<col  width="360">
					<col>
				</colgroup>
				<tr>
					<td class="label">Наименование</td>
					<td><input type="text" name="role[role_name]" class="ie240" value="<?php echo $role["role_name"]; ?>"></td>
				</tr>
				<tr>
					<td class="label">Описание</td>
					<td><input type="text" name="role[role_description]" class="ie240" value="<?php echo $role["role_description"]; ?>"></td>
				</tr>
				<tr>
					<td class="label">Права за изтриване</td>
					<td><?php echo input_cb("role[role_can_del]", $role["role_can_del"]) ?> </td>
				</tr>
				<tr class="line">
					<td colspan=3>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2">
						<input type="button" name="button_save" value="<?php echo ($role_id? "Запис":"Добави");?>" class="submit_button <?php echo ($role_id? "button_edit":"button_add");?>" onclick="update_settings('<?php echo $action_save; ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel; ?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=3>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=3><?php echo get_record_info("roles", "role_id", $role_id); ?></td>
				</tr>
		</table>

</form>
<?php 				
				break;
				case "delete":
				case "list":
				default:
?>
<?php
?>
<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="180">
		<col width="180">
		<col width="80">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th>Наименование</th>
		<th>Описание</th>
		<th>Изтриване</th>
		<th>Действие</th>
	</tr>
	<?php if (count($roles)) { ?>
	<?php foreach ($roles as $id => $item) { ?>
	<tr>	
		<td align="center"><input type="checkbox"></td>
		<td><?php echo $item["role_name"]; ?></td>
		<td><?php echo $item["role_description"]; ?></td>
		<td align="center"><?php echo (!empty($item["role_can_del"])?"Да":"Не"); ?></td>
		<td width="80" class="action" style="width:80px;">
		<a class="sadd" href="<?php echo $action_edit.$id;?>"></a>&nbsp;
		<a class="sremove" href="javascript: void(0)" onclick="delete_settings('<?php echo $action_delete.$id;?>')"></a></td>
	</tr>
	<?php } ?>
	
	<?php } else { ?>
	<tr>	
		<td align="center" colspan="4"><b>Няма добавени роли!</b></td>
	</tr>
	<?php } ?>
	
	
	
</table>
<div id="container">
<?php echo $styles; ?>
<?php echo $pagination; ?>
</div>
<?php 
	break;
}
?>



<?php } else if ($page_tab == "perms") { ?>
<form action="<?php echo $action;?>" name="fEdit" method="post">
<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="100">
		<col width="100">
		<col width="100">
		<col width="100">
		<col width="100">
		<col width="100">
		<col width="100">
		<col width="100">
	</colgroup>
	<tr>	
		<th>Вид потребител</th>
		<th>Документи</th>
		<th>Справки</th>
		<th>Клиенти</th>
		<th>Контрагенти</th>
		<th>Дейности</th>
		<th>Каса</th>
		<th>Настройки</th>
	</tr>
	
	<?php if (count($perms)) { ?>
	<?php foreach ($perms as $id => $item) { ?>
	<tr>	
		<td align="center"><b><?php echo $item["role_name"];?></b> <input type="hidden" name="perms[<?php echo $id; ?>][perm_id]" value="<?php echo $item["perm_id"]; ?>"></td>
		<td align="center"><?php echo input_cb("perms[".$id."][documents]", $item["documents"]) ?> </td>
		<td align="center"><?php echo input_cb("perms[".$id."][reports]", $item["reports"]) ?> </td>
		<td align="center"><?php echo input_cb("perms[".$id."][patners]", $item["patners"]) ?> </td>
		<td align="center"><?php echo input_cb("perms[".$id."][contractors]", $item["contractors"]) ?> </td>
		<td align="center"><?php echo input_cb("perms[".$id."][activities]", $item["activities"]) ?> </td>
		<td align="center"><?php echo input_cb("perms[".$id."][payoffice]", $item["payoffice"]) ?> </td>
		<td align="center"><?php echo input_cb("perms[".$id."][settings]", $item["settings"]) ?> </td>
	</tr>
	<?php } ?>
	
	<?php } else { ?>
	<tr>	
		<td align="center" colspan="6"><b>Няма добавени права за достъп!</b></td>
	</tr>
	<?php } ?>
	
</table>

		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="180">
					<col  width="360">
					<col>
				</colgroup>

				<tr>
					<td colspan="3" align="center">
						<input type="button" name="button_save" value="Добави" class="submit_button button_add" onclick="update_settings('<?php echo $action_save; ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel; ?>'">
					</td>
				</tr>
		</table>
</form>

<?php /*printArray($perms);*/ ?>

<?php } else { ?>
<?php switch ($act) {
				case "update":
				case "edit":
				case "cancel":
?>
<form action="<?php echo $action;?>" name="fEdit" method="post">
		<table width="100%" class="form" border="0" cellspacing="0" cellpadding="2">
				<colgroup>
					<col width="180">
					<col  width="360">
					<col>
				</colgroup>
				<tr>
					<td class="label">Име</td>
					<td><input type="text" name="member[member_name]" class="ie240" value="<?php echo $member["member_name"]; ?>"></td>
				</tr>
				<tr>
					<td class="label">Фамилия</td>
					<td><input type="text" name="member[member_family]" class="ie240" value="<?php echo $member["member_family"]; ?>"></td>
				</tr>
				<tr>
					<td class="label">Роля</td>
					<td>
					<select name="member[role_id]" id="role_id" class="ie240s">
						<option value=""> - Избери роля - </option>
						<?php foreach ($roles as $role_id => $item) { ?>
							<option value="<?php echo $role_id; ?>" <?php echo ($role_id == $member["role_id"]? "selected":""); ?>> <?php echo $item['role_name']; ?> </option>
						<?php } ?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="label">Инициали</td>
					<td><input type="text" name="member[member_inc]" class="ie60" value="<?php echo $member["member_inc"]; ?>"></td>
				</tr>
				<tr>
					<td class="label">Статус</td>
					<td>
					<select name="member[status]" id="status" class="ie240s">
						<?php foreach ($statuses as $status_id => $name) { ?>
							<option value="<?php echo $status_id; ?>" <?php echo ($status_id == $member["status"]? "selected":""); ?>> <?php echo $name; ?> </option>
						<?php } ?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="label">Забавено изпълнение</td>
					<td>
					<select name="member[show_forgotten_activity]" id="show_forgotten_activity" class="ie240s">
						<?php foreach ($show_forgotten_activities as $show_forgotten_activity_id => $name) { ?>
							<option value="<?php echo $show_forgotten_activity_id; ?>" <?php echo ($show_forgotten_activity_id == $member["show_forgotten_activity"]? "selected":""); ?>> <?php echo $name; ?> </option>
						<?php } ?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="label">Потребителско име</td>
					<td><input type="text" id="login" name="member[member_login]" class="ie240" value="<?php echo $member["member_login"]; ?>" <?php echo($member_id? "readonly=\"readonly\"":""); ?>></td>
				</tr>
				<tr>
					<td class="label">Парола</td>
					<td><input type="password" id="pass" name="member[member_password]" class="ie240" value=""></td>
				</tr>
				<tr>
					<td class="label">Потвърди</td>
					<td><input type="password" id="re_pass" name="member[re_member_password]" class="ie240" value=""></td>
				</tr>
				<tr class="line">
					<td colspan=3>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2">
						<input type="button" name="button_save" value="<?php echo ($member_id? "Запис":"Добави");?>" class="submit_button <?php echo ($member_id? "button_edit":"button_add");?>" onclick="check_user_form('<?php echo $action_save; ?>');">
						<input type="button" name="button_save" value="Отказ" class="submit_button button_cancel" onclick="window.location='<?php echo $action_cancel; ?>'">
					</td>
				</tr>
				<tr class="line">
					<td colspan=3>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=3><?php echo get_record_info("members", "uid", $member_id); ?></td>
				</tr>
		</table>

</form>
<?php 				
				break;
				case "delete":
				case "list":
				default:
?>
<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="30">
		<col width="120">
		<col width="200">
		<col width="120">
		<col width="60">
		<col width="60">
		<col width="60">
		<col width="60">
	</colgroup>
	<tr>	
		<th><input type="checkbox"></th>
		<th><input type="checkbox"> Потребителско име</th>
		<th>Име и Фамилия</th>
		<th>Роля</th>
		<th>Инициали</th>
		<th>Статус</th>
		<th>З/И</th>
		<th>Действие</th>
	</tr>
	<?php if (count($members)) { ?>
	<?php foreach ($members as $id => $item) { ?>
	<tr>	
		<td align="center"><input type="checkbox"></td>
		<td align="center"><?php echo $item["member_login"]; ?></td>
		<td><?php echo $item["member_name"]; ?> <?php echo $item["member_family"]; ?></td>
		<td><?php echo $item["role_name"]; ?></td>
		<td align="center"><?php echo $item["member_inc"]; ?></td>
		<td align="center"><?php echo (!empty($item["status"])? "Активен" : "Неактивен"); ?></td>
		<td align="center"><?php echo (!empty($item["show_forgotten_activity"])? "Да" : "Не"); ?></td>
		<td width="80" class="action" style="width:80px;">
		<a class="sadd" href="<?php echo $action_edit.$id;?>"></a>&nbsp;
		<a class="sremove" href="javascript: void(0)" onclick="delete_settings('<?php echo $action_delete.$id;?>')"></a></td>
	</tr>
	<?php } ?>
	
	<?php } else { ?>
	<tr>	
		<td align="center" colspan="7"><b>Няма добавени потребители!</b></td>
	</tr>
	<?php } ?>
	
	
	
</table>
<div id="container">
<?php echo $styles; ?>
<?php echo $pagination; ?>
</div>
<?php 
	break;
}
?>







<?php } ?>
				<?php 
					if (count($messages)) {
						displayMessages($messages);
					}
				?>
