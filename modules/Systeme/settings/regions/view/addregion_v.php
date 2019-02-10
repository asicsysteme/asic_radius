<?php defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('regions', 'Liste des régions', Null, $exec = NULL, 'reply');      
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
$form = new Mform('addregion', 'addregion','',  'regions', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
$form->wizard_steps = $wizard_array;
//Titre bloc 
$form->step_start(1, 'Informations région');

//Region
$region_array[]  = array('required', 'true', 'Insérer région' );
$region_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$region_array[]  = array('remote', 'region#ref_region#region', 'Cette région existe déja' );
$form->input('Région', 'region', 'text' ,6 , null, $region_array);

//Region active
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$pays_array[]  = array('required', 'true', 'Choisir le pays' );
$form->select_table('Pays', 'id_pays', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '*****' ,
$selected=242,$multi=NULL, $where='etat=1', $pays_array);

$form->step_end();
//Button submit 
$form->button('Enregistrer la région');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
