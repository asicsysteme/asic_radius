<?php 
// Valider le form Forgot    
        //vèrifier si le nom d'utilisateur exist
	  global $db;
	  $username = $_REQUEST['username'];
      $captcha = $_REQUEST['captcha'];
	  if (! $db->Query("SELECT * FROM users_sys where nom='".$username."'")) $db->Kill('');
		  if ( $db->RowCount() > 0 ){
			// si oui vérifier le captcha
		  if(!valid_captcha($captcha)){
			 echo 2;
			 exit;
			  }else{
				   $array = $db->RowArray();
		           $user_name=$array['nom'];
				   $user_mail=$array['mail'];
				   $user_fname=$array['fnom'];
				   $user_lname=$array['lnom'];
		           $user_id=$array['id'];
                   $tkenforgot=md5($_SESSION['Captcha']);
				   $urlrecovery='http://localhost/E-ONAPE/?_tsk=recovery&token='.$tkenforgot;
				   include_once MSG_REP.'forgot.php';
				  
				  $args = array(
                             'isHTML'  => true,
                             'debug'   => true,
                             'to'      => $user_fname.'  '.$user_lname.'<'.$user_mail.'>',
                             'from'    => utf8_decode('Administrateur Système').'<admin@dctchad.com>',
                            //'replyTo' => 'replyto@email.com',
                             'subject' => 'Demande de réinitialisation du mot de passe Système ',
                             'message' => $fullmesage,
                             'charset' => 'utf-8',
                             'errorMsg'=> 'Error!',
                             'successMsg' => '',
                             );
                
                 $email = new Mymail($args);
                 $email->send();
				 $sql = "INSERT INTO `forgot` (`id`, `user`, `etat`, `dat`,expir) VALUES ('".$tkenforgot."', '".$user_name."',                  0,CURRENT_TIMESTAMP,DATE_ADD(CURRENT_TIMESTAMP,INTERVAL 2 DAY))";
				 if (! $db->Query($sql )) $db->Kill(''); 
				 echo 0;
				 exit;
			 }

			
			}//end if verif utilisateur
			else{
		     echo 1;
             return false;
				
			}//end else passworde
			
	     


function valid_captcha($captcha){
	
	if( trim($captcha) == $_SESSION['Captcha']){return true;}
	
	return false;
	
	}


?>

