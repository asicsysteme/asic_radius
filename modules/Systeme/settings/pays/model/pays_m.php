<?php 

//SYS MRN ERP
// Modul: pays => Model

/**
* Class Gestion Pays 1.0
*/


class Mpays {
	private $_data; //data receive from form
	var $table = 'ref_pays'; //Main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_pays; // Ville ID append when request
	var $token; //user for recovery function
	var $pays_info; //Array stock all ville info


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
		//Get all info pays from database for edit form

	public function get_pays()
	{
		global $db;

		$sql = "SELECT  p.*  FROM  ref_pays p WHERE  p.id = ".$this->id_pays;

		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();//." ". $db->BuildSQLUpdate("metier_ville",$values,$wheres);
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->pays_info = $db->RowArray();
				$this->error = true;
			}
			
			
		}
		//return Array pays_info
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


	 //Save new pays after all check
    public function save_new_pays(){


        //Before execute do the multiple check
		// check Pays
    	$this->Check_exist('pays', $this->_data['pays'], 'Pays', null);

    	global $db;
    	$values["pays"]         = MySQL::SQLValue($this->_data['pays']);
    	$values["nationalite"]  = MySQL::SQLValue($this->_data['nationalite']);
    	$values["alpha"]        = MySQL::SQLValue($this->_data['alpha']);
    	$values["creusr"]       = MySQL::SQLValue(session::get('userid'));
    	$values["credat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->InsertRow("ref_pays", $values)) {

    			$this->log .= $db->Error();
    			$this->error = false;
    			$this->log .='</br>Enregistrement BD non réussie'; 

    		}else{

    			$this->last_id = $result;
    			$this->log .='</br>Enregistrement  réussie '. $this->_data['pays'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->last_id, 'Insertion pays', 'Insert'))
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

    //activer ou desactiver un pays
    public function valid_pays($etat = 0)
    {
    	
    	global $db;
		//Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
		$etat = $etat == 0 ? 1 : 0;
		//Format value for requet
		$values["etat"] 		= MySQL::SQLValue($etat);
		$values["updusr"]       = MySQL::SQLValue(session::get('userid'));
	    $values["upddat"]       = MySQL::SQLValue(date("Y-m-d H:i:s"));

		$where["id"]   			= $this->id_pays;

        // Execute the update and show error case error
		if( !$result = $db->UpdateRows($this->table, $values , $where))
		{
			$this->log .= '</br>Impossible de changer le statut!';
			$this->log .= '</br>'.$db->Error();
			$this->error = false;
		}else{
			$this->log .= '</br>Statut changé! ';
			$this->error = true;

                    if(!Mlog::log_exec($this->table, $this->id_pays, 'Validation pays', 'Validate'))
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



	// afficher les infos d'un pays
    public function Shw($key,$no_echo = "")
    {
    	if($this->pays_info[$key] != null)
    	{
    		if($no_echo != null)
    		{
    			return $this->pays_info[$key];
    		}

    		echo $this->pays_info[$key];
    	}else{
    		echo "";
    	}

    }
	//Edit pays after all check
    public function edit_pays(){

		//Get existing data for pays
    	$this->get_pays();

    	$this->last_id = $this->id_pays;

		// check Pays
    	$this->Check_exist('pays', $this->_data['pays'], 'Pays', $this->id_pays);

    	global $db;
    	$values["pays"]          = MySQL::SQLValue($this->_data['pays']);
    	$values["nationalite"]   = MySQL::SQLValue($this->_data['nationalite']);
    	$values["alpha"]         = MySQL::SQLValue($this->_data['alpha']);
    	$values["updusr"]        = MySQL::SQLValue(session::get('userid'));
    	$values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));
    	$wheres["id"]            = $this->id_pays;


        // If we have an error
    	if($this->error == true){

    		if (!$result = $db->UpdateRows("ref_pays", $values, $wheres)) {
				//$db->Kill();
    			$this->log .= $db->Error();
    			$this->error == false;
    			$this->log .='</br>Enregistrement BD non réussie'; 

    		}else{

				//$this->last_id = $result;
    			$this->log .='</br>Enregistrement  réussie '. $this->_data['pays'] .' - '.$this->last_id.' -';

                    if(!Mlog::log_exec($this->table, $this->id_pays, 'Modification pays', 'Update'))
                    {
                        $this->log .= '</br>Un problème de log ';
                    }

                //Esspionage
                if(!$db->After_update($this->table, $this->id_pays, $values, $this->pays_info)){
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

    public function delete_pays()
    {
    	global $db;
    	$id_pays = $this->id_pays;
    	$this->get_pays();
    	//Format where clause
    	$where['id'] = MySQL::SQLValue($id_pays);
    	//check if id on where clause isset
    	if($where['id'] == null)
    	{
    		$this->error = false;
    		$this->log .='</br>L\' id est vide';
    	}
    	//execute Delete Query
    	if(!$db->DeleteRows('ref_pays',$where))
    	{

    		$this->log .= $db->Error().'  '.$db->BuildSQLDelete('ref_pays',$where);
    		$this->error = false;
    		$this->log .='</br>Suppression non réussie';

    	}else{
    		
    		$this->error = true;
    		$this->log .='</br>Suppression réussie ';

                    if(!Mlog::log_exec($this->table, $this->id_pays, 'Suppression pays', 'Delete'))
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