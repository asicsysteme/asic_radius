<?php
defined('_MEXEC') or die;
//Get all compte info 
 $info_departement = new Mdept();
//Set ID of Module with POST id
 $info_departement->id_departement = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_departement->get_departement())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_departement->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('departements', 'Liste des départements', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le département 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

		<?php echo ' ('.$info_departement->Shw('departement',1).' -'.$info_departement->id_departement.'-)' ;
		//var_dump($info_departement->get_departement());
		?>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP ; ?>"

		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php


$form = new Mform('editdepartement', 'editdepartement', $info_departement->id, 'departements' , ' ');

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations département');

$form->input_hidden('id', $info_departement->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//departement
$departement_array[]  = array('required', 'true', 'Insérer département' );
$departement_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Département', 'departement', 'text' ,6 , $info_departement->Shw('departement',1), $departement_array);

//Région active
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$region_array[]  = array('required', 'true', 'Choisir la région' );
$form->select_table('Région', 'id_region', 6, 'ref_region', 'id', 'region' , 'region', $indx = '*****' ,
	$selected=$info_departement->Shw('id_region',1),$multi=NULL, $where='etat=1', $region_array);

$form->step_end();
//Button submit 
$form->button('Enregistrer Modifications');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
