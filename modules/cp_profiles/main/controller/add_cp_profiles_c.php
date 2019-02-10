<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//Controller ADD Form
if(MInit::form_verif('add_cp_profiles', false))
{

	$posted_data = array(

		'profile'         => Mreq::tp('profile') ,
		'quota'           => Mreq::tp('quota') ,
		'date_expir'      => Mreq::tp('date_expir') ,


	);


    //Check if array have empty element return list
    //for acceptable empty field do not put here
	$checker = null;
	$empty_list = "Les champs suivants sont obligatoires:\n<ul>";

			if($posted_data["profile"] == NULL){
                                    $empty_list .= "<li>Profile</li>";
                                    $checker = 1;
                              }



	$empty_list.= "</ul>";
	if($checker == 1)
	{
		exit("0#$empty_list");
	}



      //End check empty element
	$new_cp_profiles = new  Mcp_profiles($posted_data);



       //execute Insert returne false if error
	if($new_cp_profiles->save_new_cp_profiles())
	{
		exit("1#".$new_cp_profiles->log);
	}else{
		exit("0#".$new_cp_profiles->log);
	}


}

//No form posted show view
view::load_view('add_cp_profiles');







	?>