<?php 
if(!MInit::crypt_tp('id', null, 'D'))
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$service = new Mservice();
$service->id_service = Mreq::tp('id');

if($service->delete_service())
{
	exit("1#".$service->log);

}else{
	exit("0#".$service->log);
}