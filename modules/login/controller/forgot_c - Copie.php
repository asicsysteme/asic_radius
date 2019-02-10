<?php
if(MReq::tp('check')==1) {


	$message = "";
	$class = "";	
	$output = "";
	global $db;
	model::load('login','login');

//check user exist
	if(!check_email(MReq::tp('email'))){
		$message = "Cette Adresse (".MReq::tp('email').") E-mail n'exist pas";
		$class = "alert-error";	
		$output = $message;
		exit("3# $output");
	}

// Check Captcha Code

	if(!check_captcha(MReq::tp('captcha'))){
		$message = "Le code anti-robots est incorrect";
		$class = "alert-error";	
		$output = $message;

		exit("2# $output");
	}

//All is OK send email
	if(!forgot(MReq::tp('email'),MReq::tp('captcha'))){
		$message = "Erreur Système";
		$class = "alert-error";	
		$output = $message;
		exit("4# $output" );
	} else{
		$message = "Un message de récupération est envoyé dans votre boite";
		$class = "alert-success";
	    $output = "<div class=\"alert $class \"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><i class=\"icon-remove\"></i></button><strong><i class=\"icon-remove\"></i> OK ! </strong><br>$message</div>";
		exit("5# $output");
	}

	

} 



?>