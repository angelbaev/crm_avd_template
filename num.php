<?php
include 'config.php';
	include_once(DIR_SYSTEM."function.inc.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");
	
	function lwrite($msg) {
		$script_name = __FILE__;
		$time = @date('[d/M/Y:H:i:s]');
		$content = "".$time." (".$script_name.") ".$msg."\n";
		$fp = fopen(DIR_BASE."mysql_log.txt","a+");
		fputs($fp,$content); 
		fclose($fp);	
	}
	lwrite('test ala bala');
	
	/*
	$items = array();	

		$query = " select * from partners where (1) ".$flt." order by PName asc  ";//
		$db->prepare($query);
		if($db->query()) {
			while ($db->fetch()){
				$item = $db->Record;
				$items[] = $item;
			}
		} else {
			print ("DB Error: <br>".$db->Error."<br>");
		}
$n = 0;
foreach ($items as $key => $item) {
	print (++$n)." ".$item['PName']."<bR>";
}
*/
?>