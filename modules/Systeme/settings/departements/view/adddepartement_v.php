<?php defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('departements', 'Liste des départements', Null, $exec = NULL, 'reply');      
		 ?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		<?php echo ACTIV_APP; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
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
$form = new Mform('adddepartement', 'adddepartement','',  'departements', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
 
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations département');

//departement
$departement_array[]  = array('required', 'true', 'Insérer département' );
$departement_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$departement_array[]  = array('remote', 'departement#ref_departement#departement', 'Ce departement existe déja' );
$form->input('Département', 'departement', 'text' ,6 , null, $departement_array);

//Région active
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$region_array[]  = array('required', 'true', 'Choisir la région' );
$form->select_table('Région', 'id_region', 6, 'ref_region', 'id', 'region' , 'region', $indx = '*****' ,
	$selected=NULL,$multi=NULL, $where='etat=1', $region_array);


$form->step_end();
//Button submit 
$form->button('Enregistrer le departement');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
