<?php
defined('_MEXEC') or die;
if(MInit::form_verif('editville',false))
{
  //Check if id is been the correct id compared with idc
   if(!MInit::crypt_tp('id', null, 'D') )
   {  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
   }
  $posted_data = array(
   'id'                   => Mreq::tp('id') ,
   'ville'                => Mreq::tp('ville') ,
   'id_departement'       => Mreq::tp('id_departement') ,
   'latitude'             => Mreq::tp('latitude') ,
   'longitude'            => Mreq::tp('longitude') ,
   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if(!MInit::is_regex($posted_data['ville'])){

      $empty_list .= "<li>Ville non valide (a-z 1-9)</li>";
      $checker = 1;
    }

    if($posted_data['ville'] == NULL){

      $empty_list .= "<li>Ville</li>";
      $checker = 1;
    }
     if($posted_data['id_departement'] == NULL){

      $empty_list .= "<li>Département</li>";
      $checker = 1;
    }
    if($posted_data['latitude'] == NULL){

      $empty_list .= "<li>Latitude</li>";
      $checker = 1;
    }
    if($posted_data['longitude'] == NULL){

      $empty_list .= "<li>Longitude</li>";
      $checker = 1;
    }
   
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element

  $new_ville = new  Mville($posted_data);
  $new_ville->id_ville = $posted_data['id'];

  //execute Insert returne false if error
  if($new_ville->edit_ville())
  {
    echo("1#".addslashes($new_ville->log));
  }else{
    echo("0#".addslashes($new_ville->log));
  }


}else{
    
  view::load('Systeme/settings/villes','editville');
}






?>