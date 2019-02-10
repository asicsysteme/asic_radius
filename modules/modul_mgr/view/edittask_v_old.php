<?php
//Get all modul infos 
 $info_task= new Mmodul();
 //$info_modul->id_modul = Mreq::tp('id');
 $info_task->id_task  = Mreq::tp('id');

 if(!MInit::crypt_tp('id', null, 'D')  or !$info_task->get_task())
 { 	
 	exit('3#'.$info_task->log .'<br>Les  informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 	//var_dump($info_task->task_info);
 	$id_modul   = $info_task->task_info['modul'];
        $id_modul_c = md5(MInit::cryptage($id_modul,1));
        $id_app     = Mreq::tp('id');
        $id_app_c   = md5(MInit::cryptage($id_app,1));
 ?>


<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
TableTools::btn_add('task', 'Liste Task Module ('.$id_modul.') ', MInit::crypt_tp('id', $id_modul), $exec = NULL, 'reply'); 
		?>

					
	</div>
</div>
<div class="page-header">
	<h1>
		Editer Application Task : "<?php echo $info_task->task_info['dscrip']. ' - '. $info_task->id_task. ' - ';?>"
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

$form = new Mform('edittask', 'edittask', $info_task->id_task, 'task&'.MInit::crypt_tp('id',$id_modul),'');

$form->input_hidden('id_modul', $id_modul);
$form->input_hidden('id_checker_modul',  $id_modul_c);
$form->input_hidden('id_app', $id_app);
$form->input_hidden('id_checker',  $id_app_c);
//Déscription application
$desc_array[]  = array('required', 'true', 'Insérer la Déscription' );
$form->input('Déscription', 'description', 'text' ,6 , $info_task->task_info['dscrip'], $desc_array);
//Nom Application
$app_array[]  = array('required', 'true', 'Insérer Nom d l'."\'".' application' );
$app_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$app_array[]  = array('remote', 'app#task#app', 'Ce nom existe déja' );
$app_array[]  = array('regex', 'true', 'Insérer Nom de Modul Valid' );
$form->input('Nom Application', 'app', 'text', 6, $info_task->task_info['app'], $app_array);


//Option Application système
$app_sys = array( '0' => 'NON' ,  '1' => 'OUI' );
//$form->select('Application Système', 'app_sys', 3, $app_sys, $indx = NULL ,$info_task->task_info['app_sys'] );
//$form->select_onchange('app_sys');
//Type d'affichage (Tableau, Formulaire, Profile)
$type_view  = array('list' => 'Tableau liste', 'formadd' => 'Formulaire Ajout', 'formedit' => 'Formulaire Edit' , 'profil' => 'Page d\'informations' , 'exec' => 'Executable');
$form->select('Type d\'affichage', 'type_view', 5, $type_view, $indx = NULL ,$info_task->task_info['type_view'] );
$form->select_table('Services', 'services[]', 10, 'services','id', 'service', 'service', $indx = NULL ,$info_task->task_info['services'] , 1, NULL, NULL);
$sbclass_array[]  = array('regex', 'true', 'Insérer Classe Valid' );
$sbclass_array[]  = array('minlength', '2', 'Minimum 3 caractères' );
$form->input('Class TDB', 'sbclass', 'text', 6 , $info_task->task_info['sbclass'], $sbclass_array);
//Message dans la liste
$form->input('Message à afficher', 'etat_desc', 'text' ,6 , $info_task->task_info['etat_desc'], $desc_array);
//Style Message
$message_style = array('success' => 'Vert', 'warning' => 'Orange', 'danger' => 'Rouge', 'info' => 'Bleu', 'inverse' => 'Noire' );
$form->select('Type Message', 'message_class', 3, $message_style, $indx = NULL ,$info_task->task_info['message_class'] );
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



