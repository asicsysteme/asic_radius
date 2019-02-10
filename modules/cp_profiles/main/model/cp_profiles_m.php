<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//ARCEP PORTAIL CAPTIF MANAGER
// Modul: cp_profiles
//Created : 02-02-2019
//Model
/**
* M%modul% 
* Version 1.0
* 
*/

class Mcp_profiles 
{
	
    private $_data;                      //data receive from form
    var $table            = 'cp_profiles';   //Main table of module
    var $last_id          = null;        //return last ID after insert command
    var $log              = null;        //Log of all opération.
    var $error            = true;        //Error bol changed when an error is occured
    var $id_cp_profiles   = null;        // cp_profiles ID append when request
    var $token            = null;        //user for recovery function
    var $cp_profiles_info = array();     //Array stock all cp_profiles info
	

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
     * Function Get modul info 
     * fit $this->cp_profiles_info (Array) 
     * @return true
     */ 
	public function get_cp_profiles()
	{
		global $db;
		$table = $this->table;
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_cp_profiles;
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
				$this->cp_profiles_info = $db->RowArray();
				$this->error = true;
			}	
		}
		//return Array user_info
		if($this->error == false)
		{
			return false;
		}else{
			return true ;
		}
		
	}
	/**
	 * Save new row to main table
	 * @return [bol] [bol value send to controller]
	 */
	public function save_new_cp_profiles()
    {
        $this->check_exist('profile', $this->_data["profile"], 'Profile', $edit = null);
        //$this->check_non_exist($table, $column, $value, $message)
        // If we have an error
		if($this->error == true)
        {
			global $db;
		    $quota = $this->formatBytes($this->_data["quota"]);
            //ADD field row here
            $values["profile"]       = MySQL::SQLValue($this->_data["profile"]);
            $values["quota"]         = MySQL::SQLValue($this->_data["quota"]);
            $values["date_expir"]    = MySQL::SQLValue($this->_data["date_expir"]);
            $values["quota_s"]       = MySQL::SQLValue($quota);
		
		    $values["creusr"]       = MySQL::SQLValue(session::get('userid'));

			if (!$result = $db->InsertRow($this->table, $values)) 
            {				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 
			}else{
				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['profile'] .' - '.$this->last_id.' -';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Création Profile '.$this->_data['profile'], 'Insert'))
                {
                  $this->log .= '</br>Un problème de log ';
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
	 * Edit selected Row
	 * @return Bol [send to controller]
	 */
	public function edit_cp_profiles()
    {
        $this->check_exist('profile', $this->_data["profile"], 'Profile', $edit = $this->id_cp_profiles);
        //$this->check_non_exist($table, $column, $value, $message)
		//Get existing data for row
		$this->get_cp_profiles();
		$this->last_id = $this->id_cp_profiles;
        // If we have an error
		if($this->error == true)
        {
			global $db;
            $quota = $this->formatBytes($this->_data["quota"]);
		    //ADD field row here
		    $values["profile"]       = MySQL::SQLValue($this->_data["profile"]);
            $values["quota"]         = MySQL::SQLValue($this->_data["quota"]);
            $values["date_expir"]    = MySQL::SQLValue($this->_data["date_expir"]);
            $values["quota_s"]       = MySQL::SQLValue($quota);

		    $values["updusr"]         = MySQL::SQLValue(session::get('userid'));
		    $values["upddat"]         = 'CURRENT_TIMESTAMP';
		    $wheres["id"]             = $this->id_cp_profiles;

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) 
            {				
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Modification BD non réussie'; 
			}else{
				$this->last_id = $this->id_cp_profiles;
				$this->log .='</br>Modification  réussie '. $this->_data['profile'] .' - '.$this->last_id.' -';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Modification Profile '.$this->_data['profile'], 'Update'))
                {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if(!$db->After_update($this->table, $this->id_cp_profiles, $values, $this->cp_profiles_info)){
                    $this->log .= '</br>Problème track Update';
                    $this->error = false;	
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
     * [formatBytes description]
     * @param  [type]  $size      [description]
     * @param  integer $precision [description]
     * @return [type]             [description]
     */
    private function formatBytes($size, $precision = 0)
    {
        $base = log($size * 8, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');   

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }	

    /**
     * Valide cp_profiles
     * @return bol send to controller
     */
    public function valid_cp_profiles()
    {    	
    	global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
    	//Format etat from WF tables
        $old_etat_line = Mmodul::get_etat_wf('creat_cp_profiles');
        $new_etat_line = Mmodul::get_etat_wf('valid_cp_profiles');
        //if etat not correct then return error
        if($old_etat_line != $this->g('etat'))
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>Etat not correct';
            return false;
        }
    	$values["etat"]        = MySQL::SQLValue($new_etat_line);
    	$values["updusr"]      = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]      = 'CURRENT_TIMESTAMP';
    	$wheres['id']          = $this->id_cp_profiles;

		// Execute the update and show error case error
    	if(!$result = $db->UpdateRows($this->table, $values, $wheres))
    	{
    		$this->log   .= '</br>Impossible de changer le statut!';
    		$this->log   .= '</br>'.$db->Error();
    		$this->error  = false;

    	}else{
    		$this->log   .= '</br>Modification réussie! ';
    		$this->error  = true;
    		if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  cp_profiles', 'Update'))
    		{
    			$this->log .= '</br>Un problème de log ';
    			$this->error = false;
    		}
               //Esspionage
    		if(!$db->After_update($this->table, $this->id_cp_profiles, $values, $this->cp_profiles_info)){
    			$this->log .= '</br>Problème track Update';
    			$this->error = false;	
    		}
    	}
    	if($this->error == false){
    		return false;
    	}else{
    		return true;
    	}
    }
    /**
     * Valide cp_profiles
     * @return bol send to controller
     */
    public function desactiv_cp_profiles()
    {       
        global $db;
        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        //Format etat from WF tables
        $old_etat_line = Mmodul::get_etat_wf('valid_cp_profiles');
        $new_etat_line = Mmodul::get_etat_wf('creat_cp_profiles');
        //if etat not correct then return error
        if($old_etat_line != $this->g('etat'))
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>Etat not correct';
            return false;
        }
        $values["etat"]        = MySQL::SQLValue($new_etat_line);
        $values["updusr"]      = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]      = 'CURRENT_TIMESTAMP';
        $wheres['id']          = $this->id_cp_profiles;

        // Execute the update and show error case error
        if(!$result = $db->UpdateRows($this->table, $values, $wheres))
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>'.$db->Error();
            $this->error  = false;

        }else{
            $this->log   .= '</br>Modification réussie! ';
            $this->error  = true;
            if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  cp_profiles', 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                $this->error = false;
            }
               //Esspionage
            if(!$db->After_update($this->table, $this->id_cp_profiles, $values, $this->cp_profiles_info)){
                $this->log .= '</br>Problème track Update';
                $this->error = false;   
            }
        }
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }
    public function archive_cp_profiles()
    {       
        global $db;
        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        //Format etat from WF tables
        $old_etat_line = Mmodul::get_etat_wf('valid_cp_profiles');
        $new_etat_line = Mmodul::get_etat_wf('archive_cp_profiles');
        //if etat not correct then return error
        if($old_etat_line != $this->g('etat'))
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>Etat not correct';
            return false;
        }
        $values["etat"]        = MySQL::SQLValue($new_etat_line);
        $values["updusr"]      = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]      = 'CURRENT_TIMESTAMP';
        $wheres['id']          = $this->id_cp_profiles;

        // Execute the update and show error case error
        if(!$result = $db->UpdateRows($this->table, $values, $wheres))
        {
            $this->log   .= '</br>Impossible de changer le statut!';
            $this->log   .= '</br>'.$db->Error();
            $this->error  = false;

        }else{
            $this->log   .= '</br>Modification réussie! ';
            $this->error  = true;
            if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  cp_profiles', 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                $this->error = false;
            }
               //Esspionage
            if(!$db->After_update($this->table, $this->id_cp_profiles, $values, $this->cp_profiles_info)){
                $this->log .= '</br>Problème track Update';
                $this->error = false;   
            }
        }
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }
	/**
	 *  [check_non_exist Check if one entrie not exist on referential table]
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
    	$sql_edit = $edit == null ? null: " AND  <> $edit";
    	$result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
    		WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

    	if ($result != "0") {
    		$this->error = false;
    		$this->log .='</br>'.$message.' existe déjà';
    	}
    }
    /**
     * Delete selectd Row
     * @return bol [Send to controller]
     */
    public function delete_cp_profiles()
    {
    	global $db;
    	$id_cp_profiles = $this->id_cp_profiles;
    	$this->get_cp_profiles();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_cp_profiles);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows($this->table, $where))
    	{    		
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

    /**
     * [s Print value of entry]
     * @param  [key array] $key [description]
     * @return [print string]      [description]
     */
    public function s($key)
    {
        if($this->cp_profiles_info[$key] != null)
        {
            $result = $this->cp_profiles_info[$key];
            if(is_numeric($result)){
                $result = number_format($result,0,""," ");
            }elseif(DateTime::createFromFormat('Y-m-d', $result) !== FALSE){
                $result = date('d-m-Y',strtotime($result));
            }
            echo $result;
        }else{
            echo "";
        }
    }
    /**
     * [g Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function g($key)
    {
        if($this->cp_profiles_info[$key] != null)
        {
            return $this->cp_profiles_info[$key];
        }else{
            return null;
        }
    }
    /**
     * [save_file For save anattached file for entrie ]
     * @param  [string] $item  [input_name of attached file we add _id]
     * @param  [string] $titre [Title stored for file on Archive DB]
     * @param  [string] $type  [Type of file (Document, PDF, Image)]
     * @return [Setting]       [Set $this->error and $this->log]
     */
    private function save_file($item, $titre, $type, $table = null)
    {
        //Format all parameteres
        $temp_file     = $this->_data[$item.'_id'];
        //If nofile uploaded return kill function
        if($temp_file == Null OR is_numeric($temp_file)){
            return true;
        }
        $new_name_file = $item.'_'.$this->last_id;
        $folder        = MPATH_UPLOAD.'cp_profiles'.SLASH.$this->last_id;
        $id_line       = $this->last_id;
        $title         = $titre;
        $table         = $table == null ? $this->table : $table;
        $column        = $item;
        $type          = $type;
        //Call save_file_upload from initial class
        if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, $this->table, $table, $column, $type, $edit = null))
        {
            $this->error = false;
            $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
        }
    }

}