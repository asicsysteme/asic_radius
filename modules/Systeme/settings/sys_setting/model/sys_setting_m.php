<?php 
/**
* Class Gestion Régions 1.0
*/


class Msetting {
	private $_data; //data receive from form

	var $table = 'sys_setting'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_setting; // Region ID append when request
	var $token; //user for recovery function
	var $setting_info; //Array stock all region info
	

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
		//Get all info  from database for edit form

	public function get_setting()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_setting;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->setting_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array region_info
		if($this->error == false)
		{
			return false;
		}else{
			return true ;
		}
		
	}
    static public function get_set($key, $key_arr = null)
    {
    	global $db;

		$table = 'sys_setting';

		$sql = "SELECT $table.value FROM 
		$table WHERE  $table.key = '$key'";
		
		$result = $db->QuerySingleValue($sql);
		
		if($result){
			if(json_decode($result, true) != null)
			{
				$result = json_decode($result, true);
               
				if($key_arr != null && is_array($result)){
					if(array_key_exists($key_arr, $result))
					{					
						$result = $result[$key_arr];
					}else{
						$result = null;

					}
				}
				
			}
			//case booleen value
			if($result == 'true'){
				$result = true;
			}elseif($result == 'false'){
				$result = false;
			}
			//$result = $result == 'true' ? true : $result;
			//$result = $result == 'false' ? false : $result;
			return $result;
		}else{
			return null;
		}
    }

    /**
     * [g description] Get filed from main array
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
	public function g($key)
    {
        if($this->setting_info[$key] != null)
        {
            return $this->setting_info[$key];
        }else{
            return null;
        }

    }
    /**
     * [g description] Print filed from main array
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function s($key)
    {
        if($this->setting_info[$key] != null)
        {
            return print ($this->setting_info[$key]);
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
    		$this->log .='</br>'.$message.' existe déjà';
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

    public function is_json($string)
    {    	
		if (strpos($string, '{') !== false) {
            if(json_decode($string, true) == null)
            {
            	$this->error = false;
            	$this->log .= "</br>La valeur n'est pas un tableau valide";                  
            }
        }
    }




	 //Save new region after all check
	public function save_new_sys_setting(){

		
       	//Before execute do the multiple check
		// check sys_setting
		$this->Check_exist('sys_setting', $this->_data['key'], 'Paramètre', null);

		
		//Check non exist Pays
		$this->check_non_exist('sys_modules', 'id', $this->_data['modul'], 'Modul');
		$this->is_json($this->_data['value']);

		global $db;
		$values["key"]     = MySQL::SQLValue($this->_data['key']);
		$values["value"]   = MySQL::SQLValue($this->_data['value']);
		$values["comment"] = MySQL::SQLValue($this->_data['comment']);
		$values["modul"]   = MySQL::SQLValue($this->_data['modul']);
		$values["creusr"]  = MySQL::SQLValue(session::get('userid'));
	    $values["credat"]  = MySQL::SQLValue(date("Y-m-d H:i:s"));


        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow($this->table, $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['key'] .' - '.$this->last_id.' -';
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

   	//Edit setting after all check
	public function edit_sys_setting(){

		

		//Get existing data for setting
		$this->get_setting();
		
		$this->last_id = $this->id_setting;

		//Before execute do the multiple check
		// check sys_setting
		$this->Check_exist('sys_setting', $this->_data['key'], 'Paramètre', $this->id_setting);

		
		//Check non exist Pays
		$this->check_non_exist('sys_modules', 'id', $this->_data['modul'], 'Modul');
		$this->is_json($this->_data['value']);


		global $db;
		$values["key"]       = MySQL::SQLValue($this->_data['key']);
		$values["value"]     = MySQL::SQLValue($this->_data['value']);
		$values["comment"]   = MySQL::SQLValue($this->_data['comment']);
		$values["modul"]     = MySQL::SQLValue($this->_data['modul']);
		$values["updusr"]    = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]    = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]        = $this->id_setting;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				//$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['key'] .' - '.$this->last_id.' -';
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

	public function delete_sys_setting()
    {
    	global $db;
    	$id_setting = $this->id_setting;
    	$this->get_setting();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_setting);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
			$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows($this->table,$where))
    	{

    		$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

    	}else{
    		
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




}