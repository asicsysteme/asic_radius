<?php 
$service  = session::get('service');
$usrid    = session::get('userid');
$photo    = session::get('tof');
//exit($photo);
$username = session::get('username');
global $db;
?>
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar navbar-default navbar-fixed-top">
	<script type="text/javascript">
	//try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	</script>

	<div class="navbar-container" id="navbar-container">
		<!-- #section:basics/sidebar.mobile.toggle -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<!-- /section:basics/sidebar.mobile.toggle -->
		<div class="navbar-header pull-left">
			<!-- #section:basics/navbar.layout.brand -->
			<a href="#" class="navbar-brand">
				<small>
					<img  src="img/<?php echo MCfg::get('logo') ?>" alt="SYS" title="<?php echo SYS_TITRE.' | '.MCfg::get('sys_desc').' | '.CLIENT_TITRE ?>" height="25" width="100" />
					<?php echo SYS_TITRE;?>
				</small>
			</a>

			<!-- /section:basics/navbar.layout.brand -->

			<!-- #section:basics/navbar.toggle -->

			<!-- /section:basics/navbar.toggle -->
		</div>

		<!-- #section:basics/navbar.dropdown -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<?php include 'notif_v.php'; ?>
				<!-- #section:basics/navbar.user_menu -->
				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<img class="nav-user-photo" src="<?php echo $photo; ?>" alt="<?php echo $username; ?>" title="<?php echo $username; ?>" />
						<span class="user-info">
							<small>Bienvenue,</br><strong><?php echo $username; ?></strong></small>

						</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
						<li>
							<a href="#" class="this_url" rel="setting">
								<i class="ace-icon fa fa-cog"></i>
								Paramétres
							</a>
						</li>

						<li>

							<a href="#" class="this_url" data="<?php echo MInit::crypt_tp('id', session::get('userid'))?>" rel="compte">
								<i class="ace-icon fa fa-user"></i>
								Compte
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="./?_tsk=logout">
								<i class="ace-icon fa fa-power-off"></i>
								Déconnexion
							</a>
						</li>
					</ul>
				</li>
				<!-- /section:basics/navbar.user_menu -->
			</ul>
		</div>

		<!-- /section:basics/navbar.dropdown -->
	</div><!-- /.navbar-container -->
</div>

       <!--  <div class="navbar navbar-fixed-top">
       			<div class="navbar-inner">
       				<div class="container-fluid">
       					<a href="#" class="brand">
       						<small>
       							<img  src="img/logo.png" alt="MRN_COMPTA" title="MRN_COMPTA" height="25" width="100" />
       							Comptabilité
       						</small>
       					</a>/.brand
       
       					<ul class="nav ace-nav pull-right">
       						
                       <li class="purple">
       							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
       								<i class="icon-bell-alt"></i>
       								<span class="badge badge-important notiftt">8</span>
       							</a>
       
       							<ul id="ulnotif" class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-closer">
       								<li class="nav-header">
       									<i class="icon-warning-sign"></i>
       									8 Notifications
       								</li>
       
       								
       
       								
       
       								
       
       							</ul>
       						</li>  
       						<li class="light-blue">
       							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
       								<img class="nav-user-photo" src=" avatars/user.jpg" alt="Jason's Photo" />
       								<span class="user-info">
       								<small>Bienvenue,</br><strong><?php echo session::get('username'); ?></strong></small>
       									
       								</span>
       
       								<i class="icon-caret-down"></i>
       							</a>
       
       							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
       								<li>
       									<a href="#">
       										<i class="icon-cog"></i>
       										Paramétres
       									</a>
       								</li>
       
       								<li>
       									
       									<a href="#" class="this_url" rel="editcompteuser&iduser=<?php echo $usrid; ?>">
       										<i class="icon-user"></i>
       										Compte
       									</a>
       								</li>
       
       								<li class="divider"></li>
       
       								<li>
       									<a href="./?_tsk=logout">
       										<i class="icon-off"></i>
       										Déconnexion
       									</a>
       								</li>
       							</ul>
       						</li>
       					</ul>/.ace-nav
       				</div>/.container-fluid
       			</div>/.navbar-inner
       		</div> -->