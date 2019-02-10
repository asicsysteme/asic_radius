<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title><?php echo SYS_TITRE.' | '.MCfg::get('sys_desc').' | '.CLIENT_TITRE ?> (Page de Connexion)</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if(Mreq::tg('noscript') == "0" && Mreq::tg('_tsk') != 'errorjs' ) {
	$error_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?_tsk=errorjs&noscript=1';
?>
	<noscript>
		<meta http-equiv="refresh" content="0;url=<?php echo $error_link ?>"/>
	</noscript>
	<?php } ?>
        <?php require_once 'css.php'; ?>


        <?php require_once 'jsall.php'; ?>

    </head>

<!-- styles -->

<body class="login-layout">
		<div class="main-container container-fluid">
			<div class="main-content">
				<div class="row-fluid">
					<div class="span12">
						<div class="login-container">
							<div class="row-fluid">
								<div class="center">
<!-- ==== load app here==== -->

<?php 

$execute_app = new MAjax();
$execute_app->is_appli = true;
$execute_app->default_app =  Mreq::tg('_tsk') != "0"?Mreq::tg('_tsk'):'login';
$execute_app->load();
//applic::load(1,0);


?>                           
                                </div><!--/position-relative-->
                            </div>
						</div>
					</div><!--/.span-->
				</div><!--/.row-fluid-->
			</div>
		</div><!--/.main-container-->
<!-- ======== -->

  </body>
</html>