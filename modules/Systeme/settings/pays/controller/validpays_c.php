<?php 
//Get Pays info
$pays = new Mpays();
$pays->id_pays = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$pays->get_pays())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $pays->pays_info['etat'];

if($pays->valid_pays($etat))
{
	exit("1#".$pays->log);

}else{
	exit("0#".$pays->log);
}