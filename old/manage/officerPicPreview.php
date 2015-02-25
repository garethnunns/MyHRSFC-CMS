<?php
  if(isset($_GET['in']) && file_exists('/home/a6325779/public_html/img/profiles/thumbs/'.$_GET['in'].'.jpg')) {
    header('Content-Type: image/jpeg');

    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    echo file_get_contents('/home/a6325779/public_html/img/profiles/thumbs/'.$_GET['in'].'.jpg');
  }
?>