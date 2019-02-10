<?php 
/**
* Class Gestion Régions 1.0
*/


class Mregion {
	private $_data; //data receive from form
	var $table = 'ref_region'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_region; // Region ID append when request
	var $token; //user for recovery function
	var $region_info; //Array stock all region info
	var $app_action; //Array action for each 

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
		//Get all info region from database for edit form

	public function get_region()
	{
		global $db;

		$table = $this->table;

		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_region;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->region_info = $db->RowArray();
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




	 //Save new region after all check
	public function save_new_region(){

		
       	//Before execute do the multiple check
		// check Region
		$this->Check_exist('region', $this->_data['region'], 'Région', null);

		
		//Check non exist Pays
		$this->check_non_exist('ref_pays', 'id', $this->_data['id_pays'], 'Pays');
		

		global $db;
		$values["region"]     = MySQL::SQLValue($this->_data['region']);

		$values["id_pays"]    = MySQL::SQLValue($this->_data['id_pays']);
		$values["creusr"]     = MySQL::SQLValue(session::get('userid'));
	    $values["credat"]     = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow("ref_region", $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['region'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->last_id, 'Insertion région', 'Insert'))
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

    //activer ou valider une region
	public function valid_region($etat = 0)
	{
		global $db;

		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;

		$values["etat"]    = MySQL::SQLValue($etat);
		$values["updusr"] = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$wheres['id']     = $this->id_region;

		// Execute the update and show error case error
		if(!$result = $db->UpdateRows($this->table, $values, $wheres))
		{
			$this->log   .= '</br>Impossible de changer le statut!';
			$this->log   .= '</br>'.$db->Error();
			$this->error  = false;

		}else{
			$this->log   .= '</br>Statut changé! ';
			//$this->log   .= $this->table.' '.$this->id_region.' '.$etat;
			$this->error  = true;

                    if(!Mlog::log_exec($this->table, $this->id_region, 'Validation région', 'Validate'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }

		}
		if($this->error == false){
			return false;
		}else{
			return true;
		}


	}



	// afficher les infos d'une region
	public function Shw($key,$no_echo = "")
	{
		if($this->region_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->region_info[$key];
			}

			echo $this->region_info[$key];
		}else{
			echo "";
		}
		
	}
	//Edit region after all check
	public function edit_region(){

		

		//Get existing data for region
		$this->get_region();
		
		$this->last_id = $this->id_region;

		//Before execute do the multiple check
		// check Region
		$this->Check_exist('region', $this->_data['region'], 'Région', $this->id_region);

		
		//Check non exist Pays
		$this->check_non_exist('ref_pays', 'id', $this->_data['id_pays'], 'Pays');


    	global $db;
		$values["id_pays"]       = MySQL::SQLValue($this->_data['id_pays']);
		$values["region"]        = MySQL::SQLValue($this->_data['region']);
        $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]            = $this->id_region;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("ref_region", $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				//$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['region'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->id_region, 'Modifiation région', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }


                //Esspionage
                if(!$db->After_update($this->table, $this->id_region, $values, $this->region_info)){
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false; 
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

	 public function delete_region()
    {
    	global $db;
    	$id_region = $this->id_region;
    	$this->get_region();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_region);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
			$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('ref_region',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('ref_region',$where);
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

    	}else{
    		
    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';

                    if(!Mlog::log_exec($this->table, $this->id_region, 'Suppression région', 'Delete'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
    	}
    	//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
    }




}