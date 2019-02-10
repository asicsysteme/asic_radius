<?php
//SYS MRN ERP
// Modul: pays => Controller

defined('_MEXEC') or die;
if(MInit::form_verif('addpays',false))
{
	
  $posted_data = array(
   'pays'            => Mreq::tp('pays') ,
   'nationalite'     => Mreq::tp('nationalite') ,
   'alpha'           => Mreq::tp('alpha') ,
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
  
  

  //execute Insert returne false if error
  if($new_pays->save_new_pays()){

    echo("1#".$new_pays->log);
  }else{

    echo("0#".$new_pays->log);
  }


} else {
  view::load('Systeme/settings/pays','addpays');
}
?>