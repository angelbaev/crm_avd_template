<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <meta HTTP-EQUIV="refresh" CONTENT="10">
  <title>IFRAME</title>
  </head>
  <body>
     <?php
      ++$_SESSION['pcount'];
      print "<pre>";
      print_r($_SESSION);
      print "</pre>";
    ?>
  </body>
</html>
