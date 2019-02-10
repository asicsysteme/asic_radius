<?php
if(!MInit::crypt_tp('id', null, 'D'))
  {  
   // returne message error red to client 
    exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
view::load_view('history');

?>

