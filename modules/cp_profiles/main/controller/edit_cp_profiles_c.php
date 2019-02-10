<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//Controller EDIT Form
if(MInit::form_verif('edit_cp_profiles', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id'                => Mreq::tp('id') ,
        //All items
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
    $edit_cp_profiles = new  Mcp_profiles($posted_data);

    //Set ID of row to update
    $edit_cp_profiles->id_cp_profiles = $posted_data['id'];
        
    //execute Update returne false if error
    if($edit_cp_profiles->edit_cp_profiles())
    {
        exit("1#".$edit_cp_profiles->log);
    }else{

        exit("0#".$edit_cp_profiles->log);
    }


}

//No form posted show view
view::load_view('edit_cp_profiles');







    ?>