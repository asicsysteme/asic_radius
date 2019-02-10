<?php 
defined('_MEXEC') or die;
$info_ville = new Mville();
$info_ville->id_ville = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_ville->get_ville())
{ 	
	exit('3#'.$info_ville->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

  //execute Delete returne false if error
if($info_ville->delete_ville()){

	exit("1#".$info_ville->log);
}else{

	exit("0#".$info_ville->log);
}