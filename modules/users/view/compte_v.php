<?php 
//Get all compte info 
 $user_info = new Musers();
//Set ID of Module with POST id
 $user_info->id_user = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$user_info->get_user())
 { 	
 	// returne message error red to client 
 	exit('3#'.$user_info->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


$user_id = Mreq::tp('id');
$user_id_c = MInit::crypt_tp('id', $user_id);

if($user_info->get_activities()){
	$array_activities = $user_info->user_activities;
}else{
	$array_activities = $user_info->log;
}
 

if($user_info->get_connexion_history()){
	$array_connexion_history = $user_info->user_connexion_history;
}else{
	$array_connexion_history = $user_info->log;
}


//var_dump($user_info->user_info);
$photo = Minit::get_file_archive($user_info->user_info['photo']);

?>
<div class="page-header">
	<h1>
		Page de profil de <?php  $user_info->s('lnom');?>  <?php  $user_info->s('fnom'); ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>
<!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		

		<div class="hr dotted"></div>



		<div>
			<div id="user-profile-2" class="user-profile">
				<div class="tabbable">
					<ul class="nav nav-tabs padding-18">
						<li class="active">
							<a data-toggle="tab" href="#home">
								<i class="green ace-icon fa fa-user bigger-120"></i>
								Profil 
							</a>
						</li>

						<li>
							<a data-toggle="tab" href="#feed">
								<i class="orange ace-icon fa fa-rss bigger-120"></i>
								Liste des Activités / Historique de connexion
							</a>
						</li>

						
					</ul>

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
								<div class="col-xs-12 col-sm-3 center">
									<span class="profile-picture">
										<img width="180" height="200" class="editable img-responsive" alt="Alex's Avatar" id="avatar2" src="<?php echo $photo ?>" />
									</span>

									<div class="space space-4"></div>

								</div><!-- /.col -->

								<div class="col-xs-12 col-sm-9">
									<h4 class="blue">
										<span class="middle"><?php  $user_info->s('lnom') ?></span>
										<span class="middle"><?php  $user_info->s('fnom') ?></span>

										<span class="label label-purple arrowed-in-right">
											<i class="ace-icon fa fa-circle smaller-80 align-middle"></i>
											En ligne
										</span>
									</h4>

									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name"> Utilisateur </div>

											<div class="profile-info-value">
												<span><?php  $user_info->s('nom') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Email </div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span><?php  $user_info->s('mail') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Mot de passe</div>

											<div class="profile-info-value">
												<!--<i class="fa fa-map-marker light-orange bigger-110"></i>-->
												<span>************</span>
											</div>
										</div>

										<div class="middle">  				
										
											<span>
												<a class="this_url" href="#" data="<?php echo $user_id_c; ?>" rel="changepass">Modifier</a>
											</span>
										
										</div>	

										<div class="profile-info-row">
											<div class="profile-info-name"> Téléphone </div>

											<div class="profile-info-value">
												<span><?php  $user_info->s('tel') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Service </div>

											<div class="profile-info-value">
												<span><?php  $user_info->s('service_user') ?></span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Date de création </div>

											<div class="profile-info-value">
												<span><?php  $user_info->s('credat') ?></span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Dernière connexion </div>

											<div class="profile-info-value">
												<span><?php  $user_info->s('lastactive') ?></span>
											</div>
										</div>
									</div>

								</div><!-- /.col -->
							</div><!-- /.row -->

						</div><!-- /#home -->

						<div id="feed" class="tab-pane">
							<div class="profile-feed row">
								<div class="col-sm-6">
									<table class="table table-striped table-bordered table-hover">
										<th>
											Liste des activités
										</th>
											<?php
											if(is_array($array_activities)){
												foreach($array_activities as $activities ) {?>
											<tr>	
												<td>
													<span><?php echo $activities['0']; ?></span>
												</td>
											</tr>
											<?php }
											 } else{
											 	echo $array_activities;
											 }
											 ?>
											
											
											
										</table>

										<div class="center">
											
											<?php TableTools::btn_add('activities','Consulter toutes les activités',"$user_id_c",NULL,"rss bigger-150 middle orange2 ");?>
											
										</div>
									</div><!-- /.col -->

									<div class="col-sm-6">

										<table class="table table-striped table-bordered table-hover">
											<th>
												Historique de connexion
											</th>
											<?php var_dump($array_activities);
											if(is_array($array_connexion_history)){
											foreach($array_connexion_history as $history ) {?>
											<tr>
												<td>
													<span><?php echo $history['activities']; ?></span>
												</td>
											</tr>
											<?php }
											}else{
												echo $array_connexion_history;
											} ?>
											
										</table>

										<div class="center">

											<?php TableTools::btn_add('history','Consulter le détails historique',"$user_id_c",NULL,"key bigger-150 middle blue ");?>

										</div>

									</div><!-- /.col -->

								</div><!-- /.row -->

							</div><!-- /#feed -->


						</div>
					</div>
				</div>
			</div>


		</div><!-- /.well -->


	</div><!-- /.user-profile -->
