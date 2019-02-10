<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 03-02-2019
//Controller EDIT Form
if(MInit::form_verif('bloque_cp_users', false))
{
    $cp_users = new Mcp_users();
    $cp_users->id_cp_users = Mreq::tp('id');

    if(!MInit::crypt_tp('id', null, 'D') or !$cp_users->get_cp_users())
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id'                => Mreq::tp('id') ,
        'motif'             => Mreq::tp('motif') ,
        //Add posted data fields  here
            

    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if($posted_data["motif"] == NULL){
        $empty_list .= "<li>Motif de blocage</li>";
        $checker = 1;
    }
    //Add posted data fields verificator here


    $empty_list.= "</ul>";
    if($checker == 1)
    {
        exit("0#$empty_list");
    }

       
    //execute Update returne false if error
    if($cp_users->bloque_cp_users($posted_data['motif']))
    {
        exit("1#".$cp_users->log);
    }else{
        exit("0#".$cp_users->log);
    }
}

//No form posted show view
view::load_view('bloque_cp_users');







    ?>