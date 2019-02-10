<?php
//Get all task infos 
 $info_task = new Mmodul();
 $info_task->id_task = Mreq::tp('id');

 if(!MInit::crypt_tp('id', null, 'D')  or !$info_task->get_task())
 { 	
 	exit('3#'.$info_task->log .'<br>Les  informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 $id_modul = $info_task->task_info['modul'];

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
		Ajouter  Task Action pour Application : "<?php echo $info_task->task_info['dscrip']. ' - '. $info_task->id_task. ' - ';?>"
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

$form = new Mform('addtaskaction', 'addtaskaction', '', 'taskaction&'.MInit::crypt_tp('id', $info_task->id_task),'');

$form->input_hidden('name_task', $info_task->task_info['app']);
$form->input_hidden('name_checker_task',  MInit::cryptage($info_task->task_info['app'], 1));
$form->input_hidden('id_task', $info_task->id_task);
$form->input_hidden('id_checker_task',  MInit::cryptage($info_task->id_task, 1));

//Déscription application
$desc_array[]  = array('required', 'true', 'Insérer la Déscription' );
$form->input('Déscription', 'description', 'text' ,6 , null, $desc_array);
//Mode d'execution
$mode_exec = array('this_url' => 'Affiché' ,'this_exec' => 'Arrière plan');
$form->select('Mode d\'exécution', 'mode_exec', 3, $mode_exec, $indx = NULL ,$selected = NULL );
//Service
//select_table($input_desc, $input_id, $input_class, $table, $id_table, $order_by , $txt_table, $indx = NULL ,$selected = NULL, $multi = NULL, $where = NULL, $js_array = null)
$form->select_table('Services', 'services[]', 9, 'sys_services','id', 'service', 'service', $indx = NULL ,$selected = NULL , 1, NULL, NULL);
//$form->input_tag('services');
//Nom Application à appeler
$form->select_table('Application à appeler', 'app', 9, 'sys_task','app', 'app', 'dscrip', $indx = NULL ,$selected = NULL , NULL, 'modul = '.$info_task->task_info['modul'], NULL);

//Nom class Application
$class_array[]  = array('required', 'true', 'Insérer Nom de de la class' );
$class_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
//$class_array[]  = array('regex', 'true', 'Insérer une class valid' );
$form->input('Class Action', 'class', 'text', 6, null, $class_array);
//Etat de ligne
//select_count($input_desc, $input_id, $input_class, $count, $indx = NULL ,$selected = NULL )

$option_etat_line = array(0 => 'Etat 0', 1 => 'Etat 1', 2 => 'Etat 2', 3 => 'Etat 3', 4 => 'Etat 4', 5 => 'Etat 5', 6 => 'Etat 6', 7 => 'Etat 7', 8 => 'Etat 8', 9 => 'Etat 9', 10 => 'Etat 10', 100 => 'Etat archive 100' );

$form->select('Etat de la ligne', 'etat_line', 3, $option_etat_line, $indx = NULL ,$selected = NULL );
//Message dans la liste
$form->input('Message à afficher', 'etat_desc', 'text' ,6 , null, $desc_array);
//Style Message
$message_style = array('success' => 'Vert', 'warning' => 'Orange', 'danger' => 'Rouge', 'info' => 'Bleu', 'inverse' => 'Noire' );
$form->select('Type Message', 'message_class', 3, $message_style, $indx = NULL ,$selected = NULL );
//Option Application système
$notif = array('0' => 'NON' , '1' => 'OUI' );
$form->select('Notification pour action', 'notif', 3, $notif, $indx = NULL ,$selected = NULL );


//Button submit 
$form->button('Enregistrer Action Task');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
