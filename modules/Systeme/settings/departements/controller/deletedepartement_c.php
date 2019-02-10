<?php 
defined('_MEXEC') or die;
$info_departement = new Mdept();
$info_departement->id_departement = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_departement->get_departement())
{ 	
	exit('3#'.$info_departement->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

  //execute Delete returne false if error
if($info_departement->delete_departement()){

	exit("1#".$info_departement->log);
}else{

	exit("0#".$info_departement->log);
}