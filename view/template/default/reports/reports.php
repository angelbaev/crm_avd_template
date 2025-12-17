<?php if ($page_tab == "users") { ?>
<!-- Partner Report begin -->
<?php include_once("report_user.php"); ?>
<!-- Partner Report end -->
<?php } else if ($page_tab == "suppliers") {?>
<!-- Suppliers Report begin -->
<?php include_once("report_suppliers.php"); ?>
<!-- Suppliers Report end -->
<?php } else if ($page_tab == "category") {?>
<!-- Category Report begin -->
<?php include_once("report_category.php"); ?>
<!-- Category Report end -->
<?php } else if ($page_tab == "timetracker") {?>
<!-- Timetracker Report begin -->
<?php include_once("report_timetracker.php"); ?>
<!-- Timetracker Report end -->
<?php } else if ($page_tab == "not_payed") {?>
<!-- Not Payed Report begin -->
<?php include_once("report_not_payed.php"); ?>
<!-- Not Payed Report end -->
<?php } else if ($page_tab == "stock") {?>

<?php } else { ?>
<!-- Partner Report begin -->
<?php include_once("report_partner.php"); ?>
<!-- Partner Report end -->
<?php } ?>

<?php 
	if (count($messages)) {
		displayMessages($messages);
	}
?>
