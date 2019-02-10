<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS ASIC ERP
// Modul: modul_mgr
//Created : 30-11-2018
//Controller ADD Form
if(MInit::form_verif('addmodul_workflow', false))
{

	$posted_data = array(
		'modul_id'         => Mreq::tp('modul_id') ,
		'id_checker_modul' => Mreq::tp('id_checker_modul') ,
		'descrip'          => Mreq::tp('descrip') ,
		'code'             => Mreq::tp('code') ,
		'color'            => Mreq::tp('color') ,
		'etat_line'        => Mreq::tp('etat_line') ,
		'message_etat'     => Mreq::tp('message_etat') ,		
	);


        //Check if array have empty element return list
        //for acceptable empty field do not put here
		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";
		if($posted_data['id_checker_modul'] != MInit::cryptage($posted_data['modul_id'],1)){

			$empty_list .= "<li>Le ID Modul n'est pas Valid</li>";
			$checker = 1;
		}
		if($posted_data['descrip'] == NULL){

			$empty_list .= "<li>Déscription</li>";
			$checker = 1;
		}	
		if($posted_data['code'] == NULL){

			$empty_list .= "<li>Code Usage</li>";
			$checker = 1;
		}	
		if($posted_data['color'] == NULL){

			$empty_list .= "<li>Choisir une couleur</li>";
			$checker = 1;
		}		
		if(!in_array($posted_data['etat_line'], array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15))){

			$empty_list .= "<li>Choisir l'Etat de la ligne </li>";
			$checker = 1;
		}
		if($posted_data['message_etat'] == NULL){

			$empty_list .= "<li>Choisir Message à Afficher </li>";
			$checker = 1;
		}
			


		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}



       //End check empty element
		$new_modul_mgr = new  Mmodul($posted_data);


        //execute Insert returne false if error
		if($new_modul_mgr->add_modul_workflow()){

			exit("1#".$new_modul_mgr->log);
		}else{

			exit("0#".$new_modul_mgr->log);
		}


}

//No form posted show view
view::load_view('addmodul_workflow');







?>