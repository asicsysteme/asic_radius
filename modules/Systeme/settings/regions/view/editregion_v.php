<?php
defined('_MEXEC') or die;
//Get all region info 
 $info_region = new Mregion();
//Set ID of Module with POST id
 $info_region->id_region = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D')  or !$info_region->get_region())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_region->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('regions', 'Liste des régions', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier la région 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

		<?php echo ' ('.$info_region->Shw('region',1).' -'.$info_region->id_region.'-)' ;
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


$form = new Mform('editregion', 'editregion', $info_region->id, 'regions' , ' ');

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations région');

$form->input_hidden('id', $info_region->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Titre bloc 
//$form->bloc_title('Informations Region');
//Region
$region_array[]  = array('required', 'true', 'Insérer région' );
$region_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Région', 'region', 'text' ,6, $info_region->Shw('region',1), $region_array);

//Pays actif
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)

$pays_array[]  = array('required', 'true', 'Choisir le pays' );
$form->select_table('Pays', 'id_pays', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '*****' ,
$selected=$info_region->Shw('id_pays',1),$multi=NULL, $where='etat=1', $pays_array);

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
