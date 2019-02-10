<?php 
		//First check target no Hack
if(!defined('_MEXEC'))die();
		//SYS ASIC ERP
		// Modul: modul_mgr
		//Created : 30-11-2018
		//View
		//Get all modul infos 
$info_modul= new Mmodul();
$info_modul->id_modul = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')  or !$info_modul->get_modul())
{ 	
	exit('3#'.$info_modul->log .'<br>Les  informations pour cette ligne sont erronées contactez l\'administrateur');
}
$id_modul   = Mreq::tp('id');
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('modul_mgr','Liste des modul_mgr', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter une Etape Work-FLow 
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		Module <?php echo $info_modul->modul_info['description'];?>
	</h1>
</div><!-- /.page-header -->
<!-- Bloc form Add Devis-->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "Ajout Etape Workflow"
		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php
 
$form = new Mform('addmodul_workflow', 'addmodul_workflow', '', 'modul_workflow&'.MInit::crypt_tp('id', $id_modul), '0', null);

$form->input_hidden('modul_id', $info_modul->id_modul);
$form->input_hidden('id_checker_modul',  MInit::cryptage($info_modul->id_modul, 1));

//Déscription application
$desc_array[]  = array('required', 'true', 'Insérer la Déscription' );
$form->input('Déscription', 'descrip', 'text' ,6 , null, $desc_array);
//Code Usage
$code_array[]  = array('required', 'true', 'Insérer code usage' );
$form->input('Code usage', 'code', 'text' ,3 , null, $code_array);
//WF Message color
$color_array = array('success' => 'Vert', 'warning' => 'Orange', 'danger' => 'Rouge', 'info' => 'Bleu', 'inverse' => 'Noire' );
$form->select('Couleur', 'color', 3, $color_array, $indx = NULL ,$selected = NULL );
//Etat de ligne
//select_count($input_desc, $input_id, $input_class, $count, $indx = NULL ,$selected = NULL )
$form->select_count('Etat de la ligne', 'etat_line', 2, 15, $indx = NULL ,$selected = NULL );
//Message à afficher
$form->input('Message à afficher', 'message_etat', 'text' ,6 , null, $desc_array);




$form->button('Enregistrer');
//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
<!-- End Add devis bloc -->
		
<script type="text/javascript">
$(document).ready(function() {
    
//JS bloc   

});
</script>	

		