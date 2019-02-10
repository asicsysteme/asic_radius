<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS ASIC ERP
// Modul: modul_mgr
//Created : 07-01-2019
//Controller EDIT Form
if(MInit::form_verif('editmodul_workflow', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id'                => Mreq::tp('id') ,
        //All items
        

    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    


    $empty_list.= "</ul>";
    if($checker == 1)
    {
        exit("0#$empty_list");
    }



    //End check empty element
    $edit_modul_mgr = new  Mmodul_mgr($posted_data);

    //Set ID of row to update
    $edit_modul_mgr->id_modul_mgr = $posted_data['id'];
        
    //execute Update returne false if error
    if($edit_modul_mgr->edit_modul_mgr())
    {
        exit("1#".$edit_modul_mgr->log);
    }else{

        exit("0#".$edit_modul_mgr->log);
    }


}

//No form posted show view
view::load_view('editmodul_workflow');







    ?>