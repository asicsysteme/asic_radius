<?php
//Check Valid link

if (MReq::tg('token') != "0"){
	$token=	MReq::tg('token');
	
	if(!Mpswrecovery::check_token($token)){
		exit(MInit::msgbox('error_recovery'));
	}
}else{
	exit(MInit::msgbox('error_recovery'));
}
//End Check Valid link

//Reset Password
if(MReq::tp('check')==1){


     //check token in post
    if(!Mpswrecovery::check_token($token)){
		$message = "Vous avez tentez de changer le mot de passe utilison un token non valide !";
		$class = "alert-error";	
		$output = "$message";
		exit("1# $output");
	}
	//check passwords confirmed
	if(MReq::tp('psw1') != MReq::tp('psw2')){

		$message = "Les mots de passe ne sont pas compatible !";
		$class = "alert-error";	
		$output = "$message";
		exit("1# $output");

	}
	
	$posted_data = array('token' => Mreq::tp('token') ,
		'pass' => Mreq::tp('psw1') ,  
		);	

	$new_pass = new  Mpswrecovery($posted_data);
	if($new_pass->reset_passwors()){

		exit('5#Votre mot de passe est bien changé redirection vers page de connexion.');

	}else{


	} 





}else{
	view::load('login','recovery');
}
//End Reset Password




?>