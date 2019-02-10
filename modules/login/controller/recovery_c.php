<?php
//Check Valid link

//End Check Valid link


if(Mreq::tp('verif')==1) {
  //exit('0#exec');
	
  $posted_data = array(
   'pass'                => Mreq::tp('pass'),
   'passc'               => Mreq::tp('passc'),
   'token'               => Mreq::tp('token'),
    );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['pass'] == NULL){

      $empty_list .= "<li>Mot de passe</li>";
      $checker = 1;
    }
    if($posted_data['passc'] == NULL){

      $empty_list .= "<li>Confirmation mot de passe</li>";
      $checker = 1;
    }
    if($posted_data['pass'] !=  $posted_data['passc']){

      $empty_list .= "<li>Les deux mots de passe incompatibles</li>";
      $checker = 1;
    }
    if($posted_data['token'] == NULL || strlen($posted_data['token']) != 32){

      $empty_list .= "<li>Le token est Invalide</li>";
      $checker = 1;
    }
    
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element


  $new_recovery = new  Musers($posted_data);
  $new_recovery->token = $posted_data['token'];

  //execute Login returne false if error
  if($new_recovery->recovery_pass()){

    echo("1#".$new_recovery->log);
  }else{

    echo("0#".$new_recovery->log);
  }


} else {


  if (MReq::tg('token') != "0"){
    $token = MReq::tg('token');

    $check_token = new Musers();
    $check_token->token = $token;
    if(!$check_token->check_recovery_token())
    {
       exit($check_token->log);
    }
  
  }else{
    exit(MInit::msgbox('error_recovery'));
  }
  view::load('login','recovery');
}


?>