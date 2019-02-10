<?php 
/**
* Class Gestion Villes 1.0
*/


class Mville {
	private $_data; //data receive from form

	var $table = 'ref_ville'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = ''; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
	var $id_ville; // Ville ID append when request
	var $token; //user for recovery function
	var $ville_info; //Array stock all prminfo 
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
		//Get all info ville from database for edit form

		public function get_ville()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_ville;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->ville_info = $db->RowArray();
				$this->error = true;
			}	
		}
		//return Array ville_info
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



	 //Save new ville after all check
	public function save_new_ville(){

		//Before execute do the multiple check
		// check Ville
		$this->Check_exist('ville', $this->_data['ville'], 'Ville', null);

		
		//Check non exist Region
		$this->check_non_exist('ref_departement', 'id', $this->_data['id_departement'], 'Département');

		//Format values for Insert query 
		global $db;
		$values["ville"]     = MySQL::SQLValue($this->_data['ville']);
		$values["id_departement"] = MySQL::SQLValue($this->_data['id_departement']);
		$values["latitude"]  = MySQL::SQLValue($this->_data['latitude']);
		$values["longitude"] = MySQL::SQLValue($this->_data['longitude']);
		$values["creusr"]    = MySQL::SQLValue(session::get('username'));
	    $values["credat"]    = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow('ref_ville', $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['ville'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->last_id, 'Insertion ville', 'Insert'))
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

    //activer ou valider une ville
	public function valid_ville($etat = 0)
	{
		global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$values["etat"] 			= MySQL::SQLValue($etat);
		$values["updusr"]       = MySQL::SQLValue(session::get('username'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_ville;
        // Execute the update and show error case error
		if( !$result = $db->UpdateRows($this->table, $values , $where))
		{
			$this->log .= '</br>Impossible de changer le statut!';
			$this->log .= '</br>'.$db->Error();
			$this->error = false;
		}else{
			$this->log .= '</br>Statut changé! ';
			$this->error = true;

                    if(!Mlog::log_exec($this->table, $this->id_ville, 'Validation ville', 'Validate'))
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


	// afficher les infos d'une ville
	public function Shw($key,$no_echo = "")
	{
		if($this->ville_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->ville_info[$key];
			}

			echo $this->ville_info[$key];
		}else{
			echo "";
		}
		
	}
	//Edit ville after all check
	public function edit_ville(){

		//Before execute do the multiple check
		// check Ville
		$this->Check_exist('ville', $this->_data['ville'], 'Ville', $this->id_ville);

		
		//Check non exist Region
		$this->check_non_exist('ref_departement', 'id', $this->_data['id_departement'], 'Département');


		//Get existing data for ville
		$this->get_ville();
		
		$this->last_id = $this->id_ville;


    	global $db;
		$values["ville"]        = MySQL::SQLValue($this->_data['ville']);
		$values["id_departement"]    = MySQL::SQLValue($this->_data['id_departement']);
		$values["latitude"]     = MySQL::SQLValue($this->_data['latitude']);
		$values["longitude"]    = MySQL::SQLValue($this->_data['longitude']);
	    $values["updusr"]       = MySQL::SQLValue(session::get('username'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]           = $this->id_ville;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows("ref_ville", $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Modification BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Modification  réussie '. $this->_data['ville'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->id_ville, 'Modification ville', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }

                //Esspionage
                if(!$db->After_update($this->table, $this->id_ville, $values, $this->ville_info)){
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

	 public function delete_ville()
    {
    	global $db;
    	$id_ville = $this->id_ville;
    	$this->get_ville();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_ville);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
			$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('ref_ville',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('ref_ville',$where);
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

    	}else{
    		
    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';

                    if(!Mlog::log_exec($this->table, $this->id_ville, 'Suppression ville', 'Delete'))
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