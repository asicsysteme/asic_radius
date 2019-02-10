<?php defined('_MEXEC') or die; ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
              TableTools::btn_add('modul', 'Liste Modules', Null, $exec = NULL, 'reply');      
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
		<div class="widget-content scrolling">
			<div class="widget-box">
				
<?php

$form = new Mform('addmodul', 'addmodul','',  'modul', '');

//Titre bloc Modul
$form->bloc_title('Informations Module');
//Nom Module
$modul_array[]  = array('required', 'true', 'Insérer Nom de Module' );
$modul_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$modul_array[]  = array('regex', 'true', 'Insérer Nom de Modul Valid (a_z 0-9)' );
$form->input('Nom Module', 'modul', 'text' ,6 , null, $modul_array);
//Répertoire Module
/*$modul_rep_array[]  = array('required', 'true', 'Insérer Nom de Répertoire' );
$modul_rep_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$modul_rep_array[]  = array('regex', 'true', 'Insérer Nom de Répertoire Valid' );
$form->input('Répertoire Module', 'rep_modul', 'text' ,6 , null, $modul_rep_array);*/
//$form->input_autocomplete('modul','users_sys','nom', 'etat', '1');
//Test date picker
//$date_array[]  = array('required', 'true', 'Insérer une date' );
//$form->input_date('Date de test', 'test', 'text' ,6 , null, null);
//$form->input_editor('Editor de test', 'editor', 'text' ,6 , null, null);
//Déscription Module
$description_array[]  = array('required', 'true', 'Insérer la déscription' );
$description_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Déscription', 'description', 'text', 10, null, $description_array);

//Tables de module
$table_array[]  = array('required', 'true', 'Insérer au moin une table utilisée' );
$table_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Tables principale', 'tables', 'text', 4, null, $table_array);
//$form->input_tag('tables');
//Titre bloc default TASK
/*$form->bloc_title('Informations Application par défault');

//Nom Application
$app_array[]  = array('required', 'true', 'Insérer Nom d l'."\'".' application' );
$app_array[]  = array('regex', 'true', 'Insérer Nom d l'."\'".' application Valid' );
$app_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$app_array[]  = array('remote', 'app', 'Ce nom existe déja' );
$form->input('Nom Application', 'app', 'text', 6, null, $app_array);*/

//Class en cas de app de base
//$rep_array[]  = array('regex', 'true', 'Insérer Nom d l'."\'".' application Valid' );
//$sbclass_array[]  = array('regex', 'true', 'Insérer Classe Valid' );
$sbclass_array[]  = array('minlength', '2', 'Minimum 3 caractères' );
$form->input('Icone', 'sbclass', 'text', 3 , '', $sbclass_array);
//Message dans la liste
/*$desc_array[]  = array('required', 'true', 'Insérer le Message' );
$form->input('Message à afficher', 'etat_desc', 'text' ,6 , null, $desc_array);
//Style Message
$message_style = array('success' => 'Vert', 'warning' => 'Orange', 'danger' => 'Rouge', 'info' => 'Bleu', 'inverse' => 'Noire' );
$form->select('Type Message', 'message_class', 3, $message_style, $indx = NULL ,$selected = NULL );*/
//Titre bloc default Services
$form->bloc_title('Les Services par défault de ce module');
//Service
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)
$form->select_table('Services', 'services[]', 10, 'sys_services','id', 'service', 'service', $indx = NULL ,$selected = NULL , 1, NULL, NULL);

//Button submit 
$form->button('Enregistrer le Module');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
