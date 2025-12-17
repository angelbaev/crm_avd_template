<?php 

ini_set('allow_url_include', '1');
ini_set('allow_url_fopen', '1');

	include("../config.php");
	include_once(DIR_SYSTEM."function.inc.php");
	include_once(DIR_SYSTEM."db.mysql.php");
	include_once(DIR_SYSTEM."pagination.php");

	$image_id = _p("image_id","");
function getPrintContent($source) {
	$lines = "";
	preg_match_all("'<body>(.*?)</body>'si", $source, $match);
	foreach($match[1] as $val) { 
		$lines .=  $val;       
//		echo $val."<br>"; 
	//	return getAttribute("rel", $val);      
	}
	return $lines; 	
}//getPrintContent()

function add_no_photo($image_id, $params_url = "") {
	if (!empty($params_url)) {//!empty($params_url)
		$calcolator_dir = cleaner_url($params_url);
		$print_image = HTTP_IMAGE."no-photo.jpg";
	
		foreach ($GLOBALS["SYSTEM_CALC"] as $id => $item) {
			if ($item["FOLDER"] == $calcolator_dir) {
			   if (in_array($item["FOLDER"], array('banners', 'advertising_souvenirs', 'badges-label', 'rcalendar'))) {
            $print_image = findImage($item, $params_url);
         } else {
            $print_image = HTTP_IMAGE."print/".$item["IMAGE"];
         }

				
				break;
			}
		}
		return "<img id=\"print_image_".$image_id."\" src=\"".$print_image."\" width=\"210\" height=\"210\" border=\"0\" onclick=\"update_image('".$image_id."');\">";
	} else {
		return "<img id=\"print_image_".$image_id."\" src=\"".HTTP_IMAGE."no-photo.jpg\" width=\"210\" height=\"210\" border=\"0\" onclick=\"update_image('".$image_id."');\">";
	}
}
function cleaner_url($url) {
	$domain = HTTP_CALCULATOR;
	$base_url = preg_replace('/\\?.*/', '', $url);
	$result = str_replace("/print.php", "", $base_url);
	$result = str_replace($domain, "", $result);
	return $result;
}

function file_get_contents_curl($url) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);


    return $data;
}

function findImage($calculator, $url) {
    parse_str($url, $get_params);
    
    if (count($get_params)) {
      foreach($get_params as &$param) {
        $param = urldecode($param);
      }
    }
    
    if ($calculator["FOLDER"] == "banners" && isset($get_params["sys_banner_type"])) {
      if ($get_params["sys_banner_type"] == 2) {
        return HTTP_IMAGE."print/L-Baners.jpg";
      } else {
        return HTTP_IMAGE."print/roll-banner.jpg";
      }
    }

    if ($calculator["FOLDER"] == "advertising_souvenirs" && isset($get_params["sys_image_url"])) {
        
        if(empty($get_params["sys_image_url"])) {
            $get_params["sys_image_url"] = $calculator['FOLDER'] .'/images/' . $calculator['IMAGE'];
        }

      return "http://avdesigngroup.org/".$get_params["sys_image_url"];
    }
  
    if ($calculator["FOLDER"] == "badges-label" && isset($get_params["sys_paper_form"])) {
      if ($get_params["sys_paper_form"] == 5) {
        return HTTP_IMAGE."print/badges.jpg";
      }
    }
    
    if ($calculator["FOLDER"] == "rcalendar") {
      $images = array(
        'PRISMA' => '1.jpg',
        'STANDARD' => '2.jpg',
        'DELTA' => '3.jpg',
        'CLASSIC' => '4.jpg',
        'BUSINESS' => '5.jpg',
        'CLASSIC_MINI' => '6.jpg',
        'PRISMA_MINI' => '7.jpg',
        'STANDARD_MINI' => '8.jpg',
        'DELTA_MINI' => '9.jpg',
        'COMPACT' => '10.jpg',
        'LIGHT' => '11.jpg',
        'LUX' => '12.jpg',
        'ELITE' => '13.jpg',
      );

      return HTTP_CALCULATOR . "rcalendar/images/".$images[$get_params["type_id"]];
    }
    
    return HTTP_IMAGE."print/".$calculator["IMAGE"];
}

	$act = _p("act","");
	if ($act == "save") {
	/*
		$file_data = 'test: '.$_POST["calc_params"]; //file_get_contents($_POST["calc_params"]);
		$print_content = getPrintContent($file_data);
		$print_content = str_replace("<!--IMAGE-->", add_no_photo($image_id, $_POST["calc_params"]), $print_content);
		*/
		//print "POST: ".outArray($_POST);
		//print ($print_content);
		//print $_POST["calc_params"]; die();
        $file_data = file_get_contents_curl($_POST["calc_params"]);
        
//		$file_data = file_get_contents($_POST["calc_params"]);
	
		//$file_data = file_get_contents_curl($_POST["calc_params"]);
		$print_content = getPrintContent($file_data);
		$print_content = str_replace("<!--IMAGE-->", add_no_photo($image_id, $_POST["calc_params"]), $print_content);
		
		print ($print_content);
	} else {
		print "";
	}

?>
