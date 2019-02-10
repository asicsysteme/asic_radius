<?php 

//Get all compte info 
 $info_user = new Musers();
//Set ID of Module with POST id
 $info_user->id_user = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_user->get_user())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//exit("1#".$etat.' '.$info_user->id_cp_user);
if($info_user->delete_user())
{
	exit("1#".$info_user->log);

}else{
	exit("0#".$info_user->log);
}