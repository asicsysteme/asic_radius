<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title><?php echo SYS_TITRE.' | '.MCfg::get('sys_desc').' | '.CLIENT_TITRE ?>.</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if(Mreq::tg('noscript') == "0" && Mreq::tg('_tsk') != 'errorjs' ) {
	    $error_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?_tsk=errorjs&noscript=1';
        ?>
	<noscript>
		<meta http-equiv="refresh" content="0;url=<?php echo $error_link ?>"/>
	</noscript>
	
<?php } ?>
        <?php require_once 'css.php'; ?>

		
		<!--inline styles related to this page-->
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
	

	<body class="no-skin">
