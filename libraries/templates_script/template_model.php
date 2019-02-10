/**
* M%modul% 
* Version 1.0
* 
*/

class M%model% 
{
	
	private $_data;                      //data receive from form
	var $table            = '%table%';   //Main table of module
	var $last_id          = null;        //return last ID after insert command
	var $log              = null;        //Log of all opération.
	var $error            = true;        //Error bol changed when an error is occured
    var $id_%model%       = null;        // %model% ID append when request
	var $token            = null;        //user for recovery function
	var $%model%_info     = array();     //Array stock all %model% info
	

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
     * fit $this->%model%_info (Array) 
     * @return true
     */ 
	public function get_%model%()
	{
		global $db;
		$table = $this->table;
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_%model%;
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
				$this->%model%_info = $db->RowArray();
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
	public function save_new_%model%()
    {
        //$this->check_exist($column, $value, $message, $edit = null);
        //$this->check_non_exist($table, $column, $value, $message)
        // If we have an error
		if($this->error == true)
        {
			global $db;
		    //Add all fields for the table
		    %lines_modul%		
		    $values["creusr"]       = MySQL::SQLValue(session::get('userid'));

			if (!$result = $db->InsertRow($this->table, $values)) 
            {				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 
			}else{
				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['%model%'] .' - '.$this->last_id.' -';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Création %model%', 'Insert'))
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
	public function edit_%model%()
    {
        //$this->check_exist($column, $value, $message, $edit = 1);
        //$this->check_non_exist($table, $column, $value, $message)
		//Get existing data for row
		$this->get_%model%();
		$this->last_id = $this->id_%model%;
        // If we have an error
		if($this->error == true)
        {
			global $db;
		    //ADD field row here
		    %lines_modul%
		    $values["updusr"]         = MySQL::SQLValue(session::get('userid'));
		    $values["upddat"]         = 'CURRENT_TIMESTAMP';
		    $wheres["id"]             = $this->id_%model%;

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) 
            {				
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Modification BD non réussie'; 
			}else{
				$this->last_id = $this->id_%model%;
				$this->log .='</br>Modification  réussie '. $this->_data['%model%'] .' - '.$this->last_id.' -';
				if(!Mlog::log_exec($this->table, $this->last_id, 'Modification %model%', 'Update'))
                {
                    $this->log .= '</br>Un problème de log ';
                    $this->error = false;
                }
                //Esspionage
                if(!$db->After_update($this->table, $this->id_%model%, $values, $this->%model%_info)){
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
     * Valide %model%
     * @return bol send to controller
     */
    public function valid_%model%()
    {    	
    	global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
    	//Format etat from WF tables
        $old_etat_line = Mmodul::get_etat_wf('creat_%model%');
        $new_etat_line = Mmodul::get_etat_wf('valid_%model%');
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
    	$wheres['id']          = $this->id_%model%;

		// Execute the update and show error case error
    	if(!$result = $db->UpdateRows($this->table, $values, $wheres))
    	{
    		$this->log   .= '</br>Impossible de changer le statut!';
    		$this->log   .= '</br>'.$db->Error();
    		$this->error  = false;

    	}else{
    		$this->log   .= '</br>Modification réussie! ';
    		$this->error  = true;
    		if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  %model%', 'Update'))
    		{
    			$this->log .= '</br>Un problème de log ';
    			$this->error = false;
    		}
               //Esspionage
    		if(!$db->After_update($this->table, $this->id_%model%, $values, $this->%model%_info)){
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
    public function delete_%model%()
    {
    	global $db;
    	$id_%model% = $this->id_%model%;
    	$this->get_%model%();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_%model%);
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
        if($this->%model%_info[$key] != null)
        {
            $result = $this->%model%_info[$key];
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
        if($this->%model%_info[$key] != null)
        {
            return $this->%model%_info[$key];
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
        $folder        = MPATH_UPLOAD.'%model%'.SLASH.$this->last_id;
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