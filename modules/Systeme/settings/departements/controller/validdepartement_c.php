<?php 
//Get all departement info 
 $departement = new Mdept();
//Set ID of Module with POST id
 $departement->id_departement = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$departement->get_departement())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $departement->departement_info['etat'];


if($departement->valid_departement($etat))
{
	exit("1#".$departement->log);

}else{
	exit("0#".$departement->log);
}