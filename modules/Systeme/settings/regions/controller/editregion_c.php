<?php
defined('_MEXEC') or die;
if(MInit::form_verif('editregion',false))
{
	//Check if id is been the correct id compared with idc
   if(!MInit::crypt_tp('id', null, 'D') )
   {  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
   }
  $posted_data = array(
   'id'                 => Mreq::tp('id') ,
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
  $new_region->id_region = $posted_data['id'];

  //execute Insert returne false if error
  if($new_region->edit_region())
  {
    echo("1#".addslashes($new_region->log));
  }else{
    echo("0#".addslashes($new_region->log));
  }


}else{
  	
  view::load('Systeme/settings/regions','editregion');
}






?>