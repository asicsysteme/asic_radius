<?php
if(MInit::form_verif('changepass', false))
{

 if(!MInit::crypt_tp('id', null, 'D'))
  {  
   // returne message error red to client 
    exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }

	
  $posted_data = array(
   'id'                 => Mreq::tp('id') ,
   'password'			=> Mreq::tp('password') ,
   'pass'               => Mreq::tp('pass') ,
   'passc'              => Mreq::tp('passc') ,

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if($posted_data['password'] == NULL){

      $empty_list .= "<li>Ancien mot de passe</li>";
      $checker = 1;
    }
    if($posted_data['pass'] == NULL){

      $empty_list .= "<li>Nouveau mot de passe</li>";
      $checker = 1;
    }
    if($posted_data['passc'] == NULL){

      $empty_list .= "<li>Confirmation nouveau mot de passe</li>";
      $checker = 1;
    }
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    //End check empty element


  $info_user = new  Musers($posted_data);
  $info_user->id_user = $posted_data['id'];

  //execute ChaPassnge returne false if error
  if($info_user->change_pass()){

    echo("1#".$info_user->log);
  }else{

    echo("0#".$info_user->log);
  }


} else {
  view::load('users','changepass');
}






?>