<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addservices', false))
{
  
  $posted_data = array(
   'service'                    => Mreq::tp('service') ,
   'sign'                      => Mreq::tp('sign') ,
   
   
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['service'] == NULL){

      $empty_list .= "<li>Nom du service</li>";
      $checker = 1;
    }
    if($posted_data['sign'] == NULL){

      $empty_list .= "<li>Spécifier Oui ou Non</li>";
      $checker = 1;
    }    
  
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }


  $new_service = new  Mservice($posted_data);
  
  

  //execute Insert returne false if error
  if($new_service->save_new_service()){

    echo("1#".$new_service->log);

  }else{

    echo("0#".$new_service->log);
  }

}
  view::load_view('addservices');


