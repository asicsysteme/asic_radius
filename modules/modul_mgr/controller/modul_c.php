<?php
//First check target no Hack
if(!defined('_MEXEC'))die();

if(Mreq::tp('export') == 1 ){
	if(!MInit::crypt_tp('id', null, 'D'))
	{ 	
 	// returne message error red to client 
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	$export_modul = new Export_modul;
	$mod_id = Mreq::tp('id');
	if(!$export_modul->export_mod($mod_id))
	{
		exit('0#Erreur Opération');
	}else{
		exit('1#Opération réussie');
	}
	//exit('1#'.$export_modul->export_mod($mod_id));
}

view::load('modul_mgr','modul');


?>