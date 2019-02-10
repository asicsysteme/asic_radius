<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS ASIC ERP
// Modul: modul_mgr
//Created : 12-10-2017
//View
 $workflow= new Mmodul();
 $workflow->id_task = Mreq::tp('id');
 $workflow->get_task();
 $task = $workflow->task_info['app'];
 $id_modul = $workflow->task_info['modul'];
 ?>
 <div class="pull-right tableTools-container">
 	<div class="btn-group btn-overlap">


 		<?php 
 		TableTools::btn_add('task', 'Liste Application Modul ('.$id_modul.') ', MInit::crypt_tp('id',$id_modul), $exec = NULL, 'reply');     
 		?>		
 	</div>
 </div>
 <div class="page-header">
 	<h1>
 		Détails Work Flow pour :     <?php echo $workflow->task_info['dscrip']; ?> 

 		<small>
 			<i class="ace-icon fa fa-angle-double-right"></i>
 		</small>
 	</h1>
 </div>
 <div class="row">
 	<div class="col-xs-12">
       <?php $workflow->show_work_flow($task) ?>
 	</div>
 </div>


</div><!-- /.well -->


</div><!-- /.-profile -->

