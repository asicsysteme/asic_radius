<?php
defined('_MEXEC') or die;
//Get all compte info 
 $info_ste = new MSte_info();
//Set ID of Module with POST id
 $info_ste->id_ste = 1;

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!$info_ste->get_ste_info())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_ste_info->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 

 ?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		
		<?php TableTools::btn_add('tdb', 'Tableau de bord', Null, $exec = NULL, 'reply');   ?>
			
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier les information de la société 
		<small>
			<i class="ace-icon fa fa-aechongle-double-right"></i>
		</small>

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

//	function ($id_form, $app_exec, $is_edit, $app_redirect, $is_wizard, $is_modal=null)
$form = new Mform('edit_info_ste', 'info_ste', $info_ste->g('id'), 'tdb',null);



$form->input_hidden('id', $info_ste->g('id'));
//$form->input_hidden('idc', Mreq::tp('idc'));
//$form->input_hidden('idh', Mreq::tp('idh'));


$ste_name_arr[]  = array('required', 'true', 'Insérer le nom' );
$ste_name_arr[]  = array('minlength', '4', 'Minimum 4 caractères' );
$form->input('Nom Ste', 'ste_name', 'text' ,9 , $info_ste->g('ste_name'), $ste_name_arr);




$ste_bp_arr[]  = array('required', 'true', 'Insérer la BP' );
$ste_bp_arr[]  = array('minlength', '2', 'Minimum 2 caractères' );
$form->input('BP', 'ste_bp', 'text' ,'2 is_number' , $info_ste->g('ste_bp'), $ste_bp_arr);



$ste_adresse_arr[]  = array('required', 'true', 'Insérer adresse' );
$ste_adresse_arr[]  = array('minlength', '6', 'Minimum 6 caractères' );
$form->input('Adresse', 'ste_adresse', 'text' ,9 , $info_ste->g('ste_adresse'), $ste_adresse_arr);


$ste_tel_arr[]  = array('required', 'true', 'Insérer le N° de Tél' );
$ste_tel_arr[]  = array('minlength', '8', 'Minimum 8 caractères' );
$form->input('Téléphone', 'ste_tel', 'text' ,'6 is_number' , $info_ste->g('ste_tel'), $ste_tel_arr);



$ste_fax_arr[]  = array('minlength', '8', 'Minimum 8 caractères' );
$form->input('Fax', 'ste_fax', 'text' ,'6 is_number' , $info_ste->g('ste_fax'), $ste_fax_arr);


$ste_email_arr[]  = array('required', 'true', 'Insérer adresse email' );
$ste_email_arr[]  = array('email', 'true', 'Email invalid' );
$ste_email_arr[]  = array('minlength', '8', 'Minimum 8 caractères' );
$form->input('Adresse Email', 'ste_email', 'text' ,'6 is_number' , $info_ste->g('ste_email'), $ste_email_arr);



$ste_if_arr[]  = array('required', 'true', 'Insérer le N° de NIF' );
$ste_if_arr[]  = array('minlength', '8', 'Minimum 8 caractères' );
$form->input('NIF', 'ste_if', 'text' ,'6' , $info_ste->g('ste_if'), $ste_if_arr);


$ste_rc_arr[]  = array('required', 'true', 'Insérer le N° de RC' );
$ste_rc_arr[]  = array('minlength', '8', 'Minimum 8 caractères' );
$form->input('RC', 'ste_rc', 'text' ,'6 is_number' , $info_ste->g('ste_rc'), $ste_rc_arr);


//$ste_website_arr[]  = array('required', 'true', 'Insérer le N° de Tél' );
$ste_website_arr[]  = array('minlength', '8', 'Minimum 6 caractères' );
$form->input('Site Web', 'ste_website', 'text' ,'6 is_number' , $info_ste->g('ste_website'), $ste_website_arr);


//Button submit 
$form->button('Enregistrer Modifications');

//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
