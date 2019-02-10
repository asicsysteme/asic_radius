<?php
//If session exist exit

if(MReq::tp('verif')==1){

	if(empty($_SERVER['HTTP_X_REQUESTED_WITH'])
	
		exit('no way !!!');
	}	

	$message = "";
	$class = "";	
	$output = "<div class=\"alert $class \"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><i class=\"icon-remove\"></i></button><strong><i class=\"icon-remove\"></i>Erreur! </strong>$message</div>";

	global $db;
	model::load('login','login');

//check user exist
	if(!login_check_user_exist(MReq::tp('user'))){
		$message = "Ce nom d'utilisateur n'exist pas";
		$class = "alert-error";	
		$output = "$message";
		exit("1# $output");
	}
//check user CRC
	if(!login_check_user_crc(MReq::tp('user'))){
		$message = "Le nombre des tentatives est écoulé </br> Contactez l'administrateur";
		$class = "alert-error";
		$output = "$message";
		exit("2# $output");
	}
//Check user signature
	if(!login_check_user_sign(MReq::tp('user'),MReq::tp('pass'))){
		$message = "Votre signature n'est pas enregistrée </br> Contactez l'administrateur";
		$class = "alert-error";	
		$output = "$message";
		exit("3# $output");
	}

//Password ok and validat login 
	if(!login_check_user_pass(MReq::tp('user'),MReq::tp('pass'),MReq::tp('token'))){
		global $db;	
		$message = "Le mot de passe est incorrect </br> Contactez l'administrateur";
		$class = "alert-error";	
		$output = "$message";
        exit("4# $output"); //wrong Pass 
}else{
	$user = MReq::tp('user');	
	$username = $db->QuerySingleValue0("SELECT CONCAT(lnom,' ',fnom) FROM users_sys where nom='$user'");	
	$message = "Bienvenue <strong>$username </strong></br> Vous serez rédiriger dans qulques instants";
	$class = "alert-success";
	$output = "<div class=\"alert $class \"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><i class=\"icon-remove\"></i></button><strong><i class=\"icon-remove\"></i> OK ! </strong><br>$message</div>";	
 exit("5# $output");//All is ok
}






}else{
	
	view::load('login','login');
}

?>