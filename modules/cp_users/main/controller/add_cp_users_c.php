<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_users
//Created : 03-02-2019
//Controller ADD Form
if(MInit::form_verif('add_cp_users', false))
{

    $posted_data = array(           
        
        'nom'        => Mreq::tp('nom') ,
        'prenom'     => Mreq::tp('prenom') ,
        'email'      => Mreq::tp('email') ,
        'tel'        => Mreq::tp('tel') ,
        'profile'    => Mreq::tp('profile') ,
        'username'   => Mreq::tp('pseudo') ,
        'pass'       => Mreq::tp('pass') ,
        'passc'      => Mreq::tp('passc') ,  
        'form_id'    => Mreq::tp('form-id') ,  
        'date_expir' => Mreq::tp('date_expir') ,         
        
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if($posted_data["username"] == NULL){
        $empty_list .= "<li>Pseudo</li>";
        $checker = 1;
    }
    
    
    if($posted_data["pass"] == NULL){
        $empty_list .= "<li>Mot de passe</li>";
        $checker = 1;
    }
    if($posted_data["passc"] == NULL){
        $empty_list .= "<li>Confirmation Mot de passe</li>";
        $checker = 1;
    }
    if($posted_data["nom"] == NULL){
        $empty_list .= "<li>nom</li>";
        $checker = 1;
    }
    if($posted_data["prenom"] == NULL){
        $empty_list .= "<li>prenom</li>";
        $checker = 1;
    }
    if($posted_data["email"] == NULL){
        $empty_list .= "<li>email</li>";
        $checker = 1;
    }
    if($posted_data["tel"] == NULL){
        $empty_list .= "<li>tel</li>";
        $checker = 1;
    }
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
	$new_cp_users = new  Mcp_users($posted_data);



       //execute Insert returne false if error
	if($new_cp_users->save_new_cp_users())
	{
		exit("1#".$new_cp_users->log);
	}else{
		exit("0#".$new_cp_users->log);
	}


}

//No form posted show view
view::load_view('add_cp_users');







	?>