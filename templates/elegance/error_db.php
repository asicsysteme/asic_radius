<?php
//Get MPATH THEME
define('THEME_PATH',MPATH_THEMES.Mcfg::get('theme'));
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title><?php echo MCfg::get('sys_titre').' | '.MCfg::get('sys_desc') ?> (Page de Debug)</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if(MReq::tg('noscript') == "0" && MReq::tg('_tsk') != 'errorjs' ) {
	$error_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?_tsk=errorjs&noscript=1';
?>
	<noscript>
		<meta http-equiv="refresh" content="0;url=<?php echo $error_link ?>"/>
	</noscript>
	<?php } ?>

        <?php require_once  'css.php';  ?>


        <?php //require_once 'jsall.php'; ?>

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
<div class="alert alert-danger">test avec %data_error%</div>
<!-- ==== load app here==== -->


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