<?php defined('_MEXEC') or die; ?>
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
$form = new Mform('addservices', 'addservices','', 'services', '0');

//Nom Service
$nom_array[]  = array('required', 'true', 'Insérer service' );
$nom_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Nom service', 'service', 'text' ,6 , null, $nom_array);

//Service
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)


//Signature 1 / 0
//$prenom_array[]  = array('required', 'true', 'Insérer 0 ou 1' );
//$prenom_array[]  = array('minlength', '1', 'Maximum 1 caractères' );
//$form->input('Exige une Signature', 'sign', 'text' ,6 , null, $prenom_array);

$bloc_group_ste[]  = array('Oui' , '1' );
$bloc_group_ste[]  = array('Non' , '0' );
$radio_js_array[]  = array('required', 'true', 'Indiquez exigence' );
$form->radio('Signature exigée', 'sign', '0' , $bloc_group_ste, $radio_js_array);

//Button submit 
$form->button('Enregistrer le service');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
