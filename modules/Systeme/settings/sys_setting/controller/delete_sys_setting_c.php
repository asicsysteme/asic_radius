<?php 
defined('_MEXEC') or die;
$info_setting = new Msetting();
$info_setting->id_setting = Mreq::tp('id');

if(md5(MInit::cryptage(Mreq::tp('id'),1)) == Mreq::tp('idc'))
{ 	
	exit('3#'.$info_setting->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

  //execute Delete returne false if error
if($info_setting->delete_sys_setting()){

	exit("1#".$info_setting->log);
}else{

	exit("0#".$info_setting->log);
}