
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addregion',false))
{
	
  $posted_data = array(
   'region'             => Mreq::tp('region') ,
   'id_pays'            => Mreq::tp('id_pays') ,

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
     if(!MInit::is_regex($posted_data['region'])){

      $empty_list .= "<li>Région non valide (a-z 1-9)</li>";
      $checker = 1;
    }

    if($posted_data['region'] == NULL){

      $empty_list .= "<li>Région</li>";
      $checker = 1;
    }

    if($posted_data['id_pays'] == NULL){

      $empty_list .= "<li>Pays</li>";
      $checker = 1;
    }
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element

 $new_region = new  Mregion($posted_data);
  
  

  //execute Insert returne false if error
  if($new_region->save_new_region()){

    echo("1#".$new_region->log);
  }else{

    echo("0#".$new_region->log);
  }


} else {
  view::load('Systeme/settings/regions','addregion');
}





?>