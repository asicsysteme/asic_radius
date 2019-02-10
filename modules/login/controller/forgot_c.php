<?php
if(Mreq::tp('verif')==1) {
	
  $posted_data = array(
   'email'                => Mreq::tp('email') ,
   'captcha'              => Mreq::tp('captcha') ,
    );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['email'] == NULL){

      $empty_list .= "<li>L'adresse email ou Pseudo</li>";
      $checker = 1;
    }
    if($posted_data['captcha'] == NULL ){

      $empty_list .= "<li>Le code Anti-robots</li>";
      $checker = 1;
    }
    if($posted_data['captcha'] != $_SESSION['Captcha']){

      $empty_list .= "<li>Le code Anti-robots Incorrect</li>";
      $checker = 1;
    }
    
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element


  $new_forgot = new  MLogin($posted_data);

  //execute Login returne false if error
  if($new_forgot->do_forgot()){

    echo("1#".$new_forgot->log);
  }else{

    echo("0#".$new_forgot->log);
  }


} else {
  view::load('login','login');
}


?>