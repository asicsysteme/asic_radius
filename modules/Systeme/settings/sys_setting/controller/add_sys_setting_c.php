<?php 
defined('_MEXEC') or die;
if(MInit::form_verif('add_sys_setting',false))
	{
		$posted_data = array(
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

		$new_setting = new  Msetting($posted_data);



  //execute Insert returne false if error
		if($new_setting->save_new_sys_setting()){

			exit("1#".$new_setting->log);
		}else{

			exit("0#".$new_setting->log);
		}
	}

view::load_view('add_sys_setting');