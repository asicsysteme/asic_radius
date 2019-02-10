if(MInit::form_verif('%task%', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id'                => Mreq::tp('id') ,
        //All items
        %lines_action%

    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    %lines_action_check%


    $empty_list.= "</ul>";
    if($checker == 1)
    {
        exit("0#$empty_list");
    }



    //End check empty element
    $edit_%modul% = new  M%modul%($posted_data);

    //Set ID of row to update
    $edit_%modul%->id_%modul% = $posted_data['id'];
        
    //execute Update returne false if error
    if($edit_%modul%->edit_%modul%())
    {
        exit("1#".$edit_%modul%->log);
    }else{

        exit("0#".$edit_%modul%->log);
    }


}

//No form posted show view
view::load_view('%task%');







    ?>