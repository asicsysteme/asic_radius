$%modul% = new M%modul%();
$%modul%->id_%modul% = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$%modul%->get_%modul%())
{  
   // returne message error red to %modul% 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//Execute Validate - delete


if($%modul%->%task%())
{
	exit("1#".$%modul%->log);

}else{
	exit("0#".$%modul%->log);
}