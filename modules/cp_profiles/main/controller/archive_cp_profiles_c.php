<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//Controller EXEC Form
$cp_profiles = new Mcp_profiles();
$cp_profiles->id_cp_profiles = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$cp_profiles->get_cp_profiles())
{  
   // returne message error red to cp_profiles 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//Execute Validate - delete


if($cp_profiles->archive_cp_profiles())
{
	exit("1#".$cp_profiles->log);

}else{
	exit("0#".$cp_profiles->log);
}