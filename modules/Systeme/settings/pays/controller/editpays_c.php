<?php
defined('_MEXEC') or die;
if(MInit::form_verif('editpays',false))
{
	//Check if id is been the correct id compared with idc
   if(!MInit::crypt_tp('id', null, 'D'))
   {  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
   }
  $posted_data = array(
   'id'                 => Mreq::tp('id') ,
   'pays'               => Mreq::tp('pays') ,
   'nationalite'        => Mreq::tp('nationalite') ,
   'alpha'			      	=>Mreq::tp('alpha'),

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  
$checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
     if(!MInit::is_regex($posted_data['pays'])){

      $empty_list .= "<li>Pays non valide (a-z 1-9)</li>";
      $checker = 1;
    }

    if($posted_data['pays'] == NULL){

      $empty_list .= "<li>Pays</li>";
      $checker = 1;
    }

    if($posted_data['nationalite'] == NULL){

      $empty_list .= "<li>Nationalité</li>";
      $checker = 1;
    }

    if($posted_data['alpha'] == NULL){

      $empty_list .= "<li>Code du pays</li>";
      $checker = 1;
    }
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }
    
  
  //End check empty element

  $new_pays = new  Mpays($posted_data);
  $new_pays->id_pays = $posted_data['id'];

  //execute Insert returne false if error
  if($new_pays->edit_pays())
  {
    echo("1#".addslashes($new_pays->log));
  }else{
    echo("0#".addslashes($new_pays->log));
  }


}else{
  	
  view::load('Systeme/settings/pays','editpays');
}
?>