<?php defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('villes', 'Liste des villes', Null, $exec = NULL, 'reply');      
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
$form = new Mform('addville', 'addville','',  'villes', '');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations ville');
//Titre bloc 
//$form->bloc_title('Informations Utilisateur');
//Ville
$ville_array[]  = array('required', 'true', 'Insérer ville' );
$ville_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$ville_array[]  = array('remote', 'ville#ref_ville#ville', 'Cette ville existe déja' );
$form->input('Ville', 'ville', 'text' ,6 , null, $ville_array);

//Département actif
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$departement_array[]  = array('required', 'true', 'Choisir le département' );
$form->select_table('Département', 'id_departement', 6, 'ref_departement', 'id', 'departement' , 'departement', $indx = '*****' ,
	$selected=NULL,$multi=NULL, $where='etat=1', $departement_array);

//$form->step_end();
//$form->step_start(2, 'Informations de connexion');
//Titre bloc 
//$form->bloc_title('Informations de connexion');
//Latitude
$latitude_array[]  = array('required', 'true', 'Insérer une latitude' );
$latitude_array[]  = array('number', 'true', 'Entrez un Nombre valide' );
$form->input('Latitude', 'latitude', 'text', 6, null, $latitude_array);

//Longitude
$longitude_array[]  = array('required', 'true', 'Insérer une latitude' );
$longitude_array[]  = array('number', 'true', 'Entrez un Nombre valide' );
$form->input('Longitude', 'longitude', 'text', 6, null, $longitude_array);


$form->step_end();
//Button submit 
$form->button('Enregistrer la ville');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
