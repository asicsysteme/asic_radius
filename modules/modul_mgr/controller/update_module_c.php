<?php 
$update_modul = new Update_modul;
$mod_id = Mreq::tp('id');

//Set ID of Module with POST id
$update_modul->id_modul = $mod_id;

//Check if Post ID <==> Post idc or get_prm return false. 
if(!MInit::crypt_tp('id', null, 'D')  or !$update_modul->get_modul())
{ 	
 	//returne message error red to client 
	exit('3#'.$update_modul->log .'<br>Les informations sont erronées contactez l\'administrateur');
}
//First Bkp the exist modul with old inname
$export_modul = new Export_modul;
	$mod_id = Mreq::tp('id');
	if(!$export_modul->export_mod($mod_id, 'bkp'))
	{
		exit('0#Erreur Opération BKP');
	}

if(!$update_modul->Update_module($mod_id, $update_modul->modul_info['modul']))
{
	exit('0#Erreur Opération '.$update_modul->log);
}else{
	exit('1#Opération réussie');
}