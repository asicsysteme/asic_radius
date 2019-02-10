<?php 
/**
* Class Gestion departements 1.0
*/


class Mdept {
	private $_data; //data receive from form

	var $table = 'ref_departement'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = ''; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
	var $id_departement; // departement ID append when request
	var $token; //user for recovery function
	var $departement_info; //Array stock all prminfo 
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
		//Get all info departement from database for edit form

		public function get_departement()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_departement;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->departement_info = $db->RowArray();
				$this->error = true;
			}	
		}
		//return Array departement_info
		if($this->error == false)
		{
			return false;
		}else{
			return true;
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



	 //Save new departement after all check
	public function save_new_departement(){

		//Before execute do the multiple check
		// check departement
		$this->Check_exist('departement', $this->_data['departement'], 'Departement', null);

		
		//Check non exist Region
		$this->check_non_exist('ref_region', 'id', $this->_data['id_region'], 'Région');

		//Format values for Insert query 
		global $db;
		$values["departement"] = MySQL::SQLValue($this->_data['departement']);
		$values["id_region"]   = MySQL::SQLValue($this->_data['id_region']);
		$values["creusr"]      = MySQL::SQLValue(session::get('username'));
	    $values["credat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow('ref_departement', $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['departement'] .' - '.$this->last_id.' -';


                    if(!Mlog::log_exec($this->table, $this->last_id, 'Insertion département', 'Insert'))
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

    //activer ou valider une departement
	public function valid_departement($etat = 0)
	{
		global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$values["etat"] 	    = MySQL::SQLValue($etat);
		$values["updusr"]       = MySQL::SQLValue(session::get('username'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_departement;
        // Execute the update and show error case error
		if( !$result = $db->UpdateRows($this->table, $values , $where))
		{
			$this->log .= '</br>Impossible de changer le statut!';
			$this->log .= '</br>'.$db->Error();
			$this->error = false;
		}else{
			$this->log .= '</br>Statut changé! ';

                    if(!Mlog::log_exec($this->table, $this->id_departement, 'Validation département', 'Validate'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }
			$this->error = true;

		} 
		if($this->error == false){
			return false;
		}else{
			return true;
		}

	}


	// afficher les infos d'une departement
	public function Shw($key,$no_echo = "")
	{
		if($this->departement_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->departement_info[$key];
			}

			echo $this->departement_info[$key];
		}else{
			echo "";
		}
		
	}
	//Edit departement after all check
	public function edit_departement(){

		//Before execute do the multiple check
		// check departement
		$this->Check_exist('departement', $this->_data['departement'], 'Departement', $this->id_departement);

		
		//Check non exist Region
		$this->check_non_exist('ref_region', 'id', $this->_data['id_region'], 'Région');


		//Get existing data for departement
		$this->get_departement();
		
		$this->last_id = $this->id_departement;


    	global $db;
		$values["departement"]  = MySQL::SQLValue($this->_data['departement']);
		$values["id_region"]    = MySQL::SQLValue($this->_data['id_region']);
	    $values["updusr"]       = MySQL::SQLValue(session::get('username'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]           = $this->id_departement;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("ref_departement", $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Modification BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Modification  réussie '. $this->_data['departement'] .' - '.$this->last_id.' -';


                    if(!Mlog::log_exec($this->table, $this->id_departement, 'Modification département', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }


                //Esspionage
                if(!$db->After_update($this->table, $this->id_departement, $values, $this->departement_info)){
                    $this->log .= '</br>Problème Esspionage';
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

	 public function delete_departement()
    {
    	global $db;
    	$id_departement = $this->id_departement;
    	$this->get_departement();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_departement);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
			$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('ref_departement',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('ref_departement',$where);
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

    	}else{
    		
    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';

                    if(!Mlog::log_exec($this->table, $this->id_departement, 'Suppression département', 'Delete'))
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