<?php
//Get all compte info 
 $info_user = new Musers();
//Set ID of Module with POST id
 $info_user->id_user = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_user->get_user())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_user->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }


 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('user', 'Liste Utilisateurs', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier Compte Utilisateur 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>
		<?php echo ' ('.$info_user->Shw('fnom',1).'  '.$info_user->Shw('lnom',1).' -'.$info_user->id_user.'-)' . ' Service: '.$info_user->Shw('service_user',1);?>
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


$form = new Mform('edituser', 'edituser', $info_user->Shw('id',1), 'user' , '1');

$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$wizard_array[] = array(4,'Etape 4');
$form->wizard_steps = $wizard_array;

$form->step_start(1, 'Fihiers à ajouter');
$form->input_hidden('id', $info_user->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//photo
$form->input('Photo', 'photo', 'file', 6, 'Photo.png', null);
$form->file_js('photo', 100000, 'image', $info_user->Shw('photo',1), 1);
//Form
$form->input('Formulaire', 'form', 'file', 6, 'Formulaire.pdf', null);
$form->file_js('form', 100000, 'doc',$info_user->Shw('form',1),1);
//Signature
$form->input('Signature', 'signature', 'file', 6, 'Signature.png', null);
$form->file_js('signature', 100000, 'image',$info_user->Shw('signature',1), 1);
$form->step_end();
$form->step_start(2, 'Informations générales');
//Nom utilisature
$fnom_array[]  = array('required', 'true', 'Insérer Nom utilisateur' );
$fnom_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Nom utilisateur', 'fnom', 'text' ,6 , $info_user->Shw('fnom',1), $fnom_array);
//Prénom utilisateur
$lnom_array[]  = array('required', 'true', 'Insérer Prénom utilisateur' );
$lnom_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Prénom utilisateur', 'lnom', 'text', 6, $info_user->Shw('lnom',1), $lnom_array);
//Service
$service_array[]  = array('required', 'true', 'Choisir le Service' );
$form->select_table('Service', 'service', 6, 'sys_services', 'id', 'service' , 'service', $indx = '------' ,$selected=$info_user->Shw('service',1),$multi=NULL, $where='id <> 1', $service_array);
$form->step_end();
$form->step_start(3, 'Informations d\'accés');
//Pseudo utilisateur
$pseudo_array[]  = array('required', 'true', 'Insérer pseudo utilisateur' );
$pseudo_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$pseudo_array[]  = array('remote', 'pseudo#users_sys#nom', 'Ce pseudo existe déja' );
$form->input('Pseudo utilisateur', 'pseudo', 'text', 6, $info_user->Shw('nom',1) , $pseudo_array);
//Mot de passe
//$pass_array[]  = array('required', 'true', 'Insérer le mot de passe' );
$pass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$pass_array[]  = array('remote', 'pass', 'Le mot de passe doit être alphanumériques compris entre 8 et 15 caractères');
$form->input('Mot de passe', 'pass', 'password', 6, null, $pass_array);

//Confirm Mot de passe
//$cpass_array[]  = array('required', 'true', 'Confirmez le mot de passe' );
$cpass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$cpass_array[]  = array('equalTo', '#pass', 'les deux mots de passe incompatibles');
$form->input('Confirmation mot de passe', 'passc', 'password', 6, null, $cpass_array);
$form->step_end();
$form->step_start(4, 'Informations de contact');

//Email utilisateur
$mail_array[]  = array('required', 'true', 'Insérer Email utilisateur' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$mail_array[]  = array('remote', 'email#users_sys#mail', 'Cette adresse existe déja' );
$form->input('Email utilisateur', 'email', 'text', 6, $info_user->Shw('mail',1), $mail_array);
 
//Nom utilisature
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('number', 'true', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('minlength', '8', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, $info_user->Shw('tel',1), $tel_array);


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




