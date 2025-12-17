<?php
/*
  print "<pre>";
  print_r($_SESSION);
  print "</pre>";
 */ 
  $field = (isset($_GET['field'])?$_GET['field']:'');
 $HTTP_IMAGE = (isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].'/crm_avd/images/';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>File manager</title>
	<!-- 
	<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
	-->

	<!-- <script src="jquery/jquery-1.8.1.min.js" type="text/javascript" charset="utf-8"></script> -->
	<script src="jquery/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
	
	<!-- <script src="jquery/jquery-ui-1.8.21.custom.min.js"></script> -->
	<!-- <script src="jquery/jquery-ui-1.8.23.custom.min.js" type="text/javascript" charset="utf-8"></script> -->
	<!-- <link rel="stylesheet" href="jquery/ui-themes/smoothness/jquery-ui-1.8.23.custom.css" type="text/css" media="screen" title="no title" charset="utf-8"> -->
	
	<script src="jquery/jquery-ui-1.10.1.custom.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="jquery/ui-themes/smoothness/jquery-ui-1.10.1.custom.css" type="text/css" media="screen" title="no title" charset="utf-8">
	
	
	<!-- <link rel="stylesheet" href="jquery/ui-themes/smoothness/jquery-ui-1.8.21.custom.css" type="text/css"> -->

	
	<link rel="stylesheet" href="css/common.css"      type="text/css">
	<link rel="stylesheet" href="css/dialog.css"      type="text/css">
	<link rel="stylesheet" href="css/toolbar.css"     type="text/css">
	<link rel="stylesheet" href="css/navbar.css"      type="text/css">
	<link rel="stylesheet" href="css/statusbar.css"   type="text/css">
	<link rel="stylesheet" href="css/contextmenu.css" type="text/css">
	<link rel="stylesheet" href="css/cwd.css"         type="text/css">
	<link rel="stylesheet" href="css/quicklook.css"   type="text/css">
	<link rel="stylesheet" href="css/commands.css"    type="text/css">

	<link rel="stylesheet" href="css/fonts.css"       type="text/css">
	<link rel="stylesheet" href="css/theme.css"       type="text/css">

	<!-- elfinder core -->
	<script src="js/elFinder.js"></script>
	<script src="js/elFinder.version.js"></script>
	<script src="js/jquery.elfinder.js"></script>
	<script src="js/elFinder.resources.js"></script>
	<script src="js/elFinder.options.js"></script>
	<script src="js/elFinder.history.js"></script>
	<script src="js/elFinder.command.js"></script>

	<!-- elfinder ui -->
	<script src="js/ui/overlay.js"></script>
	<script src="js/ui/workzone.js"></script>
	<script src="js/ui/navbar.js"></script>
	<script src="js/ui/dialog.js"></script>
	<script src="js/ui/tree.js"></script>
	<script src="js/ui/cwd.js"></script>
	<script src="js/ui/toolbar.js"></script>
	<script src="js/ui/button.js"></script>
	<script src="js/ui/uploadButton.js"></script>
	<script src="js/ui/viewbutton.js"></script>
	<script src="js/ui/searchbutton.js"></script>
	<script src="js/ui/sortbutton.js"></script>
	<script src="js/ui/panel.js"></script>
	<script src="js/ui/contextmenu.js"></script>
	<script src="js/ui/path.js"></script>
	<script src="js/ui/stat.js"></script>
	<script src="js/ui/places.js"></script>

	<!-- elfinder commands -->
	<script src="js/commands/back.js"></script>
	<script src="js/commands/forward.js"></script>
	<script src="js/commands/reload.js"></script>
	<script src="js/commands/up.js"></script>
	<script src="js/commands/home.js"></script>
	<script src="js/commands/copy.js"></script>
	<script src="js/commands/cut.js"></script>
	<script src="js/commands/paste.js"></script>
	<script src="js/commands/open.js"></script>
	<script src="js/commands/rm.js"></script>
	<script src="js/commands/info.js"></script>
	<script src="js/commands/duplicate.js"></script>
	<script src="js/commands/rename.js"></script>
	<script src="js/commands/help.js"></script>
	<script src="js/commands/getfile.js"></script>
	<script src="js/commands/mkdir.js"></script>
	<script src="js/commands/mkfile.js"></script>
	<script src="js/commands/upload.js"></script>
	<script src="js/commands/download.js"></script>
	<script src="js/commands/edit.js"></script>
	<script src="js/commands/quicklook.js"></script>
	<script src="js/commands/quicklook.plugins.js"></script>
	<script src="js/commands/extract.js"></script>
	<script src="js/commands/archive.js"></script>
	<script src="js/commands/search.js"></script>
	<script src="js/commands/view.js"></script>
	<script src="js/commands/resize.js"></script>
	<script src="js/commands/sort.js"></script>	
	<script src="js/commands/netmount.js"></script>	

	<!-- elfinder languages -->
	<script src="js/i18n/elfinder.bg.js"></script>
	<script src="js/i18n/elfinder.en.js"></script>

	<!-- elfinder dialog -->
	<script src="js/jquery.dialogelfinder.js"></script>

	<!-- elfinder 1.x connector API support -->
	<script src="js/proxy/elFinderSupportVer1.js"></script>

	<!-- elfinder custom extenstions -->
	<script src="extensions/jplayer/elfinder.quicklook.jplayer.js"></script>
	<script>
		$(document).ready(function() {
			$('#finder').elfinder({
				url : 'php/connector.php',
				handlers : {
                    select : function(event, elfinderInstance) {
                        var selected = event.data.selected;
						
						if (selected.length) {
							// console.log(elfinderInstance.file(selected[0]))
						}
						
                    }
                },
				lang : 'bg',
        container: '<?php echo $field;?>',  
        dirimage: '<?php echo $HTTP_IMAGE; ?>',      
        getFileCallback: function (files, fm) {
         b = files.url.replace('<?php echo $HTTP_IMAGE; ?>','');
         b = b.replace('<?php echo $HTTP_IMAGE; ?>','');
         b = replaceAll(b, '%20', ' ');
         parent.$('#<?php echo $field; ?>').attr('src', '<?php echo $HTTP_IMAGE; ?>'+b);
/*
         parent.$('#<?php echo $field; ?>').attr('value', 'data/'+b);
         parent.$("#modal-dialog").modal('hide'); 
         parent.$('#modal-dialog').remove();
         */

        //url, key: path, :data/demo/cart.png
        //alert(files+', '+fm);
        
        /*
         b = a.replace('<?php echo $HTTP_IMAGE; ?>','');
         b = b.replace('<?php echo $HTTP_IMAGE; ?>','');
         b = replaceAll(b, '%20', ' ');
         parent.$('#<?php echo $field; ?>').attr('value', b);
         parent.$('#modal-dialog').dialog('close');
         parent.$('#modal-dialog').remove();
         */
         	   		
        }
				//customData : {answer : 42}
			})

		});
    
    function replaceAll(txt, replace, with_this) {
      return txt.replace(new RegExp(replace, 'g'),with_this);
    }     
	</script>
</head>
<body>
		<div id="finder">finder <span>here</span></div>

</body>
</html>
