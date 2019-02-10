<?php
defined('_MEXEC') or die;
//Get all compte info 
 $info_pays = new Mpays();
//Set ID of Module with POST id
 $info_pays->id_pays = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_pays->get_pays())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_pays->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('pays', 'Liste des pays', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le pays 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

		<?php echo ' ('.$info_pays->Shw('pays',1).' -'.$info_pays->id_pays.'-)' ;
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


$form = new Mform('editpays', 'editpays', $info_pays->id, 'pays' , ' ');

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
//$wizard_array[] = array(2,'Etape 2');
$form->wizard_steps = $wizard_array;
$form->step_start(1, 'Informations pays');

$form->input_hidden('id', $info_pays->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//Pays
$pays_array[]  = array('required', 'true', 'Insérer le pays' );
$pays_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Pays', 'pays', 'text' ,6, $info_pays->Shw('pays',1), $pays_array);

//Nationalité
$nationalite_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Nationalité', 'nationalite', 'text' ,6, $info_pays->Shw('nationalite',1), $nationalite_array);

//Code du pays
$alpha_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Code du pays', 'alpha', 'text' ,6, $info_pays->Shw('alpha',1), $alpha_array);

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
