<?php 
defined('_MEXEC') or die;
if(MInit::form_verif('edittaskaction', false))
{
	if(!MInit::crypt_tp('id', null, 'D'))
  {  
   // returne message error red to client 
    exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
  }
	
  $posted_data = array(
   'name_task'         => Mreq::tp('name_task') ,
   'name_checker_task' => Mreq::tp('name_checker_task') ,
   'id_task'           => Mreq::tp('id_task') ,
   'app'               => Mreq::tp('app') ,
   'id_checker_task'   => Mreq::tp('id_checker_task') ,
   'description'       => Mreq::tp('description') ,
   'mode_exec'         => Mreq::tp('mode_exec') ,
   'services'          => Mreq::tp('services') ,
   'class'             => Mreq::tp('class') ,
   'etat_line'         => Mreq::tp('etat_line') ,
   'etat_desc'         => Mreq::tp('etat_desc') ,
   'message_class'     => Mreq::tp('message_class') ,
   'notif'             => Mreq::tp('notif') ,
   'id'                => Mreq::tp('id'),
  );


  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['id_checker_task'] != MInit::cryptage($posted_data['id_task'],1)){

      $empty_list .= "<li>Le ID Task n'est pas Valid</li>";
      $checker = 1;
    }
    
    if($posted_data['description'] == NULL){

      $empty_list .= "<li>Déscription</li>";
      $checker = 1;
    }
    if($posted_data['mode_exec'] == NULL){

      $empty_list .= "<li>Mode d'execution</li>";
      $checker = 1;
    }
    
    if($posted_data['class'] == NULL){

      $empty_list .= "<li>Choisir Icone </li>";
      $checker = 1;
    }
    if($posted_data['etat_line'] == NULL && !is_numeric($posted_data['etat_line'])){

      $empty_list .= "<li>Choisir l'Etat de la ligne ".$posted_data['etat_line'].'  '.Mreq::tp('etat_line') ." value </li>";
      $checker = 1;
    }
    if($posted_data['etat_desc'] == NULL){

      $empty_list .= "<li>Choisir Message à Afficher </li>";
      $checker = 1;
    }
    if($posted_data['message_class'] == NULL){

      $empty_list .= "<li>Choisir La coleur du message </li>";
      $checker = 1;
    }
    
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element


  $edit_task_action = new  Mmodul($posted_data);
  $edit_task_action->id_task_action = $posted_data['id'];
  

  //execute Insert returne false if error
  if($edit_task_action->edit_task_action()){

    exit("1#".$edit_task_action->log);
  }else{

    exit("0#".$edit_task_action->log);
  }


} else {
  view::load('modul_mgr','edittaskaction');
}