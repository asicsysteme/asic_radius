<?php 
//SYS MRN ERP
// Modul: regions => Controller 
$region = new Mregion();
$region->id_region = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$region->get_region())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $region->region_info['etat'];

if($region->valid_region($etat))
{
	exit("1#".$region->log);

}
else
{
	exit("0#".$region->log);
}
