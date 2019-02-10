<?php 
defined('_MEXEC') or die;
$info_task = new Mmodul();
$info_task->id_task = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')  or !$info_task->get_task())
{ 	
	exit('3#'.$info_task->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

  //execute Delete returne false if error
if($info_task->delete_task()){

	exit("1#".$info_task->log);
}else{

	exit("0#".$info_task->log);
}