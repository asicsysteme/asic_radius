<?php 
//Get all ville info 
 $ville = new Mville();
//Set ID of Module with POST id
 $ville->id_ville = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$ville->get_ville())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $ville->ville_info['etat'];


if($ville->valid_ville($etat))
{
	exit("1#".$ville->log);

}else{
	exit("0#".$ville->log);
}