<?php
if(MInit::form_verif('addtask', false))
{
	
  $posted_data = array(
   'description'      => Mreq::tp('description') ,
   'app'              => Mreq::tp('app') ,
   // 'app_sys'       => Mreq::tp('app_sys') ,
   'type_view'        => Mreq::tp('type_view') ,
   'id_checker_modul' => Mreq::tp('id_checker_modul') ,
   'id_modul'         => Mreq::tp('id_modul') ,
   'services'         => Mreq::tp('services') ,
   'sbclass'          => Mreq::tp('sbclass') ,
   'etat_workflow'    => Mreq::tp('etat_workflow') ,
   'action_list'      => Mreq::tp('action_list') ,
   'app_mere'         => Mreq::tp('app_mere') ,
   

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
  if($posted_data['id_checker_modul'] != MInit::cryptage($posted_data['id_modul'],1)){

    $empty_list .= "<li>Le ID Module n'est pas Valid</li>";
    $checker = 1;
  }
  if($posted_data['app'] == NULL){

    $empty_list .= "<li>Nom de l'application</li>";
    $checker = 1;
  }
  if($posted_data['type_view'] == NULL or !in_array($posted_data['type_view'],  array('list','formadd', 'formedit', 'formpers', 'profil', 'exec'))){

    $empty_list .= "<li>Type d'affichage</li>";
    $checker = 1;
  }
  if($posted_data['description'] == NULL){

    $empty_list .= "<li>Déscription</li>";
    $checker = 1;
  }
  /*if($posted_data['etat_desc'] == NULL){

    $empty_list .= "<li>Choisir Message à Afficher </li>";
    $checker = 1;
  }*/
  if($posted_data['action_list'] == NULL or !in_array($posted_data['action_list'],  array('Y', 'N'))){

    $empty_list .= "<li>Paramètre Action liste </li>";
    $checker = 1;
  }

  if(($posted_data['action_list'] != NULL AND in_array($posted_data['action_list'],  array('Y', 'N'))) AND $posted_data['app_mere'] == NULL){

    $empty_list .= "<li>Liste mère </li>";
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
  if($new_modul->save_new_task(Mreq::tp('id_modul'))){


    $new_modul->get_log(1);
  }else{

    $new_modul->get_log(0);
  }

} else {
  
  //check if the modul is the right one
 if(!MInit::crypt_tp('id', null, 'D') )
 {
   exit("3#<br>Les informations pour cette ligne sont erronées contactez l'administrateur");
 }
 view::load('modul_mgr','addtask');
}






?>