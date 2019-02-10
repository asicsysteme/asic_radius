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
					
		<?php TableTools::btn_add('compte', 'Page de profil', Null, $exec = NULL, 'reply'); 
		 ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Changer le mot de passe
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
		<?php echo ' ('.$info_user->Shw('fnom',1).'  '.$info_user->Shw('lnom',1).' -'.$info_user->id_user.'-)' . ' Service: '.$info_user->Shw('service_user',1);?>
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
$form = new Mform('changepass', 'changepass', $info_user->Shw('id',1), 'compte' , '0');
$wizard_array[] = array(1,'Etape 1','active');
$form->wizard_steps = $wizard_array;

$form->step_start(1, 'Changement du mot de passe');
$form->input_hidden('id', $info_user->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Ancien mot de passe
$password_array[]  = array('required', 'true', 'Insérer votre ancien mot de passe' );
$form->input('Ancien mot de passe', 'password', 'password', 6, null, $password_array);

//Nouveau Mot de passe
$pass_array[]  = array('required', 'true', 'Insérer le nouveau mot de passe' );
$pass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$pass_array[]  = array('remote', 'pass', 'Le mot de passe doit être alphanumériques compris entre 8 et 15 caractères');
$form->input('Nouveau mot de passe', 'pass', 'password', 6, null, $pass_array);


//Confirmation du nouveau Mot de passe
$cpass_array[]  = array('required', 'true', 'Confirmez le nouveau mot de passe' );
$cpass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$cpass_array[]  = array('equalTo', '#pass', 'Les deux mots de passe doivent être identiques');
$form->input('Confirmation du nouveau mot de passe', 'passc', 'password', 6, null, $cpass_array);

$form->step_end();

//Button submit 
$form->button('Enregistrer');
//Add JS function if need
//$form->js_add_funct('alert(\'Test alert\');');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
