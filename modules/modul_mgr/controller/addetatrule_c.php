<?php
if(MInit::form_verif('addetatrule', false))
{
	
  $posted_data = array(
   'name_task'         => Mreq::tp('name_task') ,
   'name_checker_task' => Mreq::tp('name_checker_task') ,
   'id_task'           => Mreq::tp('id_task') ,
   'app'               => Mreq::tp('app') ,
   'id_checker_task'   => Mreq::tp('id_checker_task') ,
   'description'       => Mreq::tp('description') ,
   'mode_exec'         => Mreq::tp('mode_exec') ,
   'services'          => Mreq::tp('services') ,
   'etat_line'         => Mreq::tp('etat_line') ,
   'etat_desc'         => Mreq::tp('etat_desc') ,
   'message_class'     => Mreq::tp('message_class') ,
  
  );



  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['id_checker_task'] != MInit::cryptage($posted_data['id_task'],1)){

      $empty_list .= "<li>Le ID Task n'est pas Valid</li>";
      $checker = 1;
    }
    if($posted_data['name_checker_task'] != MInit::cryptage($posted_data['name_task'],1)){

      $empty_list .= "<li>Le Nom Task n'est pas Valid</li>";
      $checker = 1;
    }
    if($posted_data['description'] == NULL){

      $empty_list .= "<li>Déscription</li>";
      $checker = 1;
    }
    
    if($posted_data['services'] == NULL){

      $empty_list .= "<li>Choisir un ou plusieur services</li>";
      $checker = 1;
    }
   
    if(!in_array($posted_data['etat_line'], array(0,1,2,3,4,5))){

      $empty_list .= "<li>Choisir l'Etat de la ligne ".$posted_data['etat_line'].'  '.Mreq::tp('etat_line') ." value </li>";
      $checker = 1;
    }
    if($posted_data['etat_desc'] == NULL){

      $empty_list .= "<li>Choisir Message à Afficher </li>";
      $checker = 1;
    }
    if($posted_data['message_class'] == NULL){

      $empty_list .= "<li>Choisir La couleur du message </li>";
      $checker = 1;
    }
    
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element


  $new_task_action = new  Mmodul($posted_data);
  //$new_modul->exige_pkg = true;
  

  //execute Insert returne false if error
  if($new_task_action->add_rule_wf()){

    exit("1#".$new_task_action->log);
  }else{

    exit("0#".$new_task_action->log);
  }


} else {
  view::load('modul_mgr','addetatrule');
}






?>