<?php 
defined('_MEXEC') or die;
$info_region = new Mregion();
$info_region->id_region = Mreq::tp('id');

if(md5(MInit::cryptage(Mreq::tp('id'),1)) == Mreq::tp('idc'))
{ 	
	exit('3#'.$info_region->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

  //execute Delete returne false if error
if($info_region->delete_region()){

	exit("1#".$info_region->log);
}else{

	exit("0#".$info_region->log);
}