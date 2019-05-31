<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 29-05-2019
//Controller EXEC Form
$cp_users = new Mcp_users();
$cp_users->id_cp_users = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$cp_users->get_cp_users())
{  
   // returne message error red to cp_users 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//Execute Validate - delete


if($cp_users->deblpoquer_cp_user())
{
	exit("1#".$cp_users->log);

}else{
	exit("0#".$cp_users->log);
}