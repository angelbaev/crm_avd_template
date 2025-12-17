<?php
define("DEFAULT_PAGEROWS", 14);

	class clsPagination {

		var $query;
		var $numRows = DEFAULT_PAGEROWS;//numRecs
		var $SESNAME;
		var $totalRows;
		var $page = 0;
		var $numPages;
		var $thispage;
		var $target;
		
		var $previous_button = true;
		var $next_button = true;
		var $first_button = true;
		var $last_button = true;
		
		function clsPagination($sName, $target = "") {
			$this->SESNAME = $sName;
			$this->thispage = $_SERVER["SCRIPT_NAME"];
			$this->target = $target.get_filter();
//			$this->page = (int) trim((isset($_POST["page"]) ? $_POST["page"] : (isset($_GET["page"])? $_GET["page"]: 0)));
			$this->calcPageRows();
		}//pageNavigator()
		
		function calcPageRows() {
			if (isset($_SESSION["PNAV"][$this->SESNAME]["PAGE_NUM"]))
				$this->page = $_SESSION["PNAV"][$this->SESNAME]["PAGE_NUM"];
			$this->page = (int) trim((isset($_POST["page"]) ? $_POST["page"] : (isset($_GET["page"])? $_GET["page"]: $this->page)));
			$_SESSION["PNAV"][$this->SESNAME]["PAGE_NUM"] = $this->page;
		}// calcPageRows()
		
		function updateCountItem($cnt) {
			$this->totalRows = $cnt;
		}//updateCountItem()
		
		function getLimit() {
			// calculate number of pages
			$this->numPages=ceil($this->totalRows/$this->numRows);
	        if(!preg_match("/^\d{1,2}$/",$this->page)||
	           $this->page<1||$this->page>$this->numPages){
	          $this->page=1;
	        }
			$limit = " LIMIT ".($this->page-1)*$this->numRows.",".$this->numRows;
			return $limit;
		} //getLimit()
		
		function displayPagination() {
			// create page links
			$html = 
				"<div class=\"cls_pagination\">\n".$this->displayPagination_From_To()." &nbsp;";
			$html .= " <div class=\"cls_wrapp_items\">\n";
			
			$start = $this->page * $this->numRows;
			$no_of_paginations = ceil($this->totalRows / $this->numRows);
			$cur_page = $this->page;
			
			/* ---------------Calculating the starting and endign values for the loop----------------------------------- */
			if ($cur_page >= 7) {
			    $start_loop = $cur_page - 3;
			    if ($no_of_paginations > $cur_page + 3)
			        $end_loop = $cur_page + 3;
			    else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
			        $start_loop = $no_of_paginations - 6;
			        $end_loop = $no_of_paginations;
			    } else {
			        $end_loop = $no_of_paginations;
			    }
			} else {
			    $start_loop = 1;
			    if ($no_of_paginations > 7)
			        $end_loop = 7;
			    else
			        $end_loop = $no_of_paginations;
			}
			
			$html .= "<ul>\n";	
		//	onclick=\"window.location.href='".$this->target."&page=".."'\"	
			// FOR ENABLING THE FIRST BUTTON
			if ($this->first_button && $cur_page > 1) {
			    $html .= "<li onclick=\"window.location.href='".$this->target."&page=1'\" class=\"active big_nav\">&laquo;</li>";
			} else if ($this->first_button) {
			    $html .= "<li onclick=\"window.location.href='".$this->target."&page=1'\" class=\"inactive big_nav\">&laquo;</li>";
			}

// FOR ENABLING THE PREVIOUS BUTTON
if ($this->previous_button && $cur_page > 1) {
    $pre = $cur_page - 1;
    
    $html .= "<li onclick=\"window.location.href='".$this->target."&page=".$pre."'\" class=\"active big_nav\">&lt;</li>";
} else if ($this->previous_button) {
    $html .= "<li class=\"inactive big_nav\">&lt;</li>";
}


for ($i = $start_loop; $i <= $end_loop; $i++) {
		
    if ($cur_page == $i)
        $html .= "<li onclick=\"window.location.href='".$this->target."&page=".$i."'\" style=\"color:#fff;background-color:#d65f05;\" class=\"active\">".$i."</li>";
    else
        $html .= "<li onclick=\"window.location.href='".$this->target."&page=".$i."'\" class=\"active\">".$i."</li>";
}

// TO ENABLE THE NEXT BUTTON
if ($this->next_button && $cur_page < $no_of_paginations) {
    $next = $cur_page + 1;
    $html .= "<li onclick=\"window.location.href='".$this->target."&page=".$next."'\" class=\"active big_nav\">&gt;</li>";
} else if ($this->next_button) {
    $html .= "<li class=\"inactive big_nav\">&gt;</li>";
}

// TO ENABLE THE END BUTTON
if ($this->last_button && $cur_page < $no_of_paginations) {

    $html .= "<li onclick=\"window.location.href='".$this->target."&page=".$no_of_paginations."'\" class=\"active big_nav\">&raquo;</li>";
} else if ($this->last_button) {
    $html .= "<li onclick=\"window.location.href='".$this->target."&page=".$no_of_paginations."'\" class=\"inactive big_nav\">&raquo;</li>";
}
				
			$html .= "</ul>\n";	
				/*
        // create previous link
        if($this->page>1){
          $html .= 
			 	"<a href=\"".$this->thispage."?page=".($this->page-1).$this->suburl."\">
				 « Previous
				 </a>\n";
        }

        // create numerated links
        for($i = 1; $i <= $this->numPages; $i++){
        		$html .= 
        			(($i != $this->page)?
					  "
					  	<a href=\"".$this->thispage."?page=".$i.$this->suburl."\" class=\"number\">
						  ".$i."
						</a>\n
						"
					  :
					  "
					  	<a href=\"#\" class=\"number current\">
						  ".$i."
						  </a>
					  "
					  );
        }

        // create next link
        if($this->page < $this->numPages){
          $html .= 
			 	"
				 <a href=\"".$this->thispage."?page=".($this->page+1)."\">
				 Next »
				 </a>\n";
        }
				*/
/*
				 <span style=\"border:1px #ccc solid;background-color:#eee;padding:2px;\">
				 Next»
				 </span>
*/

			$html .=" \n</div>\n";
/*
			$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
			$total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>$no_of_paginations</b></span>";
			$html .= $goto.$total_string;
			*/
        $html .= 
					"\n</div>\n";

        // return final output
        return $html;
			
		} //displayPagination()
		
		function displayPagination_From_To() {
			$html = 
				"<div style=\"width:80px;float:left;\"> <b>".$this->page."</b> îò <b>".$this->numPages."</b></div>\n";
			return $html;
		}//displayPagination_From_To()
		
		function displayPageNav() {
			$html =
				"&nbsp;".$this->displayNav()."&nbsp;";
			return $html;
		}
		
		function getStyles() {
			$style = '
				<style type="text/css">
				div.cls_pagination {
					border: 1px #333 solid;
					padding-top: 6px;
					padding-bottom: 12px;
					margin-top: 2px;
				}
				div.cls_wrapp_items {
					float:right;
					/*
					border: 1px red solid;
					margin-right: 10px;
					*/
				}

		.cls_wrapp_items ul li.inactive,
		.cls_wrapp_items ul li.inactive:hover{
		background-color:#ededed;
		color:#bababa;
		border:1px solid #bababa;
		cursor: default;
		}

		.cls_wrapp_items ul {
			margin: 0 0 0 0;
			padding: 0 0 0 0;
		}
		.cls_wrapp_items ul li{
		list-style: none;
		float: left;
		border: 1px solid #d65f05;
		padding: 2px 6px 2px 6px;
		margin: 0 3px 0 3px;
		font-family: Verdana;
		font-size: 14px;
		color: #d65f05;
		font-weight: bold;
		background-color: #f2f2f2;
		}
		.cls_wrapp_items ul li:hover{
		color: #fff;
		background-color: #d65f05;
		cursor: pointer;
		}
		.big_nav {
			font-weight: bolder;
			
		}
		
				
				</style>
			';
			
			return $style;
		}//getStyles()
		
		function getJavascript() {
		
		}//getJavascript()
	}

?>