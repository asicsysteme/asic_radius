<?php

if(!MInit::crypt_tp('id', null, 'D') )
{  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$posted_data = array(
   'id'                     => Mreq::tp('id') ,  
  );
$new_modul = new Mmodul($posted_data);
$new_modul->modul_id = $posted_data['id'];
  //execute Delete returne false if error
  if($new_modul->delete_modul()){

    echo("1#".$new_modul->log);
  }else{

    echo("0#".$new_modul->log);
  }