 $%modul%= new M%modul%();
 $%modul%->id_%modul% = Mreq::tp('id');
 $%modul%->get_%modul%();
 ?>
 <div class="pull-right tableTools-container">
 	<div class="btn-group btn-overlap">


 		<?php 
 		TableTools::btn_add('%modul%', 'Liste %modul%', Null, $exec = NULL, 'reply');      
 		?>		
 	</div>
 </div>
 <div class="page-header">
 	<h1>
 		Détails du %modul%:     <?php $%modul%->s('id'); ?> 

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
 								%modul% 
 							</a>
 						</li>


 					</ul>

 					<div class="tab-content no-border padding-24">
 						<div id="home" class="tab-pane in active">
 							<div class="row">
                                <div class="col-xs-12 col-sm-6">
 									<h4 class="blue">
 										<span class="middle">Renseignements %modul%</span>
 									</h4>
 									
 									%list_profil%

 									
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

