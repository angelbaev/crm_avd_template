<?php 
  header('X-XSS-Protection:0');
  
	include("config.php");
 function VisitorIP()
    { 
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $TheIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else $TheIp=$_SERVER['REMOTE_ADDR'];
 
    return trim($TheIp);
    }
	/*
	if(VisitorIP() != '84.1.242.66') {
		session_destroy();
		msg_add("<h1>Система е заключена моля опитайте по късно!</h1>", MSG_INFO);
	}
*/
	include_once(DIR_SYSTEM."function.inc.php");
//	include_once(DIR_SYSTEM."errors.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");

		$route = (isset($_GET["route"])?$_GET["route"]:"dashboard"); //_g("route", "dashboard");
	if (is_login() && $route != 'login') {
		$page_tab = _g("tab", "home");
    OAL_Manager::getInstance()->run();
		include_once(DIR_CONTROLLER."controller.php");

		include_once(DIR_CONTROLLER."common".SLASH."header.php");
		$controller_header = new ControllerHeader();
		$controller_header->invoke();	
		/*Content begin */
		$className = _g("route", "dashboard");
		
		//TO DO check permmision befor loaded 
		if (canDoOnSystem($route)) {
			$controller_base = set_class($className);
			$controller_base->invoke();
		} else {
		//	print "Test";
			//msg_add("Нямате права за достъп!", MSG_ERROR);
		}
		/*Content end */
		include_once(DIR_CONTROLLER."common".SLASH."footer.php");
		$controller_header = new ControllerFooter();
		$controller_header->invoke();	
	} else {
		//print "Login";
		include_once(DIR_CONTROLLER."controller.php");
		include_once(DIR_CONTROLLER."common".SLASH."login.php");
		$controller_login = new ControllerLogin();
		$controller_login->invoke();	
	}
//	$_SESSION['uid'] = 6;
/*
	$GET_CLASS = "home";
	$db = "";

	include_once(DIR_CONTROLLER."Controller.php");

	$controller = new Controller();
	$controller->invoke();
*/
//print "pass: ".md5('123@q')."<Br>";
// print Errors 
if (count($GLOBALS["MSG"])){
	displayMessages($GLOBALS["MSG"]);
	//write log
		foreach($GLOBALS["MSG"] as $key => $item){
		  if($item["type"] == MSG_ERROR) 
		    avd_error_log($item["msg"]);
		}
	//avd_error_log();
}

?>
<?php if (DEBUG) { ?>
<div style="border: 1px #333 solid;">
	<div style="font-size: 1.2em;font-weight: bold;">DEBUGER</div>
<div style="border: 1px #ccc solid;">
<h2 style="font-size: 1em;font-weight: bold;"> GET VAR </h2>
<?php printArray($_GET);?> 
</div>
<div style="border: 1px #ccc solid;">
<h2 style="font-size: 1em;font-weight: bold;"> POST VAR </h2>
<?php printArray($_POST);?> 
</div>
<div style="border: 1px #ccc solid;">
<h2 style="font-size: 1em;font-weight: bold;"> SESSION VAR </h2>
<?php printArray($_SESSION);?> 
</div>
<div style="border: 1px #ccc solid;">
<h2 style="font-size: 1em;font-weight: bold;"> INCLUDED FILES </h2>
<?php
	$included_files = get_included_files();
	if (count($included_files)) {
		$n = 0;
		print "<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n";
		print "<tr><th>#</th><th>Files</th></tr>\n";
		foreach ($included_files as $file) {
			print "<tr><td>".(++$n)."</td><td>".$file."</td></tr>\n";
		}
		print "</table>\n";
	}
 ?> 
</div>

<div style="border: 1px #ccc solid;">
<h2 style="font-size: 1em;font-weight: bold;"> LAST QUERY </h2>
<?php echo $db->lastquery; ?> 
</div>



<div style="background-color: #c4c4c4; padding: 4px 8px 4px 16px;"><span style="color:red;">Page Loaded</span>: <b><?php echo round(getmicrotime()-$GLOBALS['PAGE_BEGIN'],4);?></b> sec.</div>
</div>
<?php } ?>