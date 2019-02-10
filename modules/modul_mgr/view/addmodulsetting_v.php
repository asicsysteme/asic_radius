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

$form = new Mform('addmodulsetting', 'addmodulsetting','',  'modul', '');

//Titre bloc Modul
$form->bloc_title('Informations Module de paramétrage / Sous-Modul');

//Type Modul
$option_type  = array('1' => 'Paramètre' , '2' => 'Sous Modul' );
$form->select('Type module', 'type_modul', 3, $option_type, NULL ,$selected = NULL, $multi = NULL );
//Nom Module
$modul_array[]  = array('required', 'true', 'Insérer Nom de Module' );
$modul_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$modul_array[]  = array('regex', 'true', 'Insérer Nom de Modul Valid (a_z 0-9)' );
$form->input('Nom Module', 'modul', 'text' ,6 , null, $modul_array);
//Répertoire Module
//Choix modul de base
$form->select_table('Module de Base', 'modul_setting', 5, 'sys_modules','modul', 'modul', 'description', $indx = NULL ,$selected = NULL , NULL, ' is_setting = 0 ', NULL);
//Déscription Module
$description_array[]  = array('required', 'true', 'Insérer la déscription' );
$description_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Déscription', 'description', 'text', 10, null, $description_array);

//Tables de module
$table_array[]  = array('required', 'true', 'Insérer au moin une table utilisée' );
$table_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('Table utilisées', 'tables', 'text', 4, null, $table_array);
//$form->input_tag('tables');

//Class en cas de app de base
//$rep_array[]  = array('regex', 'true', 'Insérer Nom d l'."\'".' application Valid' );
//$sbclass_array[]  = array('regex', 'true', 'Insérer Classe Valid' );
$sbclass_array[]  = array('minlength', '2', 'Minimum 3 caractères' );
$form->input('Icone', 'sbclass', 'text', 6 , '', $sbclass_array);
//Message dans la liste
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
