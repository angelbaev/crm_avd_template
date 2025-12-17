<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">  
	<tr>	
		<th style="padding-top: 6px;padding-bottom: 6px;padding-left: 15px;text-align: left;">Забавено изпълнение</th>
	</tr>
	<tr>
		<td style="background-color: #fff;">

<table width="100%" class="list" border="1" cellspacing="0" cellpadding="1">
	<colgroup>
		<col width="100">
		<col width="160">
		<col width="80">
		<col width="120">
		<col width="160">
		<col width="180">
		<col width="160">
		<col width="60">
	</colgroup>
	<tr>	
		<th>No Поръчка</th>
		<th>Клиент</th>
		<th>Статус</th>
		<th>За дата</th>
		<th>Потребител</th>
		<th>Опсиание</th>
		<th>Контрагент</th>
		<th>Сума</th>
	</tr>
        <?php
//            $forgottenItems = array();
//            $cnt = 0;
//            foreach ($forgotenActivities as $key => $item) {
//               if (count($item["ITEM_A"])) {
//                   foreach ($item["ITEM_A"] as $k => $v) { 
//                      $v["card_status"] = $v["card_status"]?CARD_STATUS_F:CARD_STATUS_W;
//                      $v["is_activity"] = true;
//                      if (isForgotten($v)) {
//                          $forgottenItems[] = $v;
//                          $cnt++;
//                      }
//                   }
//               } 
//               if (count($item["ITEM_C"])) {
//                   foreach ($item["ITEM_C"] as $k => $v) { 
//                      $v["card_status"] = $v["card_status"]?CARD_STATUS_F:CARD_STATUS_W;
//                      $v["is_activity"] = false;
//                      if (isForgotten($v)) {
//                          $forgottenItems[] = $v;
//                          $cnt++;
//                      }
//                   }
//               }
//               
//               if ($cnt > 14) break;
//            }
        $cnt = 0;
        ?>
	<?php if (count($forgotenActivities)) { ?>
	<?php foreach ($forgotenActivities as $key => $item) { 
	?>
        <tr<?php echo ($item['is_forgotten_activity'] ? ' class="mark-as-forgotten"':''); ?>>	
                <td><?php echo _fnes($item["doc_num"]); ?></td>
                <td><?php echo _fnes($item["client"]); ?></td>
                <td align="center"><?php echo _fnes($GLOBALS["CARD_STATUS"][$item["status"]]); ?></td>
                <td align="center"><?php echo _fnes($item["activity_date"]); ?></td>
                <td><?php echo _fnes($item["user_fullname"]); ?></td>
                <td><?php echo _fnes(htmlspecialchars($item["note"])); ?></td>
                <td><?php echo _fnes($item["supplier_fullname"]); ?></td>
                <td align="right"><?php echo _fnes($item["supplier_sum"]); ?></td>
        </tr>	
        <?php
        $cnt++;
        if ($cnt > 14) {
            break;
        }
        ?>
        <?php } ?>
        <?php } ?>
</table>

		
		</td>
	</tr>
	<tr>	
		<th style="padding-top: 6px;padding-bottom: 6px;padding-left: 15px;text-align: left;">Предстоящи задачи</th>
	</tr>
	<tr>
		<td style="background-color: #fff;">

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
//	printArray($activities);
		$cnt_break = 1;
	?>
	<?php if (count($activities)) { ?>
	<?php foreach ($activities as $_doc_id => $item) { 

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
	
	<?php if (count((array)$item["ITEM_A"])) { ?>
		<tr>	
			<td align="center"><input type="checkbox"></td>
			<td colspan=9><b>Дейности</b></td>
		</tr>	
	<?php 
	foreach ($item["ITEM_A"] as $k => $v) { 
	$v["card_status"] = $v["card_status"]?CARD_STATUS_F:CARD_STATUS_W;
		if ($v["card_status"] == 0)
			$cnt_break++;
	?>
		<tr>	
			<td align="center"><input type="checkbox"></td>
			<td><?php echo _fnes($v["DocNum"]); ?></td>
			<td><?php echo _fnes($v["PName"]); ?></td>
			<td align="center"><?php echo _fnes($GLOBALS["CARD_STATUS"][$v["card_status"]]); ?></td>
			<td align="center"><?php echo _fnes($v["card_date"]); ?></td>
			<td><?php echo _fnes($v["fullName"]); ?></td>
			<td><?php echo _fnes(htmlspecialchars($v["card_notes"])); ?></td>
			<td>-</td>
			<td>-</td>
			<td width="60" class="action"><a class="sadd" href="<?php echo $action_edit.$_doc_id.get_filter(); ?>"></a></td>
		</tr>	
	<?php } ?>
	<?php } ?>
	<?php if (count($item["ITEM_C"])) {?>
		<tr>	
			<td align="center"><input type="checkbox"></td>
			<td colspan=9><b>Контрагенти</b></td>
		</tr>		
	<?php } ?>
	
	<?php foreach ($item["ITEM_C"] as $k => $v) { 
		$v["CStatus"] = $v["CStatus"]?CARD_STATUS_F:CARD_STATUS_W;
		if ($v["CStatus"] == 0)
			$cnt_break++;
	?>
		<tr>	
			<td align="center"><input type="checkbox"></td>
			<td><?php echo _fnes($v["DocNum"]); ?></td>
			<td><?php echo _fnes($v["PName"]); ?></td>
			<td align="center"><?php echo _fnes($GLOBALS["CARD_STATUS"][$v["CStatus"]]); ?></td>
			<td align="center"><?php echo _fnes($v["CDate"]); ?></td>
			<td><?php echo _fnes($v["fullName"]); ?></td>
			<td><?php echo _fnes(htmlspecialchars($v["CNotes"])); ?></td>
			<td><?php echo _fnes($v["CName"]); ?></td>
			<td align="right"><?php echo _fnes($v["CSum"]); ?></td>
			<td width="60" class="action"><a class="sadd" href="<?php echo $action_edit.$_doc_id.get_filter(); ?>"></a></td>
		</tr>	
	<?php } ?>
	
	<?php 
		if ($cnt_break == 10) break;
	?>
	
	<?php } ?>
	<?php } ?>
</table>

		
		</td>
	</tr>
</table>
<p>&nbsp;</p>
<ul id="dash_menu">
	<li><a href="<?php echo HTTP_SERVER."index.php?route=documents&tab=all&token=".get_token();?>" class="doc"><h1>Документи</h1></a></li>
	<li><a href="<?php echo HTTP_SERVER."index.php?route=reports&tab=patners&token=".get_token();?>" class="report"><h1>Справки</h1></a></li>
	<li><a href="<?php echo HTTP_SERVER."index.php?route=patners&tab=list&token=".get_token();?>" class="partner"><h1>Клиенти</h1></a></li>
	<li><a href="<?php echo HTTP_SERVER."index.php?route=contractors&tab=list&token=".get_token();?>" class="contractor"><h1>Контрагенти</h1></a></li>
	<li><a href="<?php echo HTTP_SERVER."index.php?route=activities&tab=all&token=".get_token();?>" class="activity"><h1>Дейности</h1></a></li>
	<li><a href="<?php echo HTTP_SERVER."index.php?route=payoffice&tab=list&token=".get_token();?>" class="payoffice"><h1>Каса</h1></a></li>
	<li><a href="<?php echo HTTP_SERVER."index.php?route=settings&tab=users&token=".get_token();?>" class="settings"><h1>Настройка</h1></a></li>
	<li><a href="<?php echo HTTP_SERVER."recovery/"; ?>" class="data"><h1>Данни</h1></a></li>
</ul>
<div class="clear"></div>