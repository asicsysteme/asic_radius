<?php

/**
* Class Gestion Utilisateur V1.0
*/


class Musers {

	private $_data; //data receive from form

	var $table           = 'sys_users'; //Main table of module
	var $last_id         = null; //return last ID after insert command
	var $log             = null; //Log of all opération.
	var $error           = true; //Error bol changed when an error is occured
	var $exige_photo     = true; //set when photo is required must be defalut true.
	var $new_photo       = null; //new photo path
	var $exige_signature = true; //set when signature is required must be default true.
	var $new_signature   = null; //set new signature path
	var $exige_form      = true; // set when form is required must be default true.
	var $new_form        = null; //set new form path
	var $id_user         = null; // User ID append when request
	var $token           = null; //user for recovery function
	var $user_info; //Array stock all userinfo 
  var $user_activities        = null; //Array stock all user activities
  var $user_connexion_history = null; //Array stock all user connexion history
	var $app_action; //Array action for each row

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

	/**
	 * Get information for one User
	 * @return [Array] [fill $this->user_info]
	 */
	public function get_user()
	{
		global $db;

		$sql = "SELECT sys_users.*, sys_services.service as service_user FROM 
		sys_users, sys_services WHERE sys_users.service = sys_services.id AND  sys_users.id = ".$this->id_user;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if (!$db->RowCount()) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->user_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array user_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
		
	}
    //
/**
 * [get_activities Get the list of  user activities from SYS_LOG Table]
 * @return [Array] [fit Array user_activities]
 */
  public function get_activities()
  {
    global $db;

    $sql = "SELECT distinct CONCAT(l.`message`,' , le  '
    ,DATE_FORMAT(l.`datlog`,'%d-%m-%Y'),' , à ',TIME(l.`datlog`)) as activities FROM 
    sys_users u, sys_log l WHERE u.nom = l.user_exec AND  u.id =$this->id_user order by l.`datlog` desc limit 0,6";
    if(!$db->Query($sql))
    {
      $this->error = false;
      $this->log  .= $db->Error();
    }else{
      if (!$db->RowCount()) {
        $this->error = false;
        $this->log .= 'Aucun enregistrement trouvé ';
      } else {
        $this->user_activities = $db->RecordsSimplArray();
                //var_dump( $this->user_activities );
        $this->error = true;
      }


    }
        //return Array user_activities
    if($this->error == false)
    {
      return false;
    }else{
      return true;
    }

  }

     
  /**
   * [get_connexion_history Get the list of  user connexion history from SESSION Table]
   * @return [type] [description]
   */
  public function get_connexion_history()
  {
    global $db;

    $sql = "SELECT CONCAT((IF (s.`expir`IS NULL,'Session ouverte, depuis ',CONCAT('Ouverture de session, le ',DATE_FORMAT(s.`dat`,'%d-%m-%Y %H:%i'),' pendant ')))
    ,TIMESTAMPDIFF(MINUTE, s.`dat`, (IF (s.`expir`IS NULL,NOW(),s.`expir`))),' minutes') AS HISTORY FROM 
    sys_users u, sys_session s WHERE u.`id`=s.`userid` AND u.id = $this->id_user ORDER BY s.`dat` DESC LIMIT 0,6";

    if(!$db->Query($sql))
    {
      $this->error = false;
      $this->log  .= $db->Error();
    }else{
      if (!$db->RowCount()) {
        $this->error = false;
        $this->log .= 'Aucun enregistrement trouvé ';
      } else {
        $this->user_connexion_history = $db->RecordsSimplArray();
                //var_dump( $this->user_connexion_history );
        $this->error = true;
      }


    }
        //return Array user_connexion_history
    if($this->error == false)
    {
      return false;
    }else{
      return true;
    }

  }

	/**
	 * [Shw description]
	 * @param [type] $key     [Key of info_array]
	 * @param string $no_echo [Used for echo the value on View ]
	 */
	public function Shw($key,$no_echo = "")
	{
		if($this->user_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->user_info[$key];
			}

			echo $this->user_info[$key];
		}else{
			echo "";
		}
		
	}

    /**
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function s($key)
    {
      if($this->user_info[$key] != null)
      {
        echo $this->user_info[$key];
      }else{
        echo "";
      }
      
    }

    /**
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function g($key)
    {
      if($this->user_info[$key] != null)
      {
        return $this->user_info[$key];
      }else{
        return null;
      }
      
    }

	/**
     * [check_exist Check if one entrie already exist on table]
     * @param  [string] $column  [Column of field on main table]
     * @param  [string] $value   [the value to check]
     * @param  [string] $message [Returned message if exist]
     * @param  [int] $edit       [Used if is edit action must be the ID of row edited]
     * @return [Setting]         [Set $this->error and $this->log]
     */
	private function check_exist($column, $value, $message, $edit = null)
	{
		global $db;
		$table = $this->table;
		$sql_edit = $edit == null ? null: " AND id <> $edit";
		$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
			WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

		if ($result != "0") {
			$this->error = false;
			$this->log .='</br>'.$message.' exist déjà';
		}
	}
	/**
     * [check_non_exist Check if one entrie not exist on referential table]
     * @param  [string] $table   [referential table]
     * @param  [string] $column  [Column bechecked on referential table]
     * @param  [string] $value   [the value to check]
     * @param  [string] $message [Returned message if not  exist]
     * @return [Setting]         [Set $this->error and $this->log]
     */
	private function check_non_exist($table, $column, $value, $message)
	{
		global $db;
		$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
			WHERE $table.$column = ". MySQL::SQLValue($value));
		if ($result == "0") {
			$this->error = false;
			$this->log .='</br>'.$message.' n\'exist pas';
    		//exit('0#'.$this->log);
		}
	}
    /**
     * [check_file Check attached if required stop Insert this must be placed befor Insert commande]
     * @param  [string] $item [input_name of attached file we add _id]
     * @param  [string] $msg  [description]
     * @param  [int] $edit    [Used if is edit action must be the ID of row edited]
     * @return [Setting]      [Set $this->error and $this->log]
     */
    Private function check_file($item, $msg = null, $edit = null)
    {
        //Format temporary file
    	$temp_file     = $this->_data[$item.'_id'];
    	//Check if is edit action (is numeric when called from archive DB else is retrned target upload)
    	if($edit != null && !is_numeric($temp_file))
    	{
    		if(!file_exists($temp_file))
    		{
    			$this->log .= '</br>Il faut choisir '.$msg.' pour la mise à jour '.$edit;
    			$this->error = false;
    		}
    	//When is not edit do check for existing file
    	}else{
    		if($edit == null && $this->exige_.$item == true && ($this->_data[$item.'_id'] == null || !file_exists($this->_data[$item.'_id'])))
    		{
    			$this->log .= '</br>Il faut choisir '.$msg. '  '.$edit;
    			$this->error = false; 
    		}
    	}

    }

    /**
     * [save_file For save anattached file for entrie ]
     * @param  [string] $item  [input_name of attached file we add _id]
     * @param  [string] $titre [Title stored for file on Archive DB]
     * @param  [string] $type  [Type of file (Document, PDF, Image)]
     * @return [Setting]       [Set $this->error and $this->log]
     */
    private function save_file($item, $titre, $type)
    {
    	//Format all parameteres
    	$temp_file     = $this->_data[$item.'_id'];
        //If nofile uploaded return kill function
      if($temp_file == Null){
        return true;
      }

      $new_name_file = $item.'_'.$this->last_id;
      $folder        = MPATH_UPLOAD.'users'.SLASH.$this->last_id;
      $id_line       = $this->last_id;
      $title         = $titre;
      $table         = $this->table;
      $column        = $item;
      $type          = $type;



    	//Call save_file_upload from initial class
      if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'users', $table, $column, $type, $edit = null))
      {
        $this->error = false;
        $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
      }
    }

    /**
	 * [save_new_user Save new User after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
    public function save_new_user()
    {



        //Befor execute do the multiple check
        //Nom d'utilisateur
    	$this->Check_exist('nom', $this->_data['nom'], 'Nom d\' utilisateur', null);
        //Email utilisateur
    	$this->Check_exist('email', $this->_data['mail'], 'Email', null);
        //Service si existe
    	$this->check_non_exist('sys_services', 'id', $this->_data['service'], 'Service');
		//Test for password
    	$this->check_password_comp();
    	$this->check_Password_complex();
		//if need form
    	if($this->exige_form)
    	{
    		$this->check_file('form', 'Le formulaire d\' Enregistrement.');
    	}
    	//if need photo
    	if($this->exige_photo)
    	{
    		$this->check_file('photo', 'La Photo d\' Utilisateur.');
    	}
    	//if need signature
    	if($this->exige_signature && Mservice::check_need_sign($this->_data['service']))
    	{
    		$this->check_file('signature', 'La Signature d\'Utilisateur.');
    	}

    	if($this->error == true)
    	{
    		global $db;
    		$values["nom"]     = MySQL::SQLValue($this->_data['nom']);
    		$values["mail"]    = MySQL::SQLValue($this->_data['mail']);
    		$values["pass"]    = MySQL::SQLValue(md5($this->_data['pass']));
    		$values["service"] = MySQL::SQLValue($this->_data['service']);
    		$values["fnom"]    = MySQL::SQLValue($this->_data['fnom']);
    		$values["lnom"]    = MySQL::SQLValue($this->_data['lnom']);
    		$values["tel"]     = MySQL::SQLValue($this->_data['tel']);
    		$values["etat"]    = MySQL::SQLValue(0);
    		$values["defapp"]  = MySQL::SQLValue(3);
        $values["creusr"]  = MySQL::SQLValue(session::get('userid'));


			//If no error on Insert commande
        if (!$result = $db->InsertRow("sys_users", $values)) {

         $this->log .= $db->Error();
         $this->error = false;
         $this->log .='</br>Enregistrement BD non réussie'; 

       }else{

         $this->last_id = $result;
				//If Attached required Save file to Archive
         $this->save_file('photo', 'Photo de profile de '.$this->_data['fnom'].'  '.$this->_data['fnom'], 'image');

         $this->save_file('signature', 'signature  de '.$this->_data['fnom'].'  '.$this->_data['fnom'], 'image');


         $this->save_file('form', 'Formulaire  de '.$this->_data['fnom'].'  '.$this->_data['fnom'], 'Document');

				//Insert rules for new user based on service
         $this->auto_add_user_rules($this->last_id, $this->_data['service']);
         if($this->error == true)
         {
          $this->log = '</br>Enregistrement réussie: <b>'.$this->_data['fnom'].'  '.$this->_data['lnom'].' ID: '.$this->last_id;
          if(!Mlog::log_exec($this->table, $this->last_id, 'Création utlisateur', 'Insert'))
          {
            $this->log .= '</br>Un problème de log ';
          }

        }else{
          $this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['fnom'].'  '.$this->_data['lnom'];
          $this->log .= '</br>Un problème d\'Enregistrement ';
        }
      }
    }else{
      $this->log .='</br>Enregistrement non réussie';
    }
        //check if last error is true then return true else rturn false.
    if($this->error == false){
      return false;
    }else{
      return true;
    }

  }
    /**
	 * [save_new_user Edit existing User after check values]
	 * @return [bol] [Send Bol value to controller]
	 */
    public function edit_user()
    {
     	//Get data for selected station
    	$this->get_user();
      $this->last_id = $this->id_user; 


        //Befor execute do the multiple check
        //Nom d'utilisateur
      $this->Check_exist('nom', $this->_data['nom'], 'Nom d\' utilisateur', $this->id_user);
        //Email utilisateur
      $this->Check_exist('email', $this->_data['mail'], 'Email', $this->id_user);
        //Service si existe
      $this->check_non_exist('services', 'id', $this->_data['service'], 'Nationalité');
		//Test for password
      $this->check_password_comp();
      $this->check_Password_complex();
		//if need form
      if($this->exige_form)
      {
        $this->check_file('form', 'Le formulaire d\' Enregistrement.', $this->_data['form_id']);
      }
    	//if need photo
      if($this->exige_photo)
      {
        $this->check_file('photo', 'La Photo d\' Utilisateur.', $this->_data['photo_id']);
      }
    	//if need signature
      if($this->exige_form)
      {
        $this->check_file('signature', 'La Signature d\'Utilisateur.', $this->_data['signature_id']);
      }



      if($this->error == true)
      {
        global $db;
        $values["nom"]     = MySQL::SQLValue($this->_data['nom']);
        $values["mail"]    = MySQL::SQLValue($this->_data['mail']);
        
        $values["service"] = MySQL::SQLValue($this->_data['service']);
        $values["fnom"]    = MySQL::SQLValue($this->_data['fnom']);
        $values["lnom"]    = MySQL::SQLValue($this->_data['lnom']);
        $values["tel"]     = MySQL::SQLValue($this->_data['tel']);
        $values["etat"]    = MySQL::SQLValue($this->user_info['etat']);
        $values["defapp"]  = MySQL::SQLValue(3);
        $values["updusr"]  = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]  = ' CURRENT_TIMESTAMP ';
        $wheres["id"]      = MySQL::SQLValue($this->id_user);
        //if password not null then update it
        if($this->_data['pass'] != null){
          $values["pass"]    = MySQL::SQLValue(md5($this->_data['pass']));
        }


			//If no error on Update commande
        if (!$result = $db->UpdateRows("sys_users", $values, $wheres)) {
				//$db->Kill();
         $this->log .= $db->Error();
         $this->error == false;
         $this->log .='</br>Modification BD non réussie'; 

       }else{

         $this->last_id = $this->id_user;
				

          $this->save_file('photo', 'Photo de profile de '.$this->_data['fnom'].'  '.$this->_data['fnom'], 'image');
        
        

          $this->save_file('signature', 'signature  de '.$this->_data['fnom'].'  '.$this->_data['fnom'], 'image');
        
        

          $this->save_file('form', 'Formulaire  de '.$this->_data['fnom'].'  '.$this->_data['fnom'], 'Document');

        

				//Update rules for exist user based on service
        if($this->user_info['service'] != $this->_data['service'])
        {
          $this->auto_add_user_rules($this->last_id, $this->_data['service']);
        }
        
    			//Esspionage
        if(!$db->After_update($this->table, $this->id_user, $values, $this->user_info)){
          $this->log .= '</br>Problème Esspionage';
          $this->error = false;	
        }
        if($this->error == true)
        {
          $this->log = '</br>Modification réussie: <b>'.$this->_data['fnom'].'  '.$this->_data['lnom'].' ID: '.$this->last_id;
          if(!Mlog::log_exec($this->table, $this->last_id, 'Modification utlisateur', 'Update'))
          {
            $this->log .= '</br>Un problème de log ';
          }

        }else{
          $this->log .= '</br>Modification réussie: <b>'.$this->_data['fnom'].'  '.$this->_data['lnom'];
          $this->log .= '</br>Un problème de Modification ';
        }
      }
    }else{
      $this->log .='</br>Modification non réussie';
    }
        //check if last error is true then return true else rturn false.
    if($this->error == false){
      return false;
    }else{
      return true;
    }

  }


	/**
	 * Clear user rules action
	 * @param   $userid class var int
	 * return error - log 
	 */
	
	public function clear_user_rules()
	{
		global $db;
		//Delete Rules if exist for user_id
		$where['userid'] = $this->id_user;
    //exit($db->BuildSQLDelete('sys_rules', $where));
		if(!$db->DeleteRows('sys_rules', $where))
		{
			$this->error = false;
			$this->log  .= '</br>Impossible de supprimer les anciens rules '.$db->Error().' '.$db->BuildSQLDelete('sys_rules', $where);
		}

	}

	/**
	 * [auto_add_user_rules description]
	 * @param service com from rules_c
	 * @return boolean - error - log
	 */
	private function auto_add_user_rules($userid, $service)
	{
		global $db; //
    $this->id_user = $userid;
		//First clear if have rules for sam user id
    $this->clear_user_rules();
    //Check if service have rules
    $sql_exist_rule = "SELECT COUNT(id) FROM sys_task_action WHERE service LIKE  '%-$service-%'";
    if($db->QuerySingleValue0($sql_exist_rule) == 0){
      $this->error = false;
      $this->log  .= '</br>Le service choisi ne dispose pas des droits ';
      return false;
    }

    $sql = "INSERT INTO sys_rules (appid, idf, descrip, action_id, type, userid, service) SELECT appid, idf,  descrip, id, type, $userid, $service FROM sys_task_action WHERE service LIKE  '%-$service-%' ";
    if(!$db->Query($sql))
    {
     $this->error = false;
     $this->log  .= '</br>Impossible d\'ajouter les rules ';
   }  

		//$this->log .= $sql;	

 }

	/**
	 * [add_user_rules description]
	 * @param this->_data com from rules_c
	 * @return boolean - error - log
	 */
	public function add_user_rules()
	{
		global $db; //
		//var_dump($this->app_action);
		$values["appid"]     = MySQL::SQLValue($this->app_action['app_id']);
		$values["descrip"]   = MySQL::SQLValue($this->app_action['app_name']);
		$values["action_id"] = MySQL::SQLValue($this->app_action['action_id']);
    $values["idf"]       = MySQL::SQLValue($this->app_action['idf']);
    $values["type"]      = MySQL::SQLValue($this->app_action['type']);
    $values["userid"]    = MySQL::SQLValue($this->app_action['userid']);
    $values["service"]   = MySQL::SQLValue($this->app_action['service']);
    $values["creusr"]    = MySQL::SQLValue(session::get('userid'));

    if(!$db->InsertRow("sys_rules", $values))
    {
     $this->error = false;
     $this->log  .= '</br>Impossible d\'ajouter les rules ';
     return false;
   }  

   $this->error = true;
   $this->log  .= '</br>Enregistrement réussie ';
   return true;

 }





	/**
	 * [check_password_comp Check password compatibility]
	 * @return [bol] [Fill LOG and $this->error]
	 */
	private function check_password_comp()
	{
		if($this->_data['pass'] != $this->_data['passc'] ){

			$this->error = false;
			$this->log .='</br>Incompatible passwords';
		}
	}
	/**
	 * [check_Password_complex Check password complexity]
	 * @return [bol] [Fill LOG and $this->error]
	 */
	private function check_Password_complex() 
	{
		$pwd = $this->_data['pass'];
		if ($pwd != null && (strlen($pwd) < 8 || !preg_match("#[0-9]+#", $pwd) || !preg_match("#[a-zA-Z]+#", $pwd))) {

			$this->error = false;
			$this->log .='</br>Le mot de passe doit être alphanumériques compris entre 8 et 15 caractères';

		}


	}


	/**
	 * [valid_compte Validation user compte]
	 * @param  integer $etat [Etat line]
	 * @return [bol]        [Send bol to controller]
	 */
	public function valid_compte($etat = 0)
	{
		global $db;
		$etat = $etat == 0 ? 1 : 0;
		$user_id = $this->id_user;
			//Format value for requet
		$value["etat"] = MySQL::SQLValue($etat);
		$where["id"] =  $user_id;
        // Execute the update and show error case error
		if( !$result = $db->UpdateRows("sys_users", $value , $where))
		{
			$this->log .= '</br>Impossible de changer le statut!';
			$this->log .= '</br>'.$db->Error();
			$this->error = false;
		}else{
			$this->log .= '</br>Statut changé! ';
			$this->error = true;

		} 
		if($this->error == false){
			return false;
		}else{
			return true;
		}
	}

    /**
     * [check_recovery_token Chek token exist and etat = 0]
     * @return [bool] [Return bool to controller]
     */
    public function check_recovery_token()
    {
      global $db;

      $chektoken = $db->QuerySingleValue0("SELECT id 
       FROM sys_forgot where CURRENT_TIMESTAMP < expir and
       token = '".$this->token."' and etat = 0");
      if($chektoken == "0" )
      {
       $this->log .= '</br>Vous tentez de changer le mot de passe en utilisant un token non valide !';
       $this->log .= "</br>SELECT id 
       FROM sys_forgot where CURRENT_TIMESTAMP < expir and
       token = '".$this->token."' and etat = 0";
       $this->error = false;
       return false;
     }
     return true;


   }

    /**
     * [recovery_pass Recovery password]
     * @return [bool] [Send bool to controller]
     */
    public function recovery_pass()
    {
      global $db;
      $this->check_recovery_token();
      if($this->error == true)
      {
		//Get user ID
       $token = $this->_data['token'];
       $pass  = $this->_data['pass'];
       $user_id = $db->QuerySingleValue("SELECT user FROM sys_forgot where
        token='$token' and etat = 0");

         //Format value for requet
       $value["pass"] = MySQL::SQLValue( md5($pass));
       $where["id"] =  MySQL::SQLValue($user_id);
        // Execute the update and show error case error
       if( !$result = $db->UpdateRows("sys_users", $value , $where)){
        $this->log .= '</br>Impossible de changer le mot de passe !';
        $this->log .= '</br>'.$db->Error();
        $this->error = false;
      }else{
				//if good disable recovery link
        $values["etat"]  =  MySQL::SQLValue( 1);
        $wheres["token"] =  MySQL::SQLValue($token);
                // Execute the update
        if(!$results = $db->UpdateRows("sys_forgot", $values , $wheres))
        {
         $this->log .= '</br>Impossible de MAJ la ligne token !';
         $this->log .= '</br>'.$db->Error();
         $this->error = false;
       }
     }     
     return true;

   }
   return false;
 }

 /**
     * [Change password]
     * @return [bool] [Send bool to controller]
     */
 public function change_pass()
 {
  global $db;

  $user_id = $this->id_user;
  $pass    = $this->_data['pass'];
  $passwd   = md5($this->_data['password']);

  $password = $db->QuerySingleValue("SELECT pass FROM sys_users where id='$user_id' and etat = 1");

        //Format value for requet
  $value["pass"]    = MySQL::SQLValue( md5($pass));
  $value["updusr"]  = MySQL::SQLValue(session::get('userid'));
  $value["upddat"]  = MySQL::SQLValue(date("Y-m-d H:i:s"));
  $where["id"]      = $user_id;


  if( $password == $passwd)
  {
        // Execute the update and show error case error
    if( !$result = $db->UpdateRows("sys_users", $value , $where))
    {
      $this->log .= '</br>Impossible de changer le mot de passe !';
      $this->log .= '</br>'.$db->Error();
      $this->error = false;
    }else{
      $this->log .= '</br>Mot de passe changé! ';
      $this->error = true;

    } 

  }
  else{
    $this->log .= '</br>Vérifiez ancien mot de passe ! ';
    $this->error = false;
    
  }

  if($this->error == false){
    return false;
  }else{
    return true;
  }
}



public function archive_user()
{
  global $db;

  $user_id = $this->id_user;
            //Format value for requet
  $value["etat"]    = MySQL::SQLValue(2);

  $where["id"]      = $user_id;
        // Execute the update and show error case error
  if( !$result = $db->UpdateRows("sys_users", $value , $where))
  {
    $this->log .= '</br>Impossible d\'archiver l\'utilisateur!';
    $this->log .= '</br>'.$db->Error();
    $this->error = false;
  }else{
    $this->log .= '</br>Utilisateur Archivé! ';
    $this->error = true;

  } 
  if($this->error == false){
    return false;
  }else{
    return true;
  }

}

public function delete_user()
{
  global $db;
  $where["id"]      = $this->id_user;
        // Execute the update and show error case error
  if( !$result = $db->DeleteRows("sys_users", $where))
  {
    $this->log .= '</br>Impossible de Supprimer l\'utilisateur!';
    $this->log .= '</br>'.$db->Error();
    $this->error = false;
  }else{
    $this->log .= '</br>Utilisateur supprimé! ';
    $this->error = true;

  } 
  if($this->error == false){
    return false;
  }else{
    return true;
  }

}


}


?>