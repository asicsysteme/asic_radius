<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 03-02-2019
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('cp_users','Liste des Utilisateurs CP', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un Utilisateur CP
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- Bloc form Add Devis-->
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
 
$form = new Mform('add_cp_users', 'add_cp_users', '', 'cp_users', '0', null);

//Date Example
//Input Example
//$form->input('Label field', 'field', 'text' ,'class', '0', null, null, $readonly = null);
//For more Example see form class

$form->bloc_title('Informatios générales');
//Form
$form->input('Formulaire', 'form', 'file', 6, null, null);
$form->file_js('form', 10000000, 'pdf');
//nom ==> 
$array_nom[]= array("required", "true", "Insérer nom ...");
$form->input("nom", "nom", "text" ,"9", null, $array_nom, null, $readonly = null);
//prenom ==> 
$array_prenom[]= array("required", "true", "Insérer prenom ...");
$form->input("prenom", "prenom", "text" ,"9", null, $array_prenom, null, $readonly = null);
//Profile
$profile_array[]  = array('required', 'true', 'Choisir le Profile' );
$form->select_table('Profile', 'profile', 6, 'cp_profiles', 'id', 'profile' , 'profile', $indx = '------' ,$selected=NULL,$multi=NULL, $where='etat = 1', $profile_array);
//Nom utilisature
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('number', '3', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('remote', 'tel#radcheck#tel', 'Ce Numéro existe déja' );
$tel_array[]  = array('minlength', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, null, $tel_array);

$form->bloc_title('Informatios de connexion');

//Email utilisateur
$mail_array[]  = array('required', 'true', 'Insérer Email utilisateur' );
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$mail_array[]  = array('remote', 'email#radcheck#email', 'Cette adresse existe déja' );
$form->input('Email utilisateur', 'email', 'text', 6, null, $mail_array);
//Pseudo utilisateur
$pseudo_array[]  = array('required', 'true', 'Insérer pseudo utilisateur' );
$pseudo_array[]  = array('minlength', '3', 'Minimum 3 caractères' );
$pseudo_array[]  = array('remote', 'pseudo#radcheck#username', 'Ce pseudo existe déja' );
$form->input('Pseudo utilisateur', 'pseudo', 'text', 6, null, $pseudo_array);
//Mot de passe
$pass_array[]  = array('required', 'true', 'Insérer le mot de passe' );
$pass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$pass_array[]  = array('remote', 'pass', 'Le mot de passe doit être alphanumériques compris entre 8 et 15 caractères');
//Confirm MDP
$form->input('Mot de passe', 'pass', 'password', 6, null, $pass_array);
$cpass_array[]  = array('required', 'true', 'Confirmez le mot de passe' );
$cpass_array[]  = array('minlength', '8', 'Minimum 8 caractères' );
$cpass_array[]  = array('equalTo', '#pass', 'les deux mots de passe incompatibles');
$form->input('Confirmation mot de passe', 'passc', 'password', 6, null, $cpass_array);
//Expire date
$array_date_expir[]= array('required', 'true', 'Insérer la date de ...');
$default_date = date('d-m-Y', strtotime('+1 days'));

$form->bloc_title('Date expiration (Utilisé si le profile exige une date d\'expiration)');
$form->input_date('Date expiration', 'date_expir', 4, $default_date, $array_date_expir, null);

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

		