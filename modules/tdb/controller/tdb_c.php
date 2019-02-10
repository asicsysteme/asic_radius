
<?php 

if(!isset($_GET['noscript'])) {
$error_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?_tsk=error';
$error_link = str_replace("index.php","",$error_link);	
	?>
<noscript>
<meta http-equiv="refresh" content="0;url=<?php echo $error_link ?>"/>
</noscript>
<?php } ?>
<script type="text/javascript">
 var firsttime = 1;
</script>

				



