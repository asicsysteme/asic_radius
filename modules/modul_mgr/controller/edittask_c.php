<?php
defined('_MEXEC') or die;
if(MInit::form_verif('edittask', false))
{
	
  $posted_data = array(
   'description'      => Mreq::tp('description') ,
   'app'              => Mreq::tp('app') ,
   //'app_sys'        => Mreq::tp('app_sys') ,
   'type_view'        => Mreq::tp('type_view') ,
   'sbclass'          => Mreq::tp('sbclass') ,
   'id_checker_modul' => Mreq::tp('id_checker_modul') ,
   'id_modul'         => Mreq::tp('id_modul') ,
   'services'         => Mreq::tp('services') ,
   'id_checker'       => Mreq::tp('id_checker') ,
   'message_class'    => Mreq::tp('message_class') ,
   'etat_desc'        => Mreq::tp('etat_desc') ,
   'id_app'           => Mreq::tp('id_app') ,
   
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
  if($posted_data['id_checker_modul'] != MD5(MInit::cryptage($posted_data['id_modul'],1))){

    $empty_list .= "<li>Le ID Module n'est pas Valid</li>";
    $checker = 1;
  }
  if($posted_data['app'] == NULL){

    $empty_list .= "<li>Nom de l'application</li>";
    $checker = 1;
  }
  if($posted_data['type_view'] == NULL or !in_array($posted_data['type_view'],  array('list','formadd' , 'formedit', 'profil', 'exec'))){

    $empty_list .= "<li>Type d'affichage</li>";
    $checker = 1;
  }
  if($posted_data['description'] == NULL){

    $empty_list .= "<li>Déscription</li>";
    $checker = 1;
  }
  /*if(!in_array($posted_data['app_sys'],  array(0,1))){

    $empty_list .= "<li>Type de l'Application n'est pas valid</li>";
    $checker = 1;
  }
*/


  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
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

  if($new_modul->edit_exist_task($posted_data['id_modul'])){

    echo("1#".$new_modul->log);
  }else{

    echo("0#".$new_modul->log);
  }


} else {
  view::load('modul_mgr','edittask');
}






?>