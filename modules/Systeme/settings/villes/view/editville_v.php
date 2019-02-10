<?php
defined('_MEXEC') or die;
//Get all compte info 
 $info_ville = new Mville();
//Set ID of Module with POST id
 $info_ville->id_ville = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_ville->get_ville())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_ville->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('villes', 'Liste des villes', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier la ville 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

		<?php echo ' ('.$info_ville->Shw('ville',1).' -'.$info_ville->id_ville.'-)' ;
		//var_dump($info_ville->get_ville());
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


$form = new Mform('editville', 'editville', $info_ville->id, 'villes' , ' ');

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations ville');

$form->input_hidden('id', $info_ville->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Titre bloc 
//$form->bloc_title('Informations Utilisateur');
//Ville
$ville_array[]  = array('required', 'true', 'Insérer ville' );
$ville_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Ville', 'ville', 'text' ,6 , $info_ville->Shw('ville',1), $ville_array);


//Département actif
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$departement_array[]  = array('required', 'true', 'Choisir le département' );
$form->select_table('Département', 'id_departement', 6, 'ref_departement', 'id', 'departement' , 'departement', $indx = '*****' ,
	$selected=$info_ville->Shw('id_departement',1),$multi=NULL, $where='etat=1', $departement_array);

//$form->step_end();
//$form->step_start(2, 'Informations de connexion');
//Titre bloc 
//$form->bloc_title('Informations de connexion');
//Latitude
$latitude_array[]  = array('required', 'true', 'Insérer une latitude' );
$latitude_array[]  = array('number', 'true', 'Entrez un Nombre valide' );
$form->input('Latitude', 'latitude', 'text', 6, $info_ville->Shw('latitude',1), $latitude_array);

//Longitude
$longitude_array[]  = array('required', 'true', 'Insérer une latitude' );
$longitude_array[]  = array('number', 'true', 'Entrez un Nombre valide' );
$form->input('Longitude', 'longitude', 'text', 6, $info_ville->Shw('longitude',1), $longitude_array);



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
