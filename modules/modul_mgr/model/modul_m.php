<?php

/**
* Class Gestion Modules V1.0
*/


class Mmodul {

	private $_data; //data receive from form
	
	var $last_id            = null; //return last ID after insert command
	var $log                = ''; //Log of all opération.
	var $error              = true; //Error bol changed when an error is occured
	var $exige_pkg          = null; // set when form is required must be default true.
	var $default_app        = null;
	var $new_form           = ''; //set new form path
	var $id_modul           = null; // Modul ID append when request
	var $token              = null; //user for recovery function
	var $modul_info         = array(); //Array stock all userinfo 
	var $task_info          = array(); //Array stock all userinfo 
	var $app_action         = array(); //Array action for each row
	var $new_modul          = false;
	var $EndOfSeek          = null;
	var $rows               = null;
	var $lines_action       = null;//All field for form action
	var $lines_action_check = null;//All field for form action checker
	var $lines_form_add     = null;//All field for form ADD
	var $lines_form_edit    = null;//All field for form EDIT
	var $lines_modul        = null;//All field for modul query
	var $lines_select       = null;//All field for Datatable
	var $lines_profil       = null;//All field for Profile view
	var $modul_workflow     = array();//fil all workflow from sys_workflow for modul
	var $etat_default_wf    = null;//Used when add new modul for creat default task and tas action (valid-archive-delete)
	var $app_mere           = null;//Used for default apps task new modul fit after save default task modul
	var $id_modul_workflow  = null;//Workflow IDfit by workflow fields
	

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

	public function get_log($value)
	{
		echo($value.'#'.$this->log);
		exit();
	}
	/**
	 * Function Get modul info 
	 * fit $this->modul_info (Array) 
	 * @return true
	 */

	public function get_modul()
	{
		global $db;
		$sql = "SELECT sys_modules.*, sys_task.id as id_app, sys_task.app, sys_task.rep as modul_rep, sys_task.sbclass, sys_task_action.etat_desc, sys_task_action.message_class
		FROM 
		sys_modules, sys_task, sys_task_action
		WHERE  sys_task_action.etat_line = 0 AND sys_task_action.app = sys_task.app  AND sys_modules.app_modul = sys_task.app AND sys_modules.id = ".$this->id_modul;
		//exit($sql);
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= 'Get_modul '.$db->Error().' '.$sql;
		}else{
			if (!$db->RowCount())
			{
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->modul_info = $db->RowArray();
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

	/**
	 * Get modul for permission user
	 * @param string modul
	 * @return Array array id - modul description
	 */
	public function Get_list_modul($user_service)
	{
		global $db;
		$modul_array = array();
		$sql_modul = "SELECT m.id, m.modul, m.services, m.description 
		 FROM sys_modules m WHERE m.etat = 1 AND  (m.services LIKE '%\"$user_service\"%' OR m.services LIKE '%-$user_service-%') GROUP BY modul ";
		if(!$db->Query($sql_modul))
		{
			$db->kill($db->Error());
		}else{
			$modul_array = $db->RecordsArray();
			return $modul_array;
		}
	}

	/**
	 * Get Actions for each modul 
	 * used for permission user
	 * @param int $[userid] [Id user]
	 * @param int $[id] [id modul]
	 * @return String SQL query used on each sys_modules.
	 */
	public function Get_action_modul($id, $user)
	{
		global $db;


		$sql = "SELECT
		`sys_task_action`.`descrip` AS app_name
		, `sys_task_action`.`appid` AS app_id
		, `sys_task_action`.`idf` AS idf
		, `sys_task_action`.`type`  AS type
		, `sys_task_action`.`code`  AS code
		, `sys_task_action`.`etat_line`  AS etat_line
		, `sys_task_action`.`id`    AS action_id
		, `sys_task_action`.`service`    AS service
		,(
		CASE
		WHEN (SELECT 1 FROM sys_rules WHERE sys_rules.`action_id` =  sys_task_action.id AND sys_rules.userid = $user GROUP BY userid) = 1 THEN 1
		ELSE 0
		END
		) AS exist_rule
		FROM
		`sys_task`
		INNER JOIN `sys_modules` 
		ON (`sys_task`.`modul` = `sys_modules`.`id`)
		INNER JOIN `sys_task_action` 
		ON (`sys_task_action`.`appid` = `sys_task`.`id`)
		WHERE `sys_modules`.`id` = ".$id;
		return $sql; 
	}
	/**
	 * [Shw Show element of Array modul_info]
	 * @param [type] $key     [Key of element]
	 * @param [type] $no_echo [set null return variable into code else Echo variabl into html]
	 */
	public function Shw($key, $no_echo = null)
	{
		if($this->modul_info[$key] != null)
		{
			if($no_echo == null)
			{
				return $this->modul_info[$key];
			}

			echo $this->modul_info[$key];
		}else{
			echo "";
		}
		
	}

	

	private function creat_modul_path($modul_rep)
	{
		$modul_path = MPATH_MODULES.$modul_rep;
		//exit($modul_path);

		if(!file_exists($modul_path))
		{
			if(!mkdir($modul_path, 0777, true))
			{
				$this->error = false;
				$this->log .='</br>Unable Create modul folder'; 
				return false;
			}
		}

		if(!file_exists($modul_path.'/model'))
		{
			if(!mkdir($modul_path.'/model'))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Model folder'; 
				return false;
			}
		}
		if(!file_exists($modul_path.'/controller'))
		{
			if(!mkdir($modul_path.'/controller'))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Controller folder'; 
				return false;
			}
		}
		if(!file_exists($modul_path.'/view'))
		{
			if(!mkdir($modul_path.'/view'))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View folder'; 
				return false;
			}
		}
		return true;
	}

	private function edit_modul_path($new_modul_rep, $old_modul_rep)
	{
		$new_modul_path = MPATH_MODULES.$new_modul_rep;
		$old_modul_path = MPATH_MODULES.$old_modul_rep;

		if(!file_exists($new_modul_path) and file_exists($old_modul_path))
		{
			if(!rename($old_modul_path, $new_modul_path))
			{
				$this->error = false;
				$this->log .='</br>Unable to rename modul folder'; 
				return false;
			}
		}
	}

    
    /**
     * [save_new_modul Create New Module]
     * @return [Bol] [Send to controller]
     */
	public function save_new_modul()
	{
		
       //Befor execute do the multiple check
		if(!$this->check_exist_modul())
		{
			return false;
		}
		
		//Format multipl services
		$services = json_encode($this->_data['services']);
        //Format modulfolder : 0 =>main 1 => setting 2 => submodul 
		switch ($this->_data['is_setting']) {
			case 0:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
			case 1:
			$folder = $this->_data['modul_setting'].SLASH.'settings'.SLASH.$this->_data['modul'];
			break;
			case 2:
			$folder = $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
			break;
			default:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
		}
        //Prepare fields for DB Query
		global $db;
		$values["modul"]         = MySQL::SQLValue($this->_data['modul']);
		$values["rep_modul"]     = MySQL::SQLValue($folder);
		$values["is_setting"]    = MySQL::SQLValue($this->_data['is_setting']);
		$values["modul_setting"] = MySQL::SQLValue($this->_data['modul_setting']);
		$values["description"]   = MySQL::SQLValue($this->_data['description']);
		$values["tables"]        = MySQL::SQLValue($this->_data['tables']);
		$values["app_modul"]     = MySQL::SQLValue($this->_data['modul']);
		$values["services"]      = MySQL::SQLValue($services);
		$values["etat"]          = MySQL::SQLValue(0);
		$values["creusr"]        = MySQL::SQLValue(session::get('userid'));
		$values["credat"]        = 'CURRENT_TIMESTAMP';
		 
        // If no have an error
		if (!$result = $db->InsertRow("sys_modules", $values))
		{			
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Enregistrement BD non réussie'; 
			return false;
		}else{

			$this->last_id = $result;
			$modul = $this->_data['modul'];
			//Creat default steps Workflow
			if(!$this->auto_add_modul_workflow_steps($this->last_id, 'creat_'.$modul, 'warning', 'Attente Validation', 'Création '.$modul, 0))	
			{
				$this->log .= '</br>Problème WF '.'creat_'.$modul;
			}
			if(!$this->auto_add_modul_workflow_steps($this->last_id, 'valid_'.$modul, 'success', 'Ligne validée', 'Validation  '.$modul, 1))	
			{
				$this->log .= '</br>Problème WF '.'valid_'.$modul;
			}
			if(!$this->auto_add_modul_workflow_steps($this->last_id, 'archive_'.$modul, 'inverse', 'Ligne Archivée', 'Archive '.$modul, 100))	
			{
				$this->log .= '</br>Problème WF '.'archive_'.$modul;
			}
			if(!$this->auto_add_modul_workflow_steps($this->last_id, 'delete_'.$modul, 'danger', 'Ligne Supprimée', 'Suppression '.$modul, 200))	
			{
				$this->log .= '</br>Problème WF '.'delete_'.$modul;
			}			
			//Save first default APP for modul into Task
			$this->creat_modul_path($folder);
			if(!$this->save_default_task($this->last_id, $folder))
			{
				$this->log .= '</br>Problème ajout défaut Task';
				return false;
			}
			$app_mere = $this->app_mere;
			//Add all default app task for modul
			//auto_save_default_task($modul_id, $modul_name, $app, $description, $type_view, $folder, $services, $action_list = 'Y', $sbclass, $etat_workflow, $app_mere, $tables)
			if(!$this->auto_save_default_task($this->last_id, $modul, 'add_'.$modul, 'Ajouter '.$modul, 'formadd', $folder, $services, 
				'N', 'plus', '0', $app_mere, $this->_data['tables']))
			{
				$this->log .= '</br>Problème ajout défaut Task '.'add_'.$modul;
			}
			//Edit
			if(!$this->auto_save_default_task($this->last_id, $modul, 'edit_'.$modul, 'Editer '.$modul, 'formedit', $folder, $services, 
				'Y', 'pencil-square-o blue', '0', $app_mere, $this->_data['tables']))
			{
				$this->log .= '</br>Problème ajout défaut Task '.'edit_'.$modul;
			}
			//valid
			if(!$this->auto_save_default_task($this->last_id, $modul, 'valid_'.$modul, 'Valider '.$modul, 'exec', $folder, $services, 
				'Y', 'check-square-o green', '0', $app_mere, $this->_data['tables']))
			{
				$this->log .= '</br>Problème ajout défaut Task '.'valid_'.$modul;
			}
			//archive
			if(!$this->auto_save_default_task($this->last_id, $modul, 'archive_'.$modul, 'Archiver '.$modul, 'exec', $folder, $services, 
				'Y', 'archive', '0', $app_mere, $this->_data['tables']))
			{
				$this->log .= '</br>Problème ajout défaut Task '.'archive_'.$modul;
			}
			//Delete
			if(!$this->auto_save_default_task($this->last_id, $modul, 'delete_'.$modul, 'Supprimer '.$modul, 'exec', $folder, $services, 
				'Y', 'trash-o red', '0', $app_mere, $this->_data['tables']))
			{
				$this->log .= '</br>Problème ajout défaut Task '.'delete_'.$modul;
			}
			$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['description'];
			return true;
			
		}
		
        //check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}

	}


	/**
	 * [edit_exist_modul Edit existing module]
	 * @return [Bol] [Send to controller]
	 */
	public function edit_exist_modul()
	{
		
       //Befor execute do the multiple check
       //$services_last = NULL
		$this->id_modul = $this->_data['id'];
		$this->get_modul();
		$this->last_id     = $this->_data['id'];
		$this->default_app = $this->_data['id_app'];
		$this->check_exist_modul(1);
		//Format multipl services
		if($this->_data['services'] != NULL)
		{
			$services = json_encode($this->_data['services']);
			$services = str_replace('"', '-', $services);
			$services = str_replace('-,-', '-', $services);
			$services_last = $services != $this->modul_info['services'] ? $services : $this->modul_info['services'];

		}else{
			$services_last = $this->modul_info['services'];
		}

		//Format modulfolder : 0 =>main 1 => setting 2 => submodul 
		switch ($this->_data['is_setting']) {
			case 0:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
			case 1:
			$folder = $this->_data['modul_setting'].SLASH.'settings'.SLASH.$this->_data['modul'];
			break;
			case 2:
			$folder = $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
			break;
			default:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
		}
        //$folder = $this->_data['is_setting'] == 0 ? $this->_data['modul'] : $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];

		global $db;
		$values["modul"]         = MySQL::SQLValue($this->_data['modul']);
		$values["rep_modul"]     = MySQL::SQLValue($folder);
		$values["is_setting"]    = MySQL::SQLValue($this->_data['is_setting']);
		$values["modul_setting"] = MySQL::SQLValue($this->_data['modul_setting']);
		$values["description"]   = MySQL::SQLValue($this->_data['description']);
		$values["tables"]        = MySQL::SQLValue($this->_data['tables']);
		$values["app_modul"]     = MySQL::SQLValue($this->_data['app']);
		$values["services"]      = MySQL::SQLValue($services_last);
		$wheres["id"]            = MySQL::SQLValue($this->_data['id']);
		//var_dump($this->_data);
		//exit();
		
		
		//check if package required stop Insert
		//$this->check_file('pkg', 'Le Package de module.');

		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("sys_modules", $values, $wheres)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Modification BD non réussie'; 

			}else{

				$this->last_id = $result;
				//Rename the Modul folder
				if($this->_data['modul'] != $this->modul_info['rep_modul'])
				{
					$this->edit_modul_path($folder, $this->modul_info['rep_modul']);
				}

				
				//Edit first default APP for modul into Task
				$this->edit_task_from_edit_modul($this->_data['id_app'], $this->_data['id'], $services_last);
				
				if($this->error == true)
				{
					$this->log = '</br>Modification réussie: <b>';
					
				}else{
					$this->log .= '</br>Modification non réussie: <b>';
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

	private function edit_task_from_edit_modul($id_task, $id_modul, $services)
	{
		
        //Format modulfolder : 0 =>main 1 => setting 2 => submodul 
		switch ($this->_data['is_setting']) {
			case 0:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
			case 1:
			$folder = $this->_data['modul_setting'].SLASH.'settings'.SLASH.$this->_data['modul'];
			break;
			case 2:
			$folder = $this->_data['modul_setting'].SLASH.'submodul'.SLASH.$this->_data['modul'];
			break;
			default:
			$folder = $this->_data['modul'].SLASH.'main'; 
			break;
		}


		global $db;
		$values["app"]         = MySQL::SQLValue($this->_data['app']);
		$values["file"]        = MySQL::SQLValue($this->_data['app']);
		$values["rep"]         = MySQL::SQLValue($folder);
		$values["modul"]       = MySQL::SQLValue($id_modul);
		$values["dscrip"]      = MySQL::SQLValue($this->_data['description']);
		$values["session"]     = MySQL::SQLValue(1);
		$values["services"]    = MySQL::SQLValue($services);
		$values["sbclass"]     = MySQL::SQLValue($this->_data['sbclass']);
		$values["ajax"]        = MySQL::SQLValue(1);
		$values["app_sys"]     = MySQL::SQLValue(0);
		$values["etat"]        = MySQL::SQLValue(0);
		$wheres["id"]          = MySQL::SQLValue($id_task);



		
		if(!$result = $db->UpdateRows("task", $values, $wheres)) 
		{

			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Modification Task BD non réussie'; 


		}else{
			$this->error = true;
			$this->log .='</br>Modification Task réussie';
			//Rename files if app modified
			if($this->_data['app'] != $this->modul_info['app_modul'])
			{
				
				$this->rename_app_files($folder, $this->modul_info['app_modul'], $this->_data['app']);
			}
			$this->edit_default_task_action($id_task, $this->_data['description'], $this->_data['message_class'], $this->_data['etat_desc']);

		}
		//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}


	}

	private function rename_app_files($modul_rep, $old_task_name, $new_task_name )
	{
		$modul_path = MPATH_MODULES.$modul_rep;
		//exit("2#".$modul_path);

		if($modul_rep == null)
		{
			$this->error = false;
			$this->log .='</br>Unable get Module Path'; 
			return false;

		}

		//Old names
		$old_file_c = $modul_path.'/controller/'.$old_task_name.'_c.php';
		$old_file_list_c = $modul_path.'/controller/list'.$old_task_name.'_c.php';
		$old_file_action_c = $modul_path.'/controller/action'.$old_task_name.'_c.php';
		$old_file_m = $modul_path.'/model/'.$old_task_name.'_m.php';
		$old_file_v = $modul_path.'/view/'.$old_task_name.'_v.php';
		//New Names
		$new_file_c = $modul_path.'/controller/'.$new_task_name.'_c.php';
		$new_file_list_c = $modul_path.'/controller/list'.$new_task_name.'_c.php';
		$new_file_action_c = $modul_path.'/controller/action'.$new_task_name.'_c.php';
		$new_file_m = $modul_path.'/model/'.$new_task_name.'_m.php';
		$new_file_v = $modul_path.'/view/'.$new_task_name.'_v.php';

		if(!file_exists($old_file_c) or !rename($old_file_c, $new_file_c))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_c  '.$old_file_c.'  '.$new_file_c; 
			//return false;
		}
		if(!file_exists($old_file_list_c) or !rename($old_file_list_c, $new_file_list_c))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_list_c'; 
			//return false;
		}
		if(!file_exists($old_file_action_c) or !rename($old_file_action_c, $new_file_action_c))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_action_c'; 
			//return false;
		}
		if(!file_exists($old_file_m) or !rename($old_file_m, $new_file_m))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_m'; 
			//return false;
		}
		if(!file_exists($old_file_v) or !rename($old_file_v, $new_file_v))
		{
			$this->error = false;
			$this->log .='</br>Unable to rename file_v'; 
			//return false;
		}
	}
	//Check exist modul
	private function check_exist_modul($edit = null)
	{
		global $db;
		$sql_edit = $edit == null ? null: " AND id <> ".$this->last_id;
		$sql_req = "SELECT modul FROM sys_modules 
		WHERE modul = ". MySQL::SQLValue($this->_data['modul']) ." $sql_edit ";
		$result = $db->QuerySingleValue0($sql_req);		
		if ($result != "0")
		{
			$this->error = false;
			$this->log .='</br>Exist Module '.$this->_data['modul'];
			return false;
		}
		return true;
	}

	//Check exist task
	private function check_exist_task($app_name, $edit = null)
	{
		global $db;

		$sql_edit = $edit == null ? null: " AND id <> ".$this->default_app;
		$sql      = "SELECT app FROM sys_task 
		where app = ". MySQL::SQLValue($app_name) ." $sql_edit ";
		$result = $db->QuerySingleValue0($sql);
		
		if ($result != "0") 
		{
			$this->error = false;
			$this->log .='</br>Exist App '.$app_name;
			return false;
		}
		return true;
	}
	/**
	 * [check_file description]
	 * @param  [type] $item [description]
	 * @param  [type] $msg  [description]
	 * @param  [type] $edit [description]
	 * @return [type]       [description]
	 */
	Private function check_file($item, $msg = null, $edit = null)
	{
		if($edit != null && !file_exists($edit)){
			$this->log .= '</br>Il faut choisir '.$msg.' pour la mise à jour';
			$this->error = false;
		}else{
			if($edit == null && $this->exige_.$item == true && ($this->_data[$item.'_id'] == null || !file_exists($this->_data[$item.'_id'])))
			{
				$this->log .= '</br>Il faut choisir '.$msg. '  '.$edit;
				$this->error = false; 
			}
		}

	}
	
	/**
	* ZONE TASK FOR MODULE
	* Function get Task
	* function add default task for modul
	* Function Add Task
	* Function Edit Task
	* Function delete Task
	* Function get task_action_liste
	*/
    var $id_task; // Task ID append when request
    public function get_task()
    {
    	global $db;
    	$sql = "SELECT sys_task.*, sys_task_action.message_class, sys_task_action.etat_desc, sys_modules.description
    	FROM sys_task, sys_task_action, sys_modules
    	WHERE sys_task_action.appid = sys_task.id AND sys_modules.id = sys_task.modul AND sys_task.id = ".$this->id_task;
        
    	if(!$db->Query($sql))
    	{
    		$this->error = false;
    		$this->log  .= $db->Error();
    	}else{
    		if (!$db->RowCount()) 
    		{
    			$this->error = false;
    			$this->log .= 'Aucun enregistrement trouvé ';
    		} else {
    			$this->task_info = $db->RowArray();
    			$this->error = true;
    		}
    	}		
    	if($this->error == false)
    	{
    		return false;
    	}else{
    		return true;
    	}
    }

    /**
     * [save_default_task when add new modul]
     * @param  [type] $modul_id [description]
     * @return [type]           [description]
     */
    public function save_default_task($modul_id, $folder)
    {
    	if(!$this->check_exist_task($this->_data['modul']))
    	{
    		return false;
    	}
    	$modul_name = $this->_data['modul'];
    	$services = json_encode($this->_data['services']);
		
		$values["app"]         = MySQL::SQLValue($this->_data['modul']);
		$values["file"]        = MySQL::SQLValue($this->_data['modul']);
		$values["rep"]         = MySQL::SQLValue($folder);
		$values["modul"]       = MySQL::SQLValue($modul_id);
		$values["dscrip"]      = MySQL::SQLValue($this->_data['description']);
		$values["session"]     = MySQL::SQLValue(1);
		$values["ajax"]        = MySQL::SQLValue(1);		
		$values["sbclass"]     = MySQL::SQLValue($this->_data['sbclass']);
		//$values["app_sys"]     = MySQL::SQLValue($app_sys);
		$values["etat"]        = MySQL::SQLValue(0);
		$values["type_view"]   = MySQL::SQLValue('list');
		$values["services"]    = MySQL::SQLValue($services);
		$values["creusr"]      = MySQL::SQLValue(session::get('userid'));
		$values["credat"]      = 'CURRENT_TIMESTAMP';

        global $db;
		if(!$result = $db->InsertRow("sys_task", $values)) 
		{
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Enregistrement Task BD non réussie'; 
		}else{
			$app_id = $result;
			$this->app_mere = $app_id;
			//Add default task_action
			//add_default_task_action($modul_id, $app_id, $app, $mode_exec, $icone, $description, $services, $action_list = 'N')
			$this->add_default_task_action($modul_id, $app_id, $this->_data['modul'], 'this_url', $this->_data['sbclass'], $this->_data['description'], $services);
			//Creat files of task
			//creat_task_files($modul_rep, $task_name, $modul_name, $type_view, $app_base, $table)
			$creat_fiels = new Mmodul_creat_files();
			$creat_fiels->creat_task_files($folder, $this->_data['modul'], $this->_data['modul'], 'list', true, $this->_data['tables']);
		}
		//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
    }

    /**
	 * [save_new_task Save Task into table Task]
	 * @param  [int] $modul_id [id of mdule]
	 * @return [fit error]           [fit Error variable]
	 */
    public function save_new_task($modul_id)
    {
    	global $db;     
      	//Get modul Info
    	$this->id_modul = $modul_id;
    	$this->get_modul();
    	if(!$this->check_exist_task($this->_data['app']))
    	{
    		return false;
    	}
    	$services = json_encode($this->_data['services']);

    	$values["app"]         = MySQL::SQLValue($this->_data['app']);
    	$values["file"]        = MySQL::SQLValue($this->_data['app']);
    	$values["rep"]         = MySQL::SQLValue($this->modul_info['rep_modul']);
    	$values["modul"]       = MySQL::SQLValue($modul_id);
    	$values["dscrip"]      = MySQL::SQLValue($this->_data['description']);
    	$values["sbclass"]     = MySQL::SQLValue($this->_data['sbclass']);
    	$values["session"]     = MySQL::SQLValue(1);
    	$values["ajax"]        = MySQL::SQLValue(1);
    	$values["etat"]        = MySQL::SQLValue(0);
    	$values["type_view"]   = MySQL::SQLValue($this->_data['type_view']);
    	$values["services"]    = MySQL::SQLValue($services);
    	if(!$result = $db->InsertRow("sys_task", $values)) 
    	{
    		$this->log .= $db->Error();
    		$this->error = false;
    		$this->log .='</br>Enregistrement Task BD non réussie'; 

    	}else{
    		$app_id         = $result;
    		$mode_exec      = $this->_data['type_view'] == 'exec' ? 'this_exec' : 'this_url';
			$icone          = $this->_data['action_list'] == 'Y' ? $this->_data['sbclass'] : null;
			$steps_workflow = $this->_data['etat_workflow'];
    		//Add default task_action
    		if(!$this->add_default_task_action($modul_id, $app_id, $this->_data['app'], $mode_exec, $icone, $this->_data['description'], $services, 'N', null))
			{
				$this->log .= '</br> Erreur add_default_task_action';
				return false;
			}		
			//if is action list && no empty steps => add task_action each step workflow ELse Add one task_action
			if($this->_data['action_list'] == 'Y' && !empty($steps_workflow))
			{
				$app_id         = $this->_data['app_mere'];
				//For each Step Workflow add Task Action with all settings
				foreach ($steps_workflow as $key => $step_id)
				{
					if(!$this->add_default_task_action($modul_id, $app_id, $this->_data['app'], $mode_exec, $icone, $this->_data['description'], $services, $this->_data['action_list'], $step_id))
					{
						$this->log .= '</br> Erreur add_default_task_action Step =>'.$step_id;
						return false;
					}					
				}
			}    		
			//Creat files of task			
    		$creat_fiels = new Mmodul_creat_files();
    		$creat_fiels->creat_task_files($this->modul_info['rep_modul'], $this->_data['app'], $this->modul_info['modul'], $this->_data['type_view'], false, $this->modul_info['tables']);
	
		}
		//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			$this->log .='</br>Enregistrement réussie';
			return true;
		}
	}

	/**
	 * [auto_save_default_task Save Task into table Task]
	 * @param  [int] $modul_id [id of mdule]
	 * @return [fit error]           [fit Error variable]
	 */
    public function auto_save_default_task($modul_id, $modul_name, $app, $description, $type_view, $folder, $services, $action_list = 'Y', $sbclass, $etat_workflow, $app_mere, $tables)
    {
    	global $db;
    	if(!$this->check_exist_task($app))
    	{
    		return false;
    	}    	
    	$values["app"]         = MySQL::SQLValue($app);
    	$values["file"]        = MySQL::SQLValue($app);
    	$values["rep"]         = MySQL::SQLValue($folder);
    	$values["modul"]       = MySQL::SQLValue($modul_id);
    	$values["dscrip"]      = MySQL::SQLValue($description);
    	$values["session"]     = MySQL::SQLValue(1);
    	$values["ajax"]        = MySQL::SQLValue(1);
    	$values["etat"]        = MySQL::SQLValue(0);
    	$values["type_view"]   = MySQL::SQLValue($type_view);
    	$values["services"]    = MySQL::SQLValue($services);
    	if(!$result = $db->InsertRow("sys_task", $values)) 
    	{
    		$this->log .= $db->Error();
    		$this->log .='</br>Enregistrement Task '.$app.' BD non réussie'; 
    		return false;
    	}else{
    		$app_id         = $result;
    		$mode_exec      = $type_view == 'exec' ? 'this_exec' : 'this_url';
			$icone          = $action_list == 'Y' ? $sbclass : null;
			$steps_workflow = $etat_workflow;
    		//Add default task_action
    		if(!$this->add_default_task_action($modul_id, $app_id, $app, $mode_exec, $icone, $description, $services, 'N', null))
			{
				$this->log .= '</br> Erreur add_default_task_action';
				return false;
			}			
			//if is action list add task_action each step workflow ELse Add one task_action
			if($action_list == 'Y')
			{
				$app_id         = $app_mere;
				$step_id  = $this->etat_default_wf;
				if(!$this->add_default_task_action($modul_id, $app_id, $app, $mode_exec, $icone, $description, $services, $action_list, $step_id ))
				{
					$this->log .= $db->Error();
					$this->log .= '</br> Erreur add_default_task_action Step =>'.$step_id;
					return false;
				}
			}    		
			//Creat files of task			
    		$creat_fiels = new Mmodul_creat_files();
    		$creat_fiels->creat_task_files($folder, $app, $modul_name, $type_view, false, $tables);	
		}
		//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			$this->log .='</br>Enregistrement réussie';
			return true;
		}
	}



	public function edit_exist_task($modul_id)
	{
		global $db;
		$this->id_modul = $modul_id;
		$this->get_modul();


		$modul_name = $this->modul_info['modul'];

		//Befor execute do the multiple check
		$this->default_app = $this->_data['id_app'];
		//Get Task info
		$this->id_task =$this->default_app;
		$this->get_task();
		$this->check_exist_task(1);

		//var_dump($this->_data);
		if($this->_data['services'] != null){
			$services = json_encode($this->_data['services']);
			$services = str_replace('"', '-', $services);
			$services = str_replace('-,-', '-', $services);

		}else{
			$services = $this->task_info['services'];

		}
		



		    //Format Variabl for DB

		$app       = $this->_data['app'];
		$file      = $this->_data['app'];
		$rep       = $modul_name;
		$modul     = $modul_id;
		$dscrip    = $this->_data['description'];
		$sbclass   = $this->_data['sbclass'];
		$type_view = $this->_data['type_view'];
		$session   = 1;
		$ajax      = 1;
		//$app_sys   = 0;
		$etat      = 0;
		$services  = $services;

		
		
		
		$values["app"]         = MySQL::SQLValue($app);
		$values["file"]        = MySQL::SQLValue($file);
		$values["rep"]         = MySQL::SQLValue($this->modul_info['rep_modul']);
		$values["modul"]       = MySQL::SQLValue($modul_id);
		$values["dscrip"]      = MySQL::SQLValue($dscrip);
		$values["session"]     = MySQL::SQLValue($session);
		$values["sbclass"]     = MySQL::SQLValue($sbclass);
		$values["ajax"]        = MySQL::SQLValue($ajax);
		//$values["app_sys"]     = MySQL::SQLValue($app_sys);
		$values["etat"]        = MySQL::SQLValue($etat);
		$values["type_view"]   = MySQL::SQLValue($type_view);
		$values["services"]    = MySQL::SQLValue($services);
		

		$wheres["id"]         = MySQL::SQLValue($this->_data['id_app']);

		// If we have an error
		if($this->error == true)
		{
			if(!$result = $db->UpdateRows("sys_task", $values, $wheres)) 
			{
		    	//exit($db->BuildSQLUpdate("task", $values, $wheres));

				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement Task BD non réussie'; 

			}else{
				//Rename files if app modified
				if($this->_data['app'] != $this->task_info['app'])
				{
				    //rename_app_files($modul_rep, $old_task_name, $new_task_name )
					$this->rename_app_files($rep, $this->task_info['app'], $this->_data['app']);
				}
				$this->edit_default_task_action($this->_data['id_app'], $this->_data['description'], $this->_data['message_class'], $this->_data['etat_desc']);
				$this->error = true;
				$this->log .='</br>Modification réussie';
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

	static public function check_exist_service_etat($services, $etat, $appid, $edit)
	{
		global $db;
		$return = true;
		foreach ($services as $service) {
			$edit_check = $edit == null ? 0 : 1;
			$result = $db->QuerySingleValue0("SELECT COUNT(id) FROM task_action WHERE etat_line = $etat AND service LIKE '%-$service-%' AND appid = $appid AND TYPE = 1  ");

			if ($result != $edit_check) {

				$return = false;
			}
		}

		return $return;


	}

	/**
	 * [add_default_task_action Save default Task action for new added task]
	 * @param [int] $app_id [id of task]
	 * @param [string] $description [Description of task action]
	 * @return [fit error]           [fit Error variable]
	 */

	public function add_default_task_action($modul_id, $app_id, $app, $mode_exec, $icone, $description, $services, $action_list = 'N' , $step_id = null)
	{		
       //Befor execute do the multiple check
		$idf = MD5($description.'0def');
        
		$etat_line     = 0;
		$message_etat  = null;
		$message_class = null;
		$code_exec     = null;
		$etat_desc     = null;
		$type          = 0;
        //If is action_list then get all workflow setting $this->modul_workflow['mode_exec']
        if($action_list == 'Y' && $step_id != null)
        {
        	if(!$this->get_modul_workflow($step_id))
        	{
        		return false;
        	}
        	        	
			$code_exec     = '<li><a href="#" class="'.$mode_exec.'" data="%id%" rel="'.$app.'"  ><i class="ace-icon fa fa-'.$icone.' bigger-100"></i> '.$description.'</a></li>';
			$message_etat  = $this->modul_workflow['message_etat'];
			$etat_desc     = $this->modul_workflow['etat_desc'];
			$etat_line     = $this->modul_workflow['etat_line'];
			$message_class = $this->modul_workflow['color'];
			$idf           = MD5($description.$etat_line.$step_id.'0def');
			$type          = 0;						
        } 
		$this->check_exist_idf($idf);
		global $db;
		//$service               = '-'.session::get('service').'-';
		$values["appid"]         = MySQL::SQLValue($app_id);
		$values["app"]           = MySQL::SQLValue($app);
		//to difference of next task action we use 0def
		$values["idf"]           = MySQL::SQLValue($idf);
		$values["descrip"]       = MySQL::SQLValue($description);
		$values["type"]          = MySQL::SQLValue($type);
		$values["service"]       = MySQL::SQLValue($services);
		$values["mode_exec"]     = MySQL::SQLValue($mode_exec);
		$values["etat_line"]     = MySQL::SQLValue($etat_line);
		$values["etat_desc"]     = MySQL::SQLValue($etat_desc);
		$values["message_etat"]  = MySQL::SQLValue($message_etat);
		$values["message_class"] = MySQL::SQLValue($message_class);
		$values["code"]          = MySQL::SQLValue($code_exec);
		$values["notif"]         = MySQL::SQLValue(0);
		$values["creusr"]        = MySQL::SQLValue(session::get('userid'));
		$values["credat"]        = 'CURRENT_TIMESTAMP';
		
        // If we have an error
		if($this->error == true){
			if (!$result = $db->InsertRow("sys_task_action", $values))
			{
				$this->error = false;
				$this->log .='</br>Enregistrement Task Actions BD non réussie'; 
			}else{
				$this->error = true;
				$this->log = '</br>Enregistrement réussie: <b>';
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
	 * [edit_default_task_action Edit default Task action for edited task]
	 * @param [int] $app_id [id of task]
	 * @param [string] $description [Description of task action]
	 * @return [fit error]           [fit Error variable]
	 */

	public function edit_default_task_action($app_id, $description, $message_class, $etat_desc)
	{


		
       //Befor execute do the multiple check
		$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
		if($this->_data['services'] !=Null){
			$services = json_encode($this->_data['services']);
			$services = str_replace('"', '-', $services);
			$services = str_replace('-,-', '-', $services);
			$values["service"]   = MySQL::SQLValue($services);
		}
        $idf = MD5($description.'0def');
        $this->check_exist_idf($idf); 
		//$service               = '-'.session::get('service').'-';
		$values["appid"]         = MySQL::SQLValue($app_id);
		$values["app"]           = MySQL::SQLValue($this->_data['app']);
		$values["idf"]           = MySQL::SQLValue($idf);
		$values["descrip"]       = MySQL::SQLValue($description);
		$values["type"]          = MySQL::SQLValue(1);
		$values["etat_line"]     = MySQL::SQLValue(0);
		$values["notif"]         = MySQL::SQLValue(0);
		$values["etat_desc"]     = MySQL::SQLValue($etat_desc);
		$values["message_class"] = MySQL::SQLValue($message_class);
		$values["message_etat"]  = MySQL::SQLValue($etat_desc);
		$values["class"]         = MySQL::SQLValue($app_id);
		$wheres['class']         = MySQL::SQLValue($app_id);
		
		
		
		global $db;
        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("task_action", $values, $wheres))
			{
				//$db->Kill();
				$this->log .= $db->Error().' '.$db->BuildSQLUpdate("task_action", $values, $wheres);
				$this->error = false;
				$this->log .='</br>Enregistrement Task Actions BD non réussie'; 

			}else{
				$this->error == true;
				$this->log = '</br>Enregistrement TA réussie ';

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







	public function delete_modul()
	{
		global $db;
		$id_modul = $this->modul_id;
		$where['id'] = MySQL::SQLValue($id_modul);
		if(!$db->DeleteRows('modul',$where))
		{
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

		}else{
			$this->error = true;
			exit('deleted');
			$this->log .='</br>Suppression réussie';
		}
    	//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
	}


    /**
     * @return Bool true or false
     */
    public function delete_task()
    {
    	global $db;
    	$id_task = $this->id_task;
    	$this->get_task();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_task);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>Le id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('task',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('task',$where);
    		$this->error = false;
    		$this->log .='</br>Suppression non réussie';

    	}else{
    		//remove files
    		$modul_rep = $this->task_info['rep'];
    		$task_name = $this->task_info['app'];
    		$modul_path = MPATH_MODULES.$modul_rep;

    		$file_c = $modul_path.'/controller/'.$task_name.'_c.php';
    		$file_list_c = $modul_path.'/controller/list'.$task_name.'_c.php';
    		$file_action_c = $modul_path.'/controller/action'.$task_name.'_c.php';
    		$file_m = $modul_path.'/model/'.$task_name.'_m.php';
    		$file_v = $modul_path.'/view/'.$task_name.'_v.php';
    		if(file_exists($file_c)) unlink($file_c);
    		if(file_exists($file_list_c)) unlink($file_list_c);
    		if(file_exists($file_action_c)) unlink($file_action_c);
    		if(file_exists($file_m)) unlink($file_m);
    		if(file_exists($file_v)) unlink($file_v);

    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';
    	}
    	//check if last error is true then return true else rturn false.
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}
    }


    /**
 * ZONE TASK_ACTION FOR MODULE
 * Function get Task_action
 * Function Add Task_action
 * Function Edit Task_action
 * Function delete Task_action
 * Function get task_action_action_liste
 */
    var $id_task_action; // Task_action ID append when request
    var $task_action_info = Array(); //Array for all info taskaction


    //Get all info task_action from database for edit form

    public function get_task_action()
    {
    	global $db;

    	$sql = "SELECT sys_task_action.*
    	FROM sys_task_action
    	WHERE  sys_task_action.id = ".$this->id_task_action;

    	if(!$db->Query($sql))
    	{
    		$this->error = false;
    		$this->log  .= $db->Error();
    	}else{
    		if ($db->RowCount() == 0) {
    			$this->error = false;
    			$this->log .= 'Aucun enregistrement trouvé ';
    		} else {
    			$this->task_action_info = $db->RowArray();
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
    /**
     * [g Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function g($key)
    {
        if($this->task_info[$key] != null)
        {
            return $this->task_info[$key];
        }else{
            return null;
        }
    }
    Private function update_message_task_action($app, $etat_line, $message, $etat_desc, $msg_class, $notif)
    {

    	global $db;

    	$values["etat_desc"]     = MySQL::SQLValue($etat_desc);
    	$values["message_class"] = MySQL::SQLValue($msg_class);
    	$values["message_etat"]  = MySQL::SQLValue($message);
    	//$values["notif"]         = MySQL::SQLValue($notif);
    	$wheres["appid"]         = MySQL::SQLValue($app); 
    	$wheres["etat_line"]     = MySQL::SQLValue($etat_line);
    	$wheres["type"]          = MySQL::SQLValue(0);

        
    	if (!$result             = $db->UpdateRows("sys_task_action", $values, $wheres))
    	{
				//$db->Kill();
    		$this->log .= $db->Error().' '.$db->BuildSQLUpdate("sys_task_action", $values, $wheres);
    		$this->error = false;
    		$this->log .='</br>Update all Task action non réussie'; 

    	}else{
    		$this->error == true;
    		$this->log = '</br>MAJ all Task action réussie: <b>';

    	}

    }
    Private function check_exist_idf($idf, $is_edit = false)
    {   
    	global $db;
    	$sql_req = "SELECT COUNT(id) FROM sys_task_action WHERE idf = '$idf'";
    	$alpha = !$is_edit ? 0 : 1;
    	if($db->QuerySingleValue0($sql_req) > $alpha){
    		$this->error = false;
    		$this->log .= '</br>La descripttion existe déjà dans la table Task Action';
    		$this->log .= '<br/>Chercher le IDF : '.$idf;
    	}
    }

    /**
     * [add_task_action Add Task Action (Autorisation_Lien_WF)]
     */
    public function add_task_action()
    {
    	
        


    	$services = json_encode($this->_data['services']);
    	
    	$code    = '<li><a href="#" class="'.$this->_data['mode_exec'].'" data="%id%" rel="'.$this->_data['app'].'"  ><i class="ace-icon fa fa-'.$this->_data['class'].' bigger-100"></i> '.$this->_data['description'].'</a></li>';
    	$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
    	$idf     = MD5($this->_data['description'].$this->_data['etat_line'].$services);
        $this->check_exist_idf($idf);
    	global $db;
    	$values["appid"]         = MySQL::SQLValue($this->_data['id_task']);
    	$values["descrip"]       = MySQL::SQLValue($this->_data['description']);
    	$values["type"]          = MySQL::SQLValue(0);
    	$values["service"]       = MySQL::SQLValue($services);
    	$values["mode_exec"]     = MySQL::SQLValue($this->_data['mode_exec']);
    	$values["app"]           = MySQL::SQLValue($this->_data['app']);
    	$values["idf"]           = MySQL::SQLValue($idf);
    	$values["class"]         = MySQL::SQLValue($this->_data['class']);
    	$values["code"]          = MySQL::SQLValue($code);
    	$values["etat_line"]     = MySQL::SQLValue($this->_data['etat_line']);
    	$values["etat_desc"]     = MySQL::SQLValue($this->_data['etat_desc']);
    	$values["message_class"] = MySQL::SQLValue($this->_data['message_class']);
    	$values["message_etat"]  = MySQL::SQLValue($message);
    	$values["notif"]         = MySQL::SQLValue($this->_data['notif']);


        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->InsertRow("sys_task_action", $values))
    		{
				//$db->Kill();
    			$this->log .= $db->Error().' '.$db->BuildSQLinsert("sys_task_action", $values);
    			$this->error = false;
    			$this->log .='</br>Enregistrement Task Actions BD non réussie'; 

    		}else{
    			$this->error == true;
    			$this->log = '</br>Enregistrement réussie: <b>';
    			$this->update_message_task_action($this->_data['id_task'], $this->_data['etat_line'], $message, $this->_data['etat_desc'], $this->_data['message_class'], $this->_data['notif']);

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
     * [edit_task_action Edit Task Action]
     * @return [bol] [Bol send to controller]
     */
    public function edit_task_action()
    {
    	
    	$this->get_task_action();
    	if($this->_data['services'] != null){

    		$services = json_encode($this->_data['services']);
    		$services = str_replace('"', '-', $services);
    		$services = str_replace('-,-', '-', $services);

    	}else{
    		$services = $this->task_action_info['service'];

    	}


    	$code    = '<li><a href="#" class="'.$this->_data['mode_exec'].'" data="%id%" rel="'.$this->_data['app'].'"  ><i class="ace-icon fa fa-'.$this->_data['class'].' bigger-100"></i> '.$this->_data['description'].'</a></li>';
    	$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
    	$idf     = MD5($this->_data['description'].$this->_data['etat_line'].$services);
        $this->check_exist_idf($idf, true);
    	global $db;
    	$values["appid"]         = MySQL::SQLValue($this->_data['id_task']);
    	$values["descrip"]       = MySQL::SQLValue($this->_data['description']);
    	$values["type"]          = MySQL::SQLValue(0);
    	$values["service"]       = MySQL::SQLValue($services);
    	$values["mode_exec"]     = MySQL::SQLValue($this->_data['mode_exec']);
    	$values["app"]           = MySQL::SQLValue($this->_data['app']);
    	$values["idf"]           = MySQL::SQLValue($idf);
    	$values["class"]         = MySQL::SQLValue($this->_data['class']);
    	$values["code"]          = MySQL::SQLValue($code);
    	$values["etat_line"]     = MySQL::SQLValue($this->_data['etat_line']);
    	$values["etat_desc"]     = MySQL::SQLValue($this->_data['etat_desc']);
    	$values["message_class"] = MySQL::SQLValue($this->_data['message_class']);
    	$values["message_etat"]  = MySQL::SQLValue($message);
    	$values["notif"]         = MySQL::SQLValue($this->_data['notif']);
    	$wheres["id"]            = MySQL::SQLValue($this->id_task_action);


        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->UpdateRows("task_action", $values, $wheres))
    		{
				//$db->Kill();
    			$this->log .= $db->Error().' '.$db->BuildSQLUpdate("task_action", $values);
    			$this->error = false;
    			$this->log .='</br>Modification Task Actions BD non réussie'; 

    		}else{
    			$this->error == true;
    			$this->log = '</br>Modification réussie: <b>';
                $this->update_message_task_action($this->_data['id_task'], $this->_data['etat_line'], $message, $this->_data['etat_desc'], $this->_data['message_class'], $this->_data['notif']);
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
     * [add_rule_wf Add Task_action for WF (view line)]
     */
    public function add_rule_wf()
    {

    	$services = json_encode($this->_data['services']);
    	$services = str_replace('"', '-', $services);
    	$services = str_replace('-,-', '-', $services);
    	$message = '<span class="label label-sm label-'.$this->_data['message_class'] .'">'.$this->_data['etat_desc'].'</span>';
    	global $db;
    	$values["appid"]         = MySQL::SQLValue($this->_data['id_task']);
    	$values["descrip"]       = MySQL::SQLValue($this->_data['description']);
    	$values["type"]          = MySQL::SQLValue(1);
    	$values["service"]       = MySQL::SQLValue($services);
    	$values["etat_line"]     = MySQL::SQLValue($this->_data['etat_line']);
    	$values["idf"]           = MySQL::SQLValue(MD5($this->_data['description'].$this->_data['etat_line']));
    	$values["etat_desc"]     = MySQL::SQLValue($this->_data['etat_desc']);
    	$values["message_class"] = MySQL::SQLValue($this->_data['message_class']);
    	$values["message_etat"]  = MySQL::SQLValue($message);



        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->InsertRow("task_action", $values))
    		{
				//$db->Kill();
    			$this->log .= $db->Error().' '.$db->BuildSQLinsert("task_action", $values);
    			$this->error = false;
    			$this->log .='</br>Enregistrement Autorisation WF BD non réussie'; 
    			$this->update_message_task_action($this->_data['id_task'], $this->_data['etat_line'], $message, $this->_data['etat_desc'], $this->_data['message_class'], 0);

    		}else{
    			$this->error == true;
    			$this->log = '</br>Enregistrement réussie: <b>';

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
     * [get_order_detail description]
     * @param  [type] $tkn_frm [description]
     * @return [type]          [description]
     */
    private function get_order_work_flow($modul_id)
    {
    	global $db;
    	$req_sql = "SELECT IFNULL(MAX(sys_workflow.etat_line)+1,0) AS this_order FROM sys_workflow WHERE sys_workflow.modul_id = '$modul_id' AND sys_workflow.etat_line NOT IN(100, 200)";
    	$this_order = $db->QuerySingleValue0($req_sql);
    	return $this_order;
    }
    /**
     * [check_code_usage description]
     * @param  [type] $modul_id [description]
     * @param  [type] $code     [description]
     * @return [type]           [description]
     */
    private function check_code_usage($modul_id, $code, $edit = false)
    {
    	global $db;
    	$code =  MySQL::SQLValue($code);
    	$crc = $edit == true ? 1 : 0;
    	$req_sql = "SELECT COUNT(id) FROM sys_workflow WHERE sys_workflow.modul_id = '$modul_id' AND sys_workflow.code LIKE  $code";
    	$count_code = $db->QuerySingleValue0($req_sql);
    	if($count_code > $crc)
    	{   
    	    		
    		$this->log .= '</br>Le Code usage existe pour ce modul  ';
    		return false;

    	}
    	return true;
    	
    }
        /**
    * [edit_modul_workflow Add Task_action for WF (view line)]
    */
    public function edit_modul_workflow()
    {
    	if(!$this->check_code_usage($this->_data['modul_id'], $this->_data['code'], true))
    	{
            return false;
    	}

    	$message = '<span class="label label-sm label-'.$this->_data['color'] .'">'.$this->_data['message_etat'].'</span>';
    	global $db;
    	$etat_line = $this->get_order_work_flow($this->_data['modul_id']);

		$values["modul_id"]     = MySQL::SQLValue($this->_data['modul_id']);
		$values["descrip"]      = MySQL::SQLValue($this->_data['descrip']);
		$values["etat_line"]    = MySQL::SQLValue($etat_line);
		$values["code"]         = MySQL::SQLValue($this->_data['code']);
		$values["color"]        = MySQL::SQLValue($this->_data['color']);
		$values["message_etat"] = MySQL::SQLValue($message);
		$values["updusr"]       = MySQL::SQLValue(session::get('userid'));
		$values["upddat"]       = 'CURRENT_TIMESTAMP';
		$wheres["id"]           = MySQL::SQLValue($this->_data['id']);
        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->InsertRow("sys_workflow", $values))
    		{
				//$db->Kill();
    			$this->log .= $db->Error();
    			$this->error = false;
    			$this->log .='</br>Enregistrement WorkFLow BD non réussie'; 
    			
    		}else{
    			$this->error == true;
    			$this->log = '</br>Enregistrement réussie: <b>';

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
     * [get_etat_wf used from modules to get etat_line via code]
     * @param  [type] $code_wf [description]
     * @return [type]          [description]
     */
    static public function get_etat_wf($code_wf)
    {
    	global $db;
    	$req_sql = "SELECT etat_line FROM sys_workflow WHERE code = '$code_wf'";
    	$etat_line = $db->QuerySingleValue($req_sql);
    	if(!$etat_line AND $etat_line == null)
    	{
    		exit('0#Error WF Code ');
    	}
    	return $etat_line;
    }
    /**
     * [get_multi_etat_wf description]
     * @param  [type] $array_code_wf [description]
     * @return [type]                [description]
     */
    static public function get_multi_etat_wf($array_code_wf, $array = false)
    {
    	global $db;
    	$count = count($array_code_wf);
        $i = 0;    	
    	$where_chaine = "( ";
    	foreach ($array_code_wf as $code){
    		$i++;
    		$v = $i == $count ? '' : ', ';
    		$where_chaine .= " '$code' $v ";
    	}
    	$where_chaine .= ")";
    	$req_sql = "SELECT etat_line FROM sys_workflow WHERE code IN $where_chaine ";
    	if(!$db->Query($req_sql))
    	{
    		exit('0#Error WF Code ');
    	}
    	$result = $db->RecordsArray();
    	
    	$count = count($result);
        $i = 0; 
        $return_array  = array(); 	
    	$where_chaine = "( ";
    	foreach ($result as $code => $value){
    		$i++;
    		$v = $i == $count ? '' : ', ';
    		$value = $value['etat_line'];
    		$where_chaine .= " '$value' $v ";
    		$return_array[] = $value;
    	}
    	$where_chaine .= ")";
    	if($array == true){
    		return $return_array;
    	}
    	return $where_chaine;
    }
    /**
    * [add_modul_workflow Add Task_action for WF (view line)]
    */
    public function add_modul_workflow()
    {
    	if(!$this->check_code_usage($this->_data['modul_id'], $this->_data['code']))
    	{
            return false;
    	}

    	$message = '<span class="label label-sm label-'.$this->_data['color'] .'">'.$this->_data['message_etat'].'</span>';
    	global $db;
    	$etat_line = $this->get_order_work_flow($this->_data['modul_id']);

		$values["modul_id"]     = MySQL::SQLValue($this->_data['modul_id']);
		$values["descrip"]      = MySQL::SQLValue($this->_data['descrip']);
		$values["etat_line"]    = MySQL::SQLValue($etat_line);
		$values["code"]         = MySQL::SQLValue($this->_data['code']);
		$values["color"]        = MySQL::SQLValue($this->_data['color']);
		$values["etat_desc"]    = MySQL::SQLValue($this->_data['message_etat']);
		$values["message_etat"] = MySQL::SQLValue($message);
		$values["creusr"]       = MySQL::SQLValue(session::get('userid'));
		$values["credat"]       = 'CURRENT_TIMESTAMP';
        // If we have an error
    	if($this->error == true)
    	{
    		if (!$result = $db->InsertRow("sys_workflow", $values))
    		{
				//$db->Kill();
    			$this->log .= $db->Error();
    			$this->error = false;
    			$this->log .='</br>Enregistrement WorkFLow BD non réussie';     			
    		}else{
    			$this->error == true;
    			$this->log = '</br>Enregistrement réussie: <b>';
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
     * [auto_add_modul_workflow_steps used for new modul]
     * @return [type] [description]
     */
    private function auto_add_modul_workflow_steps($id_modul, $code, $color, $message_etat, $descrip, $etat_line)
    {
    	
    	$message = '<span class="label label-sm label-'.$color .'">'.$message_etat.'</span>';
    	global $db;
    	$values["modul_id"]     = MySQL::SQLValue($id_modul);
		$values["descrip"]      = MySQL::SQLValue($descrip);
		$values["etat_line"]    = MySQL::SQLValue($etat_line);
		$values["code"]         = MySQL::SQLValue($code);
		$values["color"]        = MySQL::SQLValue($color);
		$values["etat_desc"]    = MySQL::SQLValue($message_etat);
		$values["message_etat"] = MySQL::SQLValue($message);
		$values["creusr"]       = MySQL::SQLValue(session::get('userid'));		
		if(!$result = $db->InsertRow("sys_workflow", $values))
		{
			$this->log .= $db->Error();    			
			$this->log .='</br>Enregistrement WorkFLow '.$code.' BD non réussie'; 
			return false;    			
		}else{
			if($etat_line == 0 )
			{
				$this->etat_default_wf = $result;
			}
			
			return true;    			
		}    	
    }

    public function delete_task_action()
    {
    	global $db;
    	$id_task_action = $this->id_task_action;
    	$this->get_task_action();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_task_action);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>Le id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('task_action',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('task_action',$where);
    		$this->error = false;
    		$this->log .='</br>Suppression non réussie';

    	}else{
    		//remove files
    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';
    	}
    	//check if last error is true then return true else rturn false.
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    /**
     * [get_table_fields generate lines for new files]
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public function get_table_fields($table)
    {
    	$table = $table;
    	$sql = "SHOW FULL COLUMNS FROM $table";
    	global $db;
    	$arr_fields = array();
    	if(!$db->Query($sql))
    	{
    		return false;
    	}else{
    		$arr_fields = $db->RecordsSimplArray();		

    	}
    	    	
    	$line_action = "'fields'         => Mreq::tp('fields') ,";
    	$line_action_check = "\t".'if($posted_data["fields"] == NULL){
                                    $empty_list .= "<li>title</li>";
                                    $checker = 1;
                              }';

        $line_form_add = '//title ==> '.PHP_EOL."\t".'$array_fields[]= array("required", "true", "Insérer title ...");'.PHP_EOL."\t".'$form->input("title", "fields", "text" ,"class", null, $array_fields, null, $readonly = null);';

        $line_form_edit = '//title ==> '.PHP_EOL."\t".'$array_fields[]= array("required", "true", "Insérer title ...");'.PHP_EOL.
        "\t".'$form->input("title", "fields", "text" ,"class", $info_%modul%->g("fields"), $array_fields , null, $readonly = null);';


    	$line_modul  = '$values["fields"]       = MySQL::SQLValue($this->_data["fields"]);';

    	$line_select = "\tarray(
                            'column' => '$table.fields',
                            'type'   => 'field_type',
                            'alias'  => 'fields',
                            'width'  => '15',
                            'header' => 'title',
                            'align'  => 'field_align'
                        ),";

        $line_profil = "\t".'<div class="profile-user-info">
 								<div class="profile-info-row">
 									<div class="profile-info-name">title</div>
 									<div class="profile-info-value">
 										<span><?php  $%modul%->s("fields")  ?></span>
 									</div>
 								</div>
 							</div>';


    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$field_type = null;
    			if(strpos($value['Type'], 'int'))
    			{
    				$field_type = 'int';
    				$align = 'R';
    			}elseif(strpos($value['Field'], 'date')){
    				$field_type = 'date';
    				$align = 'C';
    			}else{
    				$field_type = '';
    				$align = 'L';
    			}
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $line = str_replace('fields', $value['Field'], $line_select);
                $line = str_replace('title', $header, $line);
                $line = str_replace('field_type', $field_type, $line);
                $line = str_replace('field_align', $align, $line);
               	$this->lines_select .= $line.PHP_EOL;
    		}
    		
    	}

    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $action = str_replace('fields', $value['Field'], $line_action);
                $action_c = str_replace('fields', $value['Field'], $line_action_check);
                $action_c = str_replace('title', $header, $action_c);
                
               	$this->lines_action .= $action.PHP_EOL;
                $this->lines_action_check .= $action_c.PHP_EOL;
    		}
    		
    	}

    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $profil = str_replace('fields', $value['Field'], $line_profil);
                $profil = str_replace('title', $header, $profil);
                
               	$this->lines_profil .= $profil.PHP_EOL;
                
    		}
    		
    	}

    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$field_type = null;
    			if(strpos($value['Field'], 'int'))
    			{
    				$field_type = 'int';
    				$class = '4 is-number';
    			}elseif(strpos($value['Field'], 'date')){
    				$field_type = 'date';
    				$class = '4';
    			}else{
    				$field_type = '';
    				$class = '9';
    			}
    			$header = $value['Comment'] == null ? $value['Field'] : $value['Comment'];
                $form_add = str_replace('fields', $value['Field'], $line_form_add);
                $form_add = str_replace('title', $header, $form_add);
                $form_add = str_replace('class', $class, $form_add);
                $form_edit = str_replace('fields', $value['Field'], $line_form_edit);
                $form_edit = str_replace('title', $header, $form_edit);
                $form_edit = str_replace('class', $class, $form_edit);
               	$this->lines_form_add.= $form_add.PHP_EOL;
               	$this->lines_form_edit.= $form_edit.PHP_EOL;
                
    		}
    		
    	}
    	
    	foreach ($arr_fields as $key => $value) {
    		if(!in_array($value['Field'], array('id', 'etat', 'credat', 'upddat', 'creusr', 'updusr'))){
    			$this->lines_modul .= str_replace('fields', $value['Field'], $line_modul).PHP_EOL;
    		}
    	}
    	
    }
    /**
     * [show_work_flow description]
     * @param  [type] $task [description]
     * @return [type]       [description]
     */
    public function show_work_flow($task)
    {
    	
    	global $db;
    	$sql = "SELECT 
    	task_action.code,
    	task_action.id,
    	task_action.etat_line,
    	task_action.etat_desc,
    	task_action.message_class,
    	task_action.descrip,
    	task_action.notif,
    	modul.services,
    	task_action.service AS service_task_action
    	FROM
    	task_action,
    	task,
    	modul
    	WHERE task.app = '$task' 
    	AND task.id = task_action.appid 
    	AND task.modul = modul.id
    	AND task_action.type = 0 
    	ORDER BY  task_action.etat_line";

    	if(!$db->Query($sql)) $db->kill($db->Error());
    	if (!$db->RowCount())
    	{
            exit('0#Pas de work flow trouvé');
    	} 
    	$main_arr    = $db->RecordsArray();

    	$etat_arr    = array_column($main_arr, 'etat_line');
    	$etat__desc_arr    = array_column($main_arr, 'etat_desc');
    	$descrip_arr = array_column($main_arr, 'descrip');
    	$service_maine = $main_arr[0]['services'];
    	$service_maine = str_replace('[-','', $service_maine);
    	$service_maine = str_replace('-]','', $service_maine);
    	$service_maine = str_replace('-',',', $service_maine);
    	$arr_main_services = explode(',', $service_maine); 

    	foreach ($arr_main_services as $key => $service) {
	    //get service name

    		$sql_req = "SELECT service FROM services WHERE id = $service ";
    		$service_name = $db->QuerySingleValue0($sql_req);
    		$etat_a = array();
    		$html = '<div class="col-sm-12">
    		<div class="widget-box">
    		<div class="widget-header widget-header-flat widget-header-small">
    		<h5 class="widget-title">
    		<i class="ace-icon fa fa-setting"></i>
    		'.$service_name.' - '.$service.' -
    		</h5>
    		</div>

    		<div class="widget-body">
    		<div class="widget-main">';

    		$html .= '<ul class="steps">'; 
    		foreach ($etat_arr as $keye => $etat) {
    			if(!in_array($etat, $etat_a))
    			{
    				array_push($etat_a, $etat);
    				$html .= '<li data-step="1" class="">';
    				$html .= '<span class="step">'.$etat.'</span>';
    				$html .= '<div class="alert alert-'.$main_arr[$keye]['message_class'].'"><strong>'.$main_arr[$keye]['etat_desc'].'</strong></div>';

    				foreach ($main_arr as $key => $descrip) {
    					if($etat == $descrip['etat_line'] && strpos($descrip['service_task_action'],$service)){
    						$notif = $descrip['notif'] == 1 ? 'btn-danger' : 'btn-info';
    						$html .= '<span style="color:#FFFFFF; margin: 2px;" class="title '.$notif.' ">'.$descrip['descrip'].' - '.$descrip['id'].' - </span>';
    					}    


    				}

    				$html .= '</li>';
    			}

    		}

    		$html .= '</ul>';
    		$html .= '			</div><!-- /.widget-main -->
    		</div><!-- /.widget-body -->
    		</div><!-- /.widget-box -->
    		</div>
    		';
    		print $html;
    	}
    	return true;
    }
    /**
     * [get_statut_etat_line description]
     * @param  [type] $task      [description]
     * @param  [type] $etat_line [description]
     * @return [type]            [description]
     */    
    static public function get_statut_etat_line($task, $etat_line)
    {
    	global $db;
    	$sql = "SELECT 
    	sys_workflow.color, sys_workflow.etat_desc
    	FROM
    	sys_workflow,
    	sys_modules,
    	sys_task
    	WHERE sys_task.app = '$task' 
    	AND sys_task.modul = sys_modules.id 
    	AND sys_workflow.modul_id = sys_modules.id    	
    	AND sys_workflow.etat_line = $etat_line ";
        if(!$db->Query($sql))
        {
             $result = null;
        }else{
        	$arr_result = $db->RowArray();
        	$result = '<div class="alert alert-'.$arr_result['color'].'"><strong>'.$arr_result['etat_desc'].'</strong></div>';
        }
       
        return print($result);
    }

    /**
     * [get_modul_workflow description]
     * @param  [type] $step_id [description]
     * @return [type]           [description]
     */
    private function get_modul_workflow($step_id)
    {
    	global $db;
    	$sql = "SELECT sys_workflow.*    	
    	FROM sys_workflow WHERE sys_workflow.id = $step_id";
        if(!$db->Query($sql) OR !$db->RowCount())
        {
            $this->log .= '</>Erreur Get Step_workflow';
            return false;
        }else{
        	$this->modul_workflow = $db->RowArray();
        }       
        return true;
    }

    /**
     * [get_info_workflow description]
     * @return [type] [description]
     */
    public function get_info_workflow()
    {
    	global $db;
    	$id_workflow = $this->id_modul_workflow;
    	$sql = "SELECT sys_workflow.*    	
    	FROM sys_workflow WHERE sys_workflow.id = $id_workflow";
        if(!$db->Query($sql) OR !$db->RowCount())
        {
            $this->log .= '</>Erreur Get info_workflow';
            return false;
        }else{
        	$this->info_workflow = $db->RowArray();
        }       
        return true;
    }
    /**
     * [get_list_mere Used for edit task]
     * @param  [type] $task_id [description]
     * @return [type]          [description]
     */
    public function get_list_mere($task_id)
    {
     	global $db;
    	$id_workflow = $this->id_modul_workflow;
    	$sql = "SELECT sys_workflow.*    	
    	FROM sys_workflow WHERE sys_workflow.id = $id_workflow";
        if(!$db->Query($sql) OR !$db->RowCount())
        {
            $this->log .= '</>Erreur Get info_workflow';
            return false;
        }else{
        	$this->info_workflow = $db->RowArray();
        }       
        return true;
    } 

}

