<?php
if(MInit::form_verif(false))
{
	
  $posted_data = array(
   'modul'                     => Mreq::tp('modul') ,
   'description'               => Mreq::tp('description') ,
   'app_modul'                 => Mreq::tp('app_modul') ,
   'pkg_id'                    => Mreq::tp('pkg-id') ,
   
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['modul'] == NULL){

      $empty_list .= "<li>Nom de module</li>";
      $checker = 1;
    }
    if($posted_data['description'] == NULL){

      $empty_list .= "<li>Déscription</li>";
      $checker = 1;
    }
    if($posted_data['app_modul'] == NULL){

      $empty_list .= "<li>Application de base</li>";
      $checker = 1;
    }
    
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element


  $new_modul = new  Mmodul($posted_data);
  //$new_modul->exige_pkg = true;
  

  //execute Insert returne false if error
  if($new_modul->save_new_modul()){

    echo("1#".$new_modul->log);
  }else{

    echo("0#".$new_modul->log);
  }


} else {
  view::load('modul_mgr','addaction');
}






?>