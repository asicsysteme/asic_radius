<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//View
//Get all cp_profiles info 
$info_cp_profiles = new Mcp_profiles();
//Set ID of Module with POST id
$info_cp_profiles->id_cp_profiles = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
if(!MInit::crypt_tp('id', null, 'D') or !$info_cp_profiles->get_cp_profiles())
{ 	
 	// returne message error red to client 
 	exit('3#'.$info_cp_profiles->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('cp_profiles','Liste des Profiles CP', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier le Profile: <?php $info_cp_profiles->s('profile')?>
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
$form = new Mform('edit_cp_profiles', 'edit_cp_profiles', '1', 'cp_profiles', '0', null);
$form->input_hidden('id', $info_cp_profiles->g('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));


//Profile ==> 
$array_region[]= array("required", "true", "Insérer Profile ...");
$form->input("Profile", "profile", "text" ,"9", $info_cp_profiles->g("profile"), $array_region , null, $readonly = null);
$quota_opt = array('134217728' => '1G' , '65536000' => '500M', '26214400' => '200M' );
$form->select('Quota d\'utilisation', 'quota', 2, $quota_opt, $indx = NULL ,$selected = $info_cp_profiles->g("quota_s"), $multi = NULL);
$date_expir_opt = array('O' => 'OUI' , 'N' => 'NON' );
$form->select('Exige date expiration', 'date_expir', 2, $date_expir_opt, $indx = NULL ,$selected = $info_cp_profiles->g("date_expir"), $multi = NULL);


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
    
//JS Bloc    

});
</script>	

		