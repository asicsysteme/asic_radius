<?php

/**
* Class Load application using Ajax request.
*/
class MAjax 
{
	
	Var $error       = true;
	var $is_appli    = false;
	var $default_app = null;
	var $log         = null;	
	var $app_id      = null;
	var $app_array   = array();
	var $session_user;
	var $session_id; 
	var $app_sys       = null;
	var $msg_ajax      = "Vous n'êtes pas autorisé(e) à accéder à cette application , redirection vers acceuil";
	var $degre_message = '3#';


	function __construct() {

	}

	function __destruct() {

	}

	

	

	//Check if TSK exist 
	//return Array or error = false
	private function Check_exist_tsk()
	{
		$this->app_id = $this->default_app == null? MReq::tg('_tsk') : $this->default_app;
		if($this->app_id  == '0')
		{
			$this->error = false;
			$this->degre_message = '3#';
			$this->log .='//TG_TSK_NULL';
		}else{
			$this->error = true;
		}

	}

	//Check if Application exist on DB and append app_array
	//return Array or error = false
	private function Check_exist_app()
	{
		global $db;
		$sql = "SELECT sys_task.*, sys_modules.modul, sys_modules.app_modul FROM sys_task, sys_modules
		 WHERE sys_task.modul = sys_modules.id AND  app = '".$this->app_id."' ";
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if(!$db->RowCount())
			{
				$this->error = false;
				$this->degre_message = '3#';
				$this->log .='//APP_TASK_NULL'.$sql;


			}else{
				$this->app_array = $db->RowArray();
				$this->error = true;
				

			}			

		} 

	}

	//Check if is ajax request 
	//return bol default true
	private function Check_is_ajax_request()
	{
		if($this->is_appli == true)
		{
			if($this->app_array['ajax'] == 1)
			{
				$this->error = false;
				$this->degre_message = '3#';
				$this->log .='//AJAX_REQUEST';
			    //sleep(2);
                //header('location:./');
			}else{
				$this->error = true;
			}
		}else{
			if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			{
				$this->error = false;
			    //$this->log .='//AJAX_REQUEST';
			    //sleep(2);
				header('location:./');
			}else{
				$this->error = true;
			}
		}
		
		//exit($this->app_array['ajax']);
	}

	//Check for session APP 
	//used only for determine Template
	//if session exist and APP not need session auto logout
	
	private function Check_session_template()
	{
		if($this->app_array['session'] == 0 && session::get('ssid') != false)
		{
			$this->error = false;
			$this->degre_message = '4#';
			$this->log .=' //AUTO_LOGOUT';
			$new_logout = new  MLogin();
			$new_logout->token = session::get('username');
			$new_logout->logout();
				//sleep(2);
				//header('location:./');
		}else{
			$this->error = true;
		}

	}

	private function Check_user_active()
	{
		global $db;
		$sql = "SELECT pass  
		FROM sys_users 
		WHERE id = ".MySQL::SQLValue(session::get('userid'))." AND   etat = 1";
		if($db->QuerySingleValue0($sql) != session::get('key'))
		{
			$this->error = false;
			$this->degre_message = '3#';
			$this->log .=' //USER_ACOUNT_INACTIVE';
			$new_logout = new  MLogin();
			$new_logout->token = session::get('username');
			$new_logout->logout();

		}else{
			$this->error = true;
		}

	}

	//Check if need session or not 
	//return error true
	private function Check_need_session()
	{

		$this->session_user = MySQL::SQLValue(session::get('username'));
		$this->session_id   = MySQL::SQLValue(session::get('ssid'));

		global $db;
		$sql = "SELECT id_sys  
		FROM sys_session 
		WHERE expir is null AND  id = ".$this->session_id." and user = ".$this->session_user;
		if($db->QuerySingleValue0($sql) == '0' && $this->app_array['session'] == 1)
		{
			$this->error = false;
			$this->degre_message = '4#';
			$this->log .=' //NEED_SESSION';
			$new_logout = new  MLogin();
			$new_logout->token = session::get('ssid');
			$new_logout->logout();

		}else{
			$this->error = true;
		}

	}

	//Check if user have permission 
	//Return error true
	private function Check_user_permission()
	{
					//Grant to user id 1
		$id_user = session::get('userid');
		if($id_user == 1){
			return true;
		}
		global $db;
		$this->session_user = MySQL::SQLValue(session::get('userid'));

		$sql = "SELECT 1 FROM
		sys_rules
		INNER JOIN sys_task 
		ON (sys_rules.appid = sys_task.id)
		INNER JOIN sys_task_action 
		ON (sys_task_action.appid = sys_task.id) AND (sys_rules.action_id = sys_task_action.id)
		INNER JOIN sys_users 
		ON (sys_rules.userid = sys_users.id)
		WHERE sys_users.id = ".$this->session_user."
		AND sys_task.app =  ".MySQL::SQLValue($this->app_array['app'])." ";
		if($db->QuerySingleValue0($sql) == '0' && $this->app_array['app_sys'] == 0)
		{
			$this->error = false;
			$this->degre_message = '3#';
			$this->log .='//PERMISSION_USER';

		}else{
			$this->error = true;
		}
	}

	/**
	 * Last Activité exec
	 * update users_sys with last activité usuful for auto logout
	 * return log && Error
	 * 
	 */
	private function last_active()
	{
		global $db;
		//Get last activity time and compare with now
		//if is elapsed logout
		
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, lastactive, CURRENT_TIMESTAMP) as expir
		FROM sys_users WHERE id = ".MySQL::SQLValue(session::get('userid'));
		$time = $db->QuerySingleValue0($sql);
		
		
		if($time > MCfg::get('auto_logout'))//Config by user or systeme
		{
			
			$minutes = $time;
			$zero    = new DateTime('@0');
			$offset  = new DateTime('@' . $minutes * 60);
			$diff    = $zero->diff($offset);

			$days =  $diff->format('%a') == 0 ? null : $diff->format('%a').' J - ';
			$hour =  $diff->format('%h') == 0 ? '00h:' : $diff->format('%h').'H:';
			$minu =  $diff->format('%i') == 0 ? '00Min ' : $diff->format('%i').'Min';
			$template =  $days.$hour.$minu;
			$this->error = false;
			$this->degre_message = '4#';
			
			
			$hash = md5(uniqid(rand(), true));
			$this->log .=' </br>vous avez été deconnecté du serveur pour une inactivité de [#]'.$hash.'[#] //AUTO_LOGOUT';

			$file = MPATH_TEMP.SLASH.$hash.'.ses';
			if(!file_put_contents($file, $template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Erreur sauvgarde ses file';
			}
			

			$new_logout = new  MLogin();
			$new_logout->token = session::get('ssid');
			$new_logout->logout();
		}else{
			//Update lastactive into users_sys case no app sys
			if($this->app_array['app_sys'] == 0 OR $this->app_array['session'] == 1)
			{
				$val_time['lastactive'] = 'CURRENT_TIMESTAMP';
				$whr_user['id']         = MySQL::SQLValue(session::get('userid'));
				if (!$db->UpdateRows('sys_users', $val_time, $whr_user))
				{
					$this->log .= $db->Error();
					$this->error = false;
					$this->log .='</br>Problème MAJ dérnière activité'; 
				}
			}

		} 
		
		
	}

	private function btn_go_back($item)
	{
		$app  = $this->app_id;
		$id   = MReq::tp('id') == null ? null : MReq::tp('id');
		$idc  = MReq::tp('idc') == null ? null : MReq::tp('idc');
		$idh  = MReq::tp('idh') == null ? null : MReq::tp('idh');
		$item = md5($item);

		if($id != null && $idc != null && $idh != null)
		{
			$data = 'id='.$id.'&idc='.$idc.'&idh='.$idh;
		}else{
			$data = null;
		}
        $array = array('app' => $app, 'data' => $data, 'item' => $item);
		$val_gobak = json_encode($array);
		$previous = session::get_cookie('this_zone', false);
		session::set_cookie('this_zone', $val_gobak, 3600, false);
		session::set_cookie('gobak', $previous, 3600, false);
		return true;
	}

	public function load()
	{
		//exit($this->default_app);
		//Do ALL check if error =  false exit log
		
		if($this->error == true)
		{
			$this->Check_exist_tsk();
		}
		if($this->error == true)
		{
			$this->Check_exist_app();			
		}
		if($this->error == true  )
		{
			$this->Check_is_ajax_request();
		}
		if ($this->error == true) 
		{
			$this->Check_session_template();
		}
		if($this->error == true)
		{
			$this->Check_user_active();
		}
		if($this->error == true)
		{
			$this->Check_need_session();			
		}
		if($this->error == true)
		{
			$this->Check_user_permission();
		}
		if($this->error == true)
		{
			$this->last_active();
		}
		
		
		
		if($this->error == false)
		{
			if($this->is_appli == true)
			{
				header('location:./');

				echo('<div class="space-16"></div><div class="space-16"></div><div class="alert alert-block alert-danger"><i class="ace-icon fa fa-exclamation-circle red fa-2x icon-animated-vertical"></i> <strong class="red"> STOP: </strong>'.$this->msg_ajax.$this->log.'<br><a href="./" class="btn btn-danger btn-sm"><i class="ace-icon fa fa-reply icon-only"> Accueil</i></a></div>');
				
				

			}else{
				exit($this->degre_message.$this->msg_ajax.$this->log);
			}
			
			
		}else{
			//sleep(5);
			if(MReq::tp('act') == 1)
				{
					$target = MPATH_MODULES.$this->app_array['rep'].SLASH.'controller/action'.$this->app_array['file'].'_c.php';
				}elseif(MReq::tp('lst') == 1){
					$target = MPATH_MODULES.$this->app_array['rep'].SLASH.'controller/list'.$this->app_array['file'].'_c.php';
				}else{
					$target = MPATH_MODULES.$this->app_array['rep'].SLASH.'controller/'.$this->app_array['file'].'_c.php';
				}

				if(!file_exists($target))
				{
					exit($this->degre_message.$this->msg_ajax.'//FILE'.$target);
				}else{
					define('ACTIV_APP', $this->app_array['dscrip']);
					define('MODUL_APP', $this->app_array['modul']);
					define('APP_TARGET', MPATH_MODULES.$this->app_array['rep'].SLASH.'controller/');
					define('APP_VIEW', MPATH_MODULES.$this->app_array['rep'].SLASH.'view'.SLASH);
					define('APP_ID', $this->app_array['id']);


			    //Append tree top menu only for no appli App
					if($this->is_appli == false && MReq::tp('cor') == 1){
                        
						$output  = '<li><i class="ace-icon fa fa-home home-icon"></i><a href="#" left_menu="1" class="tip-right this_url" rel="dbd" title="Tableau de bord">Accueil</a></li>';
						$output .= '<li><a href="#" left_menu="1" class="fa-double_angle_right this_url" rel="'.$this->app_array['app_modul'].'" title="'.$this->app_array['modul'].'">'.$this->app_array['modul'].'</a></li>';
						$output .= '<li class="active">'.$this->app_array['dscrip'].'</li>';
	                $output .='#||#'; //Separator data
	                $this->btn_go_back($this->app_array['dscrip']);
	                print($output);

	            }		    

	            require_once($target);
			    //var_dump($_SESSION);

	        }


	    }


	}


}













?>
