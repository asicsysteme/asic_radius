<?php 
defined('_MEXEC') or die;

//Get pays info
$info_pays = new Mpays();
$info_pays->id_pays = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$info_pays->get_pays())
{ 	
	exit('3#'.$info_pays->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

  //execute Delete returne false if error
if($info_pays->delete_pays()){

	exit("1#".$info_pays->log);
}else{

	exit("0#".$info_pays->log);
}