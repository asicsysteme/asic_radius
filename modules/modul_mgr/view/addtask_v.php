<?php
defined('_MEXEC') or die;
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
					
		<?php 
		TableTools::btn_add('task', 'Liste Application Modul ('.$id_modul.') ', MInit::crypt_tp('id', $id_modul), $exec = NULL, 'reply'); 

		?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		
		Ajouter Application Task pour Modul :
        <small>
			<i class="ace-icon fa fa-angle-double-left"></i>
		</small>
		 <?php echo $info_modul->Shw('modul'). ' - '. $info_modul->id_modul. ' - ';?>
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

$form = new Mform('addtask', 'addtask', '', 'task&'.MInit::crypt_tp('id', $id_modul),'');

$form->input_hidden('id_modul', $info_modul->id_modul);
$form->input_hidden('id_checker_modul',  MInit::cryptage($info_modul->id_modul, 1));
//Déscription application
$desc_array[]  = array('required', 'true', 'Insérer la Déscription' );
$form->input('Déscription', 'description', 'text' ,6 , null, $desc_array);
//Nom Application
$app_array[]  = array('required', 'true', 'Insérer Nom d l'."\'".' application' );
$app_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$app_array[]  = array('remote', 'app#task#app', 'Ce nom existe déja' );
$app_array[]  = array('regex', 'true', 'Insérer Nom de Modul Valid' );
$form->input('Nom Application', 'app', 'text', 6, null, $app_array);
//Type d'affichage (Tableau, Formulaire, Profile)
$type_view  = array('list' => 'Tableau liste', 'formadd' => 'Formulaire Ajout', 'formedit' => 'Formulaire Edit' , 'formpers' => 'Formulaire Personnalisé' ,'profil' => 'Page d\'informations' , 'exec' => 'Executable');
$form->select('Type d\'affichage', 'type_view', 5, $type_view, $indx = NULL ,$selected = NULL );
//Service have rigths to run this app
$form->select_table('Services autorisés', 'services[]', 10, 'sys_services','id', 'service', 'service', $indx = NULL ,$selected = NULL , 1, NULL, NULL);
//Show on action_list
$action_list = array('Y' => 'Oui', 'N' => 'Non');
$form->select('Afficher action liste', 'action_list', 3, $action_list, $indx = NULL ,$selected = NULL );
//Each List of modul this task should be attach
$form->select_table('Liste mère', 'app_mere', 5, 'sys_task', 'id', 'app' , 'dscrip', $indx = NULL ,$selected = NULL, $multi = NULL, $where = "modul = $id_modul AND type_view = 'list' ", $js_array = null, $hard_code = null );
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null, $hard_code = null )
$form->select_table('Etat Workflow', 'etat_workflow[]', 8, 'sys_workflow', 'id', 'etat_line', 'CONCAT(id, \' - \', descrip)', $indx = NULL ,$selected = NULL , 1, 'modul_id = '.$id_modul, NULL);
//$sbclass_array[]  = array('regex', 'true', 'Insérer Classe Valid' );
$sbclass_array[]  = array('minlength', '2', 'Minimum 3 caractères' );
$form->input('Icone', 'sbclass', 'text', 6 ,null , $sbclass_array);


//Button submit 
$form->button('Enregistrer Application');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>



