<?php defined('_MEXEC') or die; 

$info_service = new Mservice();
//Set ID of Module with POST id
 $info_service->id_service = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_service->get_service())
 { 	
 	// returne message error red to client 
 	exit('0#'.$info_service->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 
 ?>


<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('services', 'Liste des services', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$info_service->Shw('service',1).' -'.$info_service->id_service.'-)' ;?>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP; ?>"

		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php
//
$form = new Mform('editservices', 'editservices',$info_service->id_service, 'services', '0');
$form->input_hidden('id', $info_service->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));


//Nom Service
$nom_array[]  = array('required', 'true', 'Insérer service' );
$nom_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Nom service', 'service', 'text' ,6 , $info_service->Shw('service',1), $nom_array);


//Service
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)


//Signature 1 / 0
//$prenom_array[]  = array('required', 'true', 'Insérer 0 ou 1' );
//$prenom_array[]  = array('minlength', '1', 'Maximum 1 caractères' );
//$form->input('Exige une Signature', 'sign', 'text' ,6 , $info_service->Shw('sign',1), $prenom_array);


$bloc_group_ste[]  = array('Oui' , 1 );
$bloc_group_ste[]  = array('Non' , 0 );
//$radio_js_array[]  = array('required', 'true', 'Indiquez l\'exigence' );
$form->radio('Signature exigée', 'sign', $info_service->Shw('sign',1), $bloc_group_ste, NULL);


//Button submit 
$form->button('Modifier le service');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
