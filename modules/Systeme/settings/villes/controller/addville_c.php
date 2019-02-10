
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addville',false))
{
  
  $posted_data = array(
   'ville'                => Mreq::tp('ville') ,
   'latitude'             => Mreq::tp('latitude') ,
   'longitude'            => Mreq::tp('longitude') ,
   'id_departement'       => Mreq::tp('id_departement') ,

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
    if($posted_data['latitude'] == NULL){

      $empty_list .= "<li>Latitude</li>";
      $checker = 1;
    }
    if($posted_data['longitude'] == NULL){

      $empty_list .= "<li>Longitude</li>";
      $checker = 1;
    }
    if($posted_data['id_departement'] == NULL){

      $empty_list .= "<li>Département</li>";
      $checker = 1;
    }
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element

 $new_ville = new  Mville($posted_data);
  
  

  //execute Insert returne false if error
  if($new_ville->save_new_ville()){

    echo("1#".$new_ville->log);
  }else{

    echo("0#".$new_ville->log);
  }


} else {
  view::load('Systeme/settings/villes','addville');
}





?>