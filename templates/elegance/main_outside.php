<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 
require_once 'header.php';
require_once 'topbar.php';
//require_once 'leftbar.php';
require_once 'core.php';
require_once 'footer.php';
?>