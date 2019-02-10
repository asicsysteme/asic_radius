<?php 
/**
* Class Gestion services
*/


class Mservice {
	private $_data; //data receive from form
	var $table = "sys_services";//The main table of module
	var $last_id; //return last ID after insert command
	var $log = NULL; //Log of all opération.
	var $error = true; //Error bol changed when an error is occured
    var $id_service; // Service ID append when request
	var $token; //Service for recovery function
	var $service_info; //Array stock all serviceinfo


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

	public function Shw($key,$no_echo = "")
	{
		if($this->service_info[$key] != null)
		{
			if($no_echo != null)
			{
				return $this->service_info[$key];
			}

			echo $this->service_info[$key];
		}else{
			echo "";
		}
		
	}
	
		//Get all info service fro database for edit form

	public function get_service()
	{
		global $db;
        $table = $this->table; 
		$sql = "SELECT $table.* FROM 
		$table WHERE $table.id = ".$this->id_service;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if (!$db->RowCount()) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->service_info = $db->RowArray();
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
	 //Save new user after all check
	public function save_new_service(){

			

		global $db;
		$values["service"] = MySQL::SQLValue($this->_data['service']);
		$values["sign"]    = MySQL::SQLValue($this->_data['sign']);
        $values["creusr"]  = MySQL::SQLValue(session::get('userid'));	

        // If we have an error
		if($this->error == true){

			if (!$result = $db->InsertRow($this->table, $values)) {
				
				$this->log .= $db->Error();
				$this->error = false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['service'] . '  '.$this->_data['sign'] .' - '.$this->last_id.' -';
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

	public function update_service()
	{

		global $db;
	
		$values["service"] = MySQL::SQLValue($this->_data['service']);
		$values["sign"]    = MySQL::SQLValue($this->_data['sign']);
        $values["updusr"]  = MySQL::SQLValue(session::get('userid'));              
        $values["upddat"]  = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]      = $this->id_service;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
				
               	//$db->Kill();
				$this->log .= $db->Error()." ".$db->BuildSQLUpdate("services", $values, $wheres);
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{
			$this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['service'];

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
public function delete_service()
    {
    	global $db;
    	$id_service = $this->id_service;
    	$where['id'] = MySQL::SQLValue($id_service);
    	if(!$db->DeleteRows($this->table, $where))
    	{
    		$this->log .= $db->Error();
			$this->error = false;
			$this->log .='</br>Suppression non réussie';

    	}else{
    		$this->error = true;
    		$this->log .='</br>Suppression réussie';
    	}
    	//check if last error is true then return true else rturn false.
		if($this->error == false){
			return false;
		}else{
			return true;
		}
    }


public function valid_service()
	{
		global $db;
		$values['etat'] = ' ETAT + 1 ';
		$wheres['id']    = MySQL::SQLValue($this->id_service);

		if(!$result = $db->UpdateRows($this->table, $values, $wheres))
		{
			$this->log .= $db->Error();
			$this->error = false;
			$this->log .= 'Validation non réussie DB';

		}else{
			$this->log .= 'Validation réussie';
			$this->error = true;

		}
		if($this->error == false){
			return false;
		}else{
			return true;
		}
	}

	static public function check_need_sign($id_service)
	{
		global $db;
		$sql = "SELECT sign FROM sys_services WHERE id = $id_service ";
		if($db->QuerySingleValue0($sql) == 0){
			return false;

		}else{
			return true;
		}
	}
}