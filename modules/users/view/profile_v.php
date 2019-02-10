<?php 
$service = cryptage($_SESSION['service'],0); 
$usrid= session::get('userid');
global $db;

$user = tg("u") == "0" ?$usrid:tg("u");

if (! $db->Query("SELECT * FROM users_sys where id= $user")) $db->Kill($db->Error());
$array = $db->RowArray();

$rep = './upload/salarie/';

 ?>
 <script type="text/javascript">
 ajax_loadmessage("Aucun enregistrement trouvé SELECT users_sys.*, archive.doc FROM users_sys, archive where users_sys.form = archive.id AND users_sys.id = 45","nok");ajax_loader("user");</script>
 
 <div class="breadcrumbs" id="breadcrumbs">
					<ul class="breadcrumb">
						<li>
							<i class="icon-home home-icon"></i>
							<a href="./">Tableau de bord</a>

							<span class="divider">
                            
								<i class="icon-angle-right arrow-icon"></i>
							</span>
						</li>
                        
                        <li>
                        <?php echo MODUL_APP; ?>
							<span class="divider">
								<i class="icon-angle-right arrow-icon"></i>
							</span>
						</li>
						<li class="active"><?php echo ACTIV_APP; ?></li>
					</ul><!--.breadcrumb-->
                     
					
</div>
                <div class="page-content">
					<div class="row-fluid">
                                
								<h3 class="header smaller lighter blue"><?php echo ACTIV_APP; ?></h3>
                                
								
                             <div class="widget-content">
            <div class="widget-box">
             <ul class="nav nav-tabs padding-18">
											<li class="active">
												<a data-toggle="tab" href="#home">
													<i class="green icon-user bigger-120"></i>
													Profile
												</a>
											</li>

											<li class="">
												<a data-toggle="tab" href="#feed">
													<i class="orange icon-rss bigger-120"></i>
													Historique Connexion
												</a>
											</li>

											
										</ul>
										<div class="tab-content no-border padding-24">
											<div id="home" class="tab-pane  active">
												<div class="row-fluid">
													<div class="span3 center">





														<span class="profile-picture">
														<?php if (file_exists($rep.$array['photo'])){ ?>
														   <img class="editable" alt="<?php echo $array['nom'].'  '.$array['prenom'] ?>" id="avatar2" src=<?php echo $rep.$array['photo']?> height="202" width="182">

                       
                        <?php }else{?>
                       <img class="editable" alt="<?php echo $array['nom'].'  '.$array['prenom'] ;?>" id="avatar2"src="img/user-thumb.png" height="202" width="182">
                        <?php }?>
                                                         
															


														</span>

														<div class="space space-4"></div>

														<a href="#" class="btn btn-small btn-block btn-success">
															
															Modifier Profil
														</a>

														<a href="#" class="btn btn-small btn-block btn-primary">
															
															Demande Avance
														</a>
													</div><!--/span-->

													<div class="span9">
														<h4 class="blue">
															<span class="middle"><?php echo $array['nom'].'  '.$array['prenom'] ?></span>

															<span class="label label-purple arrowed-in-right">
																<i class="icon-circle smaller-80"></i>
																<?php echo $array['fonction'];?>
															</span>
														</h4>

														<div class="profile-user-info">
														    	<div class="profile-info-row">
																<div class="profile-info-name"> Matricule </div>

																<div class="profile-info-value">
																	<span><?php echo $array['matricule'];?></span>
																</div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Situation Familiale </div>

																<div class="profile-info-value">
																	<span><?php echo $famille;?></span>
																</div>
															</div>

															<div class="profile-info-row">
																<div class="profile-info-name"> Nombre Enfants </div>

																<div class="profile-info-value">
																	
																	<span><?php echo $array['nbr_enfant'];?></span>
																</div>
															</div>
                                                             
														
														

															<div class="profile-info-row">
																<div class="profile-info-name"> Date d'Embauche </div>

																<div class="profile-info-value">
																	<span><?php echo date('d-m-Y',strtotime($array['date_embauche']));  ?></span>
																</div>
															</div>
																<div class="profile-info-row">
																<div class="profile-info-name"> status </div>

																<div class="profile-info-value">
																	<span><?php echo $array['status'];?></span>
																</div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Agence </div>

																<div class="profile-info-value">
																	<span><?php echo $array['agence'];?></span>
																</div>
															</div>  
															<div class="profile-info-row">
																<div class="profile-info-name"> Service </div>

																<div class="profile-info-value">
																	<span><?php echo $array['service'];?></span>
																</div>
															</div>

															
														</div>

														<div class="hr hr-8 dotted"></div>

													
													</div><!--/span-->
												</div><!--/row-fluid-->

												<div class="space-20"></div>

												
											</div><!--#home-->
											 <?php 
;

if (! $db->Query("SELECT * FROM info_salarie_budgetaire WHERE id_salarie =".tg('id'))) $db->Kill($db->Error());
$array2 = $db->RowArray();

 ?>
 

											<div id="feed" class="tab-pane">
												<div class="profile-feed row-fluid">
													<div class="span6">
														

														 <div class="profile-user-info profile-user-info-striped">
											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Salaire de Base </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="username">
													 <?php echo $nombre_format_francais = number_format($array2['salaire_de_base'], 2, ',', ' ')    ; ?> 
													</span>
												</div>
											</div>

											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Nouveau Salaire </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="username">
													 <?php echo $nombre_format_francais = number_format($array2['nouveau_salaire'], 2, ',', ' ')    ; ?> 
</span>
												</div>
											</div>

											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Taux d'Ancienneté </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="age"><?php echo $array2['taux_anciennete'];?> %</span>
												</div>
											</div>

											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Nom Banque </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="signup"><?php echo $array2['nom_banque'];?></span>
												</div>
											</div>

											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Numero Compte </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['numero_compte'];?></span>
												</div>
											</div>
											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Indemnite de Logement </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['indem_logement'];?></span>
												</div>
											</div>
											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Indemnite de Caisse </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['indem_caisse'];?></span>
												</div>
											</div>
											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Indemnite de Risque  </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['indem_risque'];?></span>
												</div>
											</div>
											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Indemnite de Responsabilite  </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['indem_responsabilite'];?></span>
												</div>
											</div>
											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Indemnite de Telephone  </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['idem_telephone'];?></span>
												</div>
											</div>
											<div class="profile-info-row">
												<div class="profile-info-name" style="width:200px;"> Indemnite de Domesticite </div>

												<div class="profile-info-value"style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['indem_domisticite'];?></span>
												</div>
											</div>
												<div class="profile-info-row" >
												<div class="profile-info-name" style="width:200px;"> Indemnite d'Eau et d'Electricite </div>

												<div class="profile-info-value" style="margin-left:200px;">
													<span class="editable editable-click" id="login"><?php echo $array2['idem_eau_electrique'];?></span>
												</div>
											</div>

											
										</div>
										

														

														

									
													</div><!--/span-->

													
												</div><!--/row-->

												
											</div><!--/#feed-->

											<div id="friends" class="tab-pane">
												<div class="profile-users clearfix">
													

													
												

												

												</div>

												
											</div><!--/#friends-->

											<div id="pictures" class="tab-pane">
												
											</div><!--/#pictures-->
										</div>
            </div>
          </div>
 
     </div><!--/span-->
</div><!--/row-->
