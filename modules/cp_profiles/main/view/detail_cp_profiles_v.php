<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//View
 $cp_profiles= new Mcp_profiles();
 $cp_profiles->id_cp_profiles = Mreq::tp('id');
 $cp_profiles->get_cp_profiles();
 ?>
 <div class="pull-right tableTools-container">
 	<div class="btn-group btn-overlap">


 		<?php 
 		TableTools::btn_add('cp_profiles', 'Liste profiles', Null, $exec = NULL, 'reply');      
 		?>		
 	</div>
 </div>
 <div class="page-header">
 	<h1>
 		Détails du profile:     <?php $cp_profiles->s('id'); ?> 

 		<small>
 			<i class="ace-icon fa fa-angle-double-right"></i>
 		</small>
 	</h1>
 </div>
 <div class="row">
 	<div class="col-xs-12">
        <div>
 			<div id="user-profile-2" class="user-profile">
 				<div class="tabbable">
 					<ul class="nav nav-tabs padding-18">
 						<li class="active">
 							<a data-toggle="tab" href="#home">
 								<i class="green ace-icon fa fa-installer bigger-120"></i>
 								Profile 
 							</a>
 						</li>


 					</ul>

 					<div class="tab-content no-border padding-24">
 						<div id="home" class="tab-pane in active">
 							<div class="row">
                                <div class="col-xs-12 col-sm-6">
 									<h4 class="blue">
 										<span class="middle">Renseignements profile</span>
 									</h4>
 									
 										<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">Profile</div>
 									<div class="profile-info-value">
 										<span><?php  $cp_profiles->s("profile")  ?></span>
 									</div>
 								</div>
 							</div>


 									
								</div><!-- /.col -->
 							</div>


                        </div><!-- /#home -->

 					</div><!-- /.row -->

 				</div><!-- /#feed -->

 			</div>
 		</div>
 	</div>
 </div>


</div><!-- /.well -->


</div><!-- /.-profile -->

