<?php
if(MInit::form_verif('edituser', false))
{
	if(!MInit::crypt_tp('id', null, 'D'))
  {  
   // returne message error red to client 
    exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 
 $posted_data = array(
   'id'                 => Mreq::tp('id') ,
   'nom'                => Mreq::tp('pseudo') ,
   'lnom'               => Mreq::tp('lnom') ,
   'fnom'               => Mreq::tp('fnom') ,
   'mail'               => Mreq::tp('email') ,  
   'tel'                => Mreq::tp('tel') ,
   'service'            => Mreq::tp('service') ,
   'pass'               => Mreq::tp('pass') ,
   'passc'              => Mreq::tp('passc') ,
   'photo_id'           => Mreq::tp('photo-id') ,
   'form_id'            => Mreq::tp('form-id') ,
   'signature_id'       => Mreq::tp('signature-id') ,

   );


  //Check if array have empty element return list
  //for acceptable empty field do not put here



 $checker = null;
 $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
 if($posted_data['lnom'] == NULL){

  $empty_list .= "<li>Nom</li>";
  $checker = 1;
}
if($posted_data['fnom'] == NULL){

  $empty_list .= "<li>Prénom</li>";
  $checker = 1;
}
if($posted_data['nom'] == NULL){

  $empty_list .= "<li>Pseudo</li>";
  $checker = 1;
}
    /*if($posted_data['pass'] == NULL){

      $empty_list .= "<li>Mot de passe</li>";
      $checker = 1;
    }*/
    if($posted_data['pass'] != NULL && $posted_data['passc'] == NULL){

      $empty_list .= "<li>Confirmation mot de passe</li>";
      $checker = 1;
    }
    if($posted_data['mail'] == NULL){

      $empty_list .= "<li>Adresse Email</li>";
      $checker = 1;
    }
    if($posted_data['tel'] == NULL){

      $empty_list .= "<li>Téléphone</li>";
      $checker = 1;
    }
    if($posted_data['service'] == NULL){

      $empty_list .= "<li>Service</li>";
      $checker = 1;
    }
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    

  //End check empty element


    $new_user = new  Musers($posted_data);
    $new_user->exige_photo = false;
    $new_user->exige_form = false;
    //$new_user->exige_signature = true;
    $new_user->id_user = $posted_data['id'];

  //execute Insert returne false if error
    if($new_user->edit_user())
    {
      echo("1#".addslashes($new_user->log));
    }else{
      echo("0#".addslashes($new_user->log));
    }


  }else{
  	
    view::load('users','edituser');
  }






  ?>