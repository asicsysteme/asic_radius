<?php
/**
* User LOGIN LOGOUT FORGOT
*/
class MLogin 
{
	private $_data; //data receive from form
	var $log = ''; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
	var $user_info; //Array stock all userinfo 
	var $id_user; //Id user full when check exist user true
	var $signature_file = NULL; //append with file from archive 
	var $photo_file = NULL; //append with file from archive 
	var $token;
	
	public function __construct($properties = array()){
		$this->_data = $properties;
	}

    // magic methods!
	public function __set($property, $value){
		return $this->_data[$property] = $value;
	}

	public function __get($property){
		return array_key_exists($property, $this->_data)
		? $this->_data[$property]
		: null
		;
	}

	/*
	This get all info user from database and append Arrau user_info.
	 */
	private function get_user($email = null)
	{
		global $db;
		$this->id_user = $email == null? MySQL::SQLValue($this->id_user) : MySQL::SQLValue($email);
		$sql = "SELECT sys_users.* FROM 
		sys_users WHERE  sys_users.nom = ".$this->id_user." OR sys_users.mail = ".$this->id_user;
		if($this->error == true)
		{
			if(!$db->Query($sql))
			{
				$this->error = false;
				$this->log  .= $db->Error().'  '.$sql.'  '.$this->id_user;
			}else{
				if ($db->RowCount() == 0) {
					$this->error = false;
					$this->log .= '</br>Cet utilisateur n\'exist pas ';
				} else {
					$this->user_info = $db->RowArray();
					$this->error = true;
				}

			}

		}
		
		
	}

	public function do_login()
	{
		global $db;
		$this->id_user = $this->_data['user'];



		if($this->error == true)
		{
			$this->get_user();
		}
		if($this->error == true)
		{
			$this->check_active();
		}
		if($this->error == true)
		{
			$this->check_ctc();
		}
		if($this->error == true)
		{
			$this->check_pass();
		}
		if($this->error == true)
		{
			$this->check_signature();
		}
		
		
		
		

		//All test is ok do LOGIN
		if($this->error == true)
		{
			//Update CTC to 0
			if (!$db->UpdateSinglRows('sys_users', 'ctc', 0, $this->user_info['id']))
			{
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Problème MAJ CTC'; 
			} 

			//Expire opened Session for this user
			$val_session['expir'] = 'CURRENT_TIMESTAMP';
			$whr_session['user']  = $this->id_user;
			$whr_session[]  = 'expir IS NULL';
			if (!$db->UpdateRows('sys_session', $val_session, $whr_session))
			{
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Problème fermeture sessions ouvertes'; 
			}
			$this->delete_temp_folder($this->id_user, null);

			//Insert new session into DB
			$this->token = md5(uniqid(rand(), true));
			$val_new_session['id']           = MySQL::SQLValue($this->token);
			$val_new_session['user']         = MySQL::SQLValue($this->user_info['nom']);
			$val_new_session['userid']       = MySQL::SQLValue($this->user_info['id']);
			$val_new_session['ip']           = MySQL::SQLValue($_SERVER['REMOTE_ADDR']);
			$val_new_session['browser']      = MySQL::SQLValue($_SERVER['HTTP_USER_AGENT']);

			if(!$db->InsertRow('sys_session',$val_new_session))
			{
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Problème enregistrement nouvelle session';
			}
			//Update lastactive into sys_users
			$val_time['lastactive'] = 'CURRENT_TIMESTAMP';
			$whr_user['id']         = MySQL::SQLValue($this->user_info['id']);
			if (!$db->UpdateRows('sys_users', $val_time, $whr_user))
			{
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Problème MAJ dérnièrre activité'; 
			}

			//Open new session for this Login
			if($this->error == true)
			{
				//Stop Exsiting Captcha session and creat Sys Sessions
				$salt = MD5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$this->token);
				$session = new session();

                
				$session->clear('Captcha');//Clear Captcha Session

				$session->set('username',$this->user_info['nom']);
				$session->set('userid',$this->user_info['id']);
				$session->set('time',time());
				$session->set('ssid',$this->token);
				$session->set('agence',$this->user_info['agence']);
				$session->set('service',$this->user_info['service']);
				$session->set('imda',$this->signature_file);
				$session->set('defapp',$this->user_info['defapp']);
				$session->set('key',$this->user_info['pass']);
				$session->set('secur_ss',$salt );
				session::clear_cookie('gobak');
				session::clear_cookie('this_zone');
				
				
				//Set Photo user if has inserted on profile
				if($this->user_info['photo'] != null)
				{
					$this->photo_file = MInit::get_file_archive($this->user_info['photo']);
					$x                = $y = 36;


					$img_user = MInit::creat_thumbail($this->photo_file ,$x ,$y);
					if($this->photo_file == false || !file_exists($img_user))
					{
						$session->set('tof','img/user.jpg');
					}else{

						$session->set('tof',$img_user);
					}

				}else{
					$session->set('tof','img/user.jpg');
				}
				
				if(!$this->creat_temp_folder()){
					$this->log .= '</br>Unable to create Temp folder';
				}
			}

			$this->log .= '<br>Bienvenue <strong>'.$this->user_info['lnom'].' '.$this->user_info['fnom'].'  </strong></br> Vous serez rédiriger dans qulques instants';

		}

		//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
	}

	
	/*
	Check password for this user
	 */
	
	private function check_pass()
	{
		global $db;
		if ($this->user_info['pass'] != MD5($this->_data['pass'])){
			if (!$db->UpdateSinglRows('sys_users', 'ctc', $this->user_info['ctc'] + 1, $this->user_info['id'])) 
			{
				    //$db->Kill();
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>MAJ CTC unable'; 

			}
			$this->error = false;
			$this->log .='</br>Mot de passe Incorrect';			
		}
	}

	/*
	Check Wrong password counter default value set in config
	return Error & Log
	 */
	private function check_ctc()
	{
		$ctc = MCfg::get('ctc') == null?3:MCfg::get('ctc');
		if ($this->user_info['ctc'] >= $ctc){
			$this->error = false;
			$this->log .='</br>Vous avez dépasseé le nombre de tentatives de connexion';			
		}
	}

	/*
	Check Account Active
	return Error & Log
	 */
	private function check_active()
	{
		
		if ($this->user_info['etat'] != 1){
			$this->error = false;
			$this->log .='</br>Votre compte est désactivé contactez l\'administrateur Système';			
		}
	}

	/*
	Check user have valid signature or not
	return Error & Log
	when Service no need signature return true
	 */
	private function check_signature()
	{
		global $db;
		$sql_query = 'SELECT sign FROM sys_services WHERE id = '.$this->user_info['service'];

		$service_need_signature = $db->QuerySingleValue0($sql_query);
		if($service_need_signature == 1)
		{
			$this->signature_file = MInit::get_file_archive($this->user_info['signature']);

			if($this->user_info['signature'] == null || $this->signature_file == false)
			{

				$this->error = false;
				$this->log .='</br>Vous n\'avez pas une signature enregistrée';
			}
		}
		
	}

	/**
	 * [creat_temp_folder For new login we create folder for temporary files handling]
	 * @return [Bool]        Return false if unable to create folder
	 */
	private function creat_temp_folder()
	{
		$temp_folder = MPATH_TEMP.$this->token;
		if(!file_exists($temp_folder)  && !@mkdir($temp_folder, 0777, true))
		{
			return false;

		}else{
			return true;
		}
	}



	
	private function delete_temp_folder($user = null, $session = null)
	{
		global $db;
		$username = $user;
		$sql_query = "SELECT id FROM sys_session WHERE user = '".$username."' AND expir is NULL";
		
		$session_id = $db->QuerySingleValue0($sql_query);

		if($session_id != '0')
		{

			$temp_folder = MPATH_TEMP.$session_id;
			if(!MInit::deleteDir($temp_folder))
			{		
				$this->error = false;
				$this->log .='</br>Unable remove temp folder';

			}

		}
	}

	private function delete_temp_folder_auto_logout($session_id)
	{
		$temp_folder = MPATH_TEMP.$session_id;
		if(session::get($session_id)){
			if(!MInit::deleteDir($temp_folder))
		    {		
			    $this->error = false;
			    $this->log .='</br>Unable remove temp folder';

		    }
		}
		
		

	}

	public function logout($auto = null)
	{
		global $db;
		//Expire opened Session for this user
		$val_session['expir'] = 'CURRENT_TIMESTAMP';
		$whr_session['id']  = MySQL::SQLValue($this->token);

		
		if (!$db->UpdateRows('sys_session', $val_session, $whr_session))
		{
			//$db->kill($db->Error().'  '.$db->BuildSQLUpdate('session', $val_session, $whr_session));
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Problème fermeture sessions ouvertes'; 
		}
		$this->delete_temp_folder_auto_logout($this->token);
		$session = new session();
		session::clear_cookie('gobak');
		session::clear_cookie('this_zone');
		if(!$session->stop())
		{
			return false;
		}
		
				
		return true;
	}

	/*
	Function Forgot password
	use Email and captcha
	return bol Error && Log
	 */

	public function do_forgot()
	{
		//Get user_info
		$this->get_user($this->_data['email']);
		if($this->error == true)
		{
			$this->send_forgot_mail();
		}
		//Insert forgot request into Token table
		if ($this->error == true) {

			//Insert into token table
			global $db;
			$values["token"] = MySQL::SQLValue(MD5($this->_data['captcha']));
			$values["user"] = MySQL::SQLValue($this->user_info['id']);
			$values["etat"] = MySQL::SQLValue(0);
			$values["dat"] = 'CURRENT_TIMESTAMP';
			$values["expir"] = 'DATE_ADD(CURRENT_TIMESTAMP,INTERVAL 2 DAY)';
			$values["ip"] = MySQL::SQLValue($_SERVER['REMOTE_ADDR']);
            // Execute the insert
			if(!$result = $db->InsertRow("sys_forgot", $values))
			{
				$this->error = false;
				$this->log  .= $db->Error();
			}

			return true;
		}else{
			return false;
		}

		
		


		
	}

	private function send_forgot_mail()
	{
		
		date_default_timezone_set('Etc/UTC');
//Create a new PHPMailer instance
		$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		$mail->SMTPDebug = 0;

		$mail->addAddress($this->user_info['mail'], $this->user_info['fnom'].'  '.$this->user_info['lnom']);
//Set the subject line
		$mail->Subject = 'Demande de réinitialisation du mot de passe Système';
//Read an HTML message body from an external file, convert referenced images to embedded,
		$Msg_body = file_get_contents(MPATH_MSG.'forgot.php');
		$Msg_body = str_replace(
			array('%user_email%','%url_recovery%'), 
			array($this->user_info['mail'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?_tsk=recovery&token='.MD5($this->_data['captcha'])), 
			$Msg_body);
		$mail->msgHTML($Msg_body);

		if (!$mail->send()) {
			$this->log .= "Mailer Error: " . $mail->ErrorInfo;
		} else {
			$this->log .= "Un Message avec la procédure de recupération de mot de passe est envoyé à ".$mail->hide_mail($this->user_info['mail']);
		}
	}

	static public function get_ses_time_autologout($param)
	{
		$time = null;
		$file = MPATH_TEMP.SLASH.$param.'.ses';
		if(file_exists($file)){
			$time = file_get_contents($file);
			unlink($file);
		}else{
			$time = Mcfg::get('auto_logout').' Min';
		}
		return $time;
	}



}

?>