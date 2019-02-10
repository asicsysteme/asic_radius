<?php 
//if(!addmail(tp('name'),tp('email'),tp('to'),tp('sujet'),tp('message'),tp('ip')))
function forgot($username,$captcha){
// Get contact & service:
global $db;
// Mail user
	  if (! $db->Query("SELECT * FROM users_sys where nom='$username'")) $db->Kill($db->Error());
		  if ( $db->RowCount() > 0 ){
			// si oui vérifier le captcha
		 
				   $array = $db->RowArray();
		           $user_name=$array['nom'];
				   $user_mail=$array['mail'];
				   $user_fname=$array['fnom'];
				   $user_lname=$array['lnom'];
		           $user_id=$array['id'];
                   $tkenforgot=md5($_SESSION['Captcha']);
				   $urlrecovery='http://'.$_SERVER['HTTP_HOST'].'?_tsk=recovery&token='.$tkenforgot;
		  }

global $db;

$values["token"] = MySQL::SQLValue($tkenforgot);
$values["user"] = MySQL::SQLValue($user_id);
$values["etat"] = MySQL::SQLValue(0);
$values["dat"] = 'CURRENT_TIMESTAMP';
$values["expir"] = 'DATE_ADD(CURRENT_TIMESTAMP,INTERVAL 2 DAY)';
$values["ip"] = MySQL::SQLValue($_SERVER['REMOTE_ADDR']);



// Execute the insert
$result = $db->InsertRow("forgot", $values);

// if(!$result){ exit($db->Error());}else{ return true;}
//sendmail($nom,$mail,$tonom,$tomail,$sujet,$message);

include_once MSG_REP.'forgot.php';
require LIB_REP.'mailer/class.phpmailer.php';
require LIB_REP.'mailer/class.smtp.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "mail.onape.td";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 26;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "admin@onape.td";
//Password to use for SMTP authentication
$mail->Password = ",.onape$7*";
$mail->CharSet = 'UTF-8';
//Set who the message is to be sent from
$mail->setFrom('admin@onape.td', 'Administrateur Système');
//Set an alternative reply-to address
//$mail->addReplyTo('rachid@atelsolution.com', $tonom);
//Set who the message is to be sent to
$mail->addAddress($user_mail, $user_fname.'  '.$user_lname);
//Set the subject line
$mail->Subject = 'Demande de réinitialisation du mot de passe Système ';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
$mail->Body = $fullmesage;
$mail->IsHTML(true);
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
           return false;
} else {
            return true;
}


if (!$result) {
	       $db->Kill($result);
	        return false;
            } else {
            return true;
           }	      
		  
		   
	   
       
	
	
}






?>