<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title>PAGE</title>
  </head>
  <body>
    <?php
      if(!isset($_SESSION['pcount'])) {
        $_SESSION['pcount'] = 0;
      }
      print "<pre>";
      print_r($_SESSION);
      print "</pre>";
      ++$_SESSION['pcount'];
    ?>
    <iframe src="iframe.php" height="1" width="1" style="display: none;"></iframe>
  </body>
</html>
