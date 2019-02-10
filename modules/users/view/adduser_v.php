<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('user', 'Liste Utilisateurs', Null, $exec = NULL, 'reply');   ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter Utilisateur
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
//__construct($id_form, $app_exec, $is_edit, $app_redirect, $is_wizard)
$form = new Mform('adduser', 'adduser', '', 'user' , '1');

$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$wizard_array[] = array(4,'Etape 4');
$form->wizard_steps = $wizard_array;

$form->step_start(1, 'Fihiers à ajouter');
//photo
//$photo_array[]  = array('required', 'true', 'Insérer Nom utilisateur' );
$form->input('Photo', 'photo', 'file', 6, null, null);
$form->file_js('photo', 500000, 'image');
//Form
$form->input('Formulaire', 'form', 'file', 6, null, null);
$form->file_js('form', 10000000, 'pdf');
//Signature
$form->input('Signature', 'signature', 'file', 6, null, null);
$form->file_js('signature', 100000, 'image');
$form->step_end();
$form->step_start(2, 'Informations générales');


//Nom utilisature
$fnom_array[]  = array('required', 'true', 'Insérer Nom utilisateur' );
$fnom_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Nom utilisateur', 'fnom', 'text' ,6 , null, $fnom_array);
//Prénom utilisateur
$lnom_array[]  = array('required', 'true', 'Insérer Prénom utilisateur' );
$lnom_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$form->input('Prénom utilisateur', 'lnom', 'text', 6, null, $lnom_array);
//Service
$service_array[]  = array('required', 'true', 'Choisir le Service' );
$form->select_table('Service', 'service', 6, 'sys_services', 'id', 'service' , 'service', $indx = '------' ,$selected=NULL,$multi=NULL, $where='id <> 1', $service_array);


$form->step_end();
$form->step_start(3, 'Informations d\'accés');
//Pseudo utilisateur
$pseudo_array[]  = array('required', 'true', 'Insérer pseudo utilisateur' );
$pseudo_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$pseudo_array[]  = array('remote', 'pseudo#sys_users#nom', 'Ce pseudo existe déja' );
$form->input('Pseudo utilisateur', 'pseudo', 'text', 6, null, $pseudo_array);
//Mot de passe
$pass_array[]  = array('required', 'true', 'Insérer le mot de passe' );
$pass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$pass_array[]  = array('remote', 'pass', 'Le mot de passe doit être alphanumériques compris entre 8 et 15 caractères');
$form->input('Mot de passe', 'pass', 'password', 6, null, $pass_array);
//Input Inline
//$bloc_inline1[] = array(null, 'pass4', 'password', 3, null, $pass_array);
/*$bloc_inline1[] = array('Mot de passe1', 'pass1', 'password', 4, null, $pass_array);
$bloc_inline1[] = array('Mot de passe2', 'pass2', 'checkbox', '4 ace ace-checkbox-2', null, null);
$form->inline_input('Bloc 1',$bloc_inline1);*/
//Input Radio
//radio($radio_desc, $radio_id, $radio_value = null, $array_radio,  $js_array = null)
/*$radio_array[]  = array('Option 1' , 'Valeur1' );
$radio_array[]  = array('Option 2' , 'Valeur2' );
$radio_array[]  = array('Option 3' , 'Valeur3' );
$radio_array[]  = array('Option 4' , 'Valeur4' );
$radio_js_array[]  = array('required', 'true', 'Insérer le mot de passe' );
$form->radio('Exemple Radio', 'radio', 'Valeur2', $radio_array, $radio_js_array);*/

//Confirm Mot de passe
$cpass_array[]  = array('required', 'true', 'Confirmez le mot de passe' );
$cpass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$cpass_array[]  = array('equalTo', '#pass', 'les deux mots de passe incompatibles');
$form->input('Confirmation mot de passe', 'passc', 'password', 6, null, $cpass_array);
$form->step_end();
$form->step_start(4, 'Informations de contact');
//Email utilisateur
$mail_array[]  = array('required', 'true', 'Insérer Email utilisateur' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$mail_array[]  = array('remote', 'email#sys_users#mail', 'Cette adresse existe déja' );
$form->input('Email utilisateur', 'email', 'text', 6, null, $mail_array);
//Nom utilisature
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('number', '3', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('minlength', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, null, $tel_array);
$form->step_end();



//Button submit 
$form->button('Enregistrer Utilisateur');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
