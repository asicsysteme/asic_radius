<?php 
defined('_MEXEC') or die;
if(MInit::form_verif('edit_sys_setting',false))
	{
		if(!MInit::crypt_tp('id', null, 'D') )
			{  
   // returne message error red to client 
				exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
			}
			$posted_data = array(
				'id'      => Mreq::tp('id') ,
				'key'     => Mreq::tp('key') ,
				'value'   => Mreq::tp('value') ,
				'comment' => Mreq::tp('comment') ,
				'modul'   => Mreq::tp('modul') ,

			);


  //Check if array have empty element return list
  //for acceptable empty field do not put here



			$checker = null;
			$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
			if(!MInit::is_regex($posted_data['key']) OR $posted_data['key'] == NULL){

				$empty_list .= "<li>Clé non valide (a-z 1-9)</li>";
				$checker = 1;
			}

			if($posted_data['value'] == NULL){

				$empty_list .= "<li>Valeur</li>";
				$checker = 1;
			}

			if($posted_data['comment'] == NULL){

				$empty_list .= "<li>Commentaire</li>";
				$checker = 1;
			}

			if($posted_data['modul'] == NULL){

				$empty_list .= "<li>Module</li>";
				$checker = 1;
			}

			$empty_list.= "</ul>";
			if($checker == 1)
			{
				exit("0#$empty_list");
			}



  //End check empty element

			$edit_setting = new  Msetting($posted_data);
			$edit_setting->id_setting = $posted_data['id'];



  //execute Insert returne false if error

			if($edit_setting->edit_sys_setting()){

				exit("1#".$edit_setting->log);
			}else{

				exit("0#".$edit_setting->log);
			}
	}
//When no form go to view
view::load_view('edit_sys_setting');