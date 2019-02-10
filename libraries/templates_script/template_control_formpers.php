if(MInit::form_verif('%task%', false))
{
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to client 
        exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id'                => Mreq::tp('id') ,
        //Add posted data fields  here
            

    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    //Add posted data fields verificator here


    $empty_list.= "</ul>";
    if($checker == 1)
    {
        exit("0#$empty_list");
    }



    //End check empty element
    $%task%_%modul% = new  M%modul%($posted_data);

    //Set ID of row to update
    $%task%_%modul%->id_%modul% = $posted_data['id'];
        
    //execute Update returne false if error
    if($%task%_%modul%->%task%_%modul%())
    {
        exit("1#".$%task%_%modul%->log);
    }else{
        exit("0#".$%task%_%modul%->log);
    }
}

//No form posted show view
view::load_view('%task%');







    ?>