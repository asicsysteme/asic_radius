<?php
defined('_MEXEC') or die;
if(MInit::form_verif('editservices', false))
{
  
  $posted_data = array(
   'service'                    => Mreq::tp('service') ,
   'sign'                      => Mreq::tp('sign') ,
   'id'                      => Mreq::tp('id') ,
   
   
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

      $empty_list .= "<li>Spécifier 0 ou 1</li>";
      $checker = 1;
    }    
  
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }


  //End check empty element


  $new_service = new  Mservice($posted_data);
  $new_service->id_service = $posted_data['id'];
  
  
  

  //execute Insert returne false if error
  if($new_service->update_service()){

    exit("1#".$new_service->log);

  }else{

    exit("0#".$new_service->log);
  }

} else {
  view::load_view('editservices');
}


?>