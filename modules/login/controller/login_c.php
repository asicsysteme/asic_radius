<?php
if(MInit::form_verif('login', false)) {

  	
  $posted_data = array(
   'user'                => Mreq::tp('user') ,
   'pass'               => Mreq::tp('pass') ,
    );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['user'] == NULL){

      $empty_list .= "<li>Nom d'utilisateur</li>";
      $checker = 1;
    }
    if($posted_data['pass'] == NULL){

      $empty_list .= "<li>Mot de passe</li>";
      $checker = 1;
    }
    
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element


  $new_login = new  MLogin($posted_data);

  //execute Login returne false if error
  if($new_login->do_login()){

    echo("1#".$new_login->log);
  }else{

    echo("0#".$new_login->log);
  }


} else {
   view::load('login','login');
}


?>