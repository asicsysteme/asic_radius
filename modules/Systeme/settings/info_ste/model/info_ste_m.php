<?php 
/**
* Info Ste 1.0
*/
class MSte_info 
{
	
	private $_data; //data receive from form

	var $table    = 'ste_info'; //Main table of module
	var $last_id  = null; //return last ID after insert command
	var $log      = null; //Log of all opération.
	var $error    = true; //Error bol changed when an error is occured
	var $id_ste   = null; // Ville ID append when request
	var $ste_info = null; //Array stock all prminfo 
	


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
	 * [get_ste_info Get all info for line]
	 * @return [type] [fill ste_info array]
	 */
	public function get_ste_info()
	{
		global $db;
		$table = $this->table;
		//Format Select commande
		$sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = ".$this->id_ste;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$this->ste_info = $db->RowArray();
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

	public function edit_info_ste()
	{

		global $db;
		$values["ste_name"]    = MySQL::SQLValue($this->_data['ste_name']);
		$values["ste_bp"]      = MySQL::SQLValue($this->_data['ste_bp']);
		$values["ste_adresse"] = MySQL::SQLValue($this->_data['ste_adresse']);
		$values["ste_tel"]     = MySQL::SQLValue($this->_data['ste_tel']);
		$values["ste_fax"]     = MySQL::SQLValue($this->_data['ste_fax']);
		$values["ste_email"]   = MySQL::SQLValue($this->_data['ste_email']);
		$values["ste_if"]      = MySQL::SQLValue($this->_data['ste_if']);
		$values["ste_rc"]      = MySQL::SQLValue($this->_data['ste_rc']);
		$values["ste_website"] = MySQL::SQLValue($this->_data['ste_website']);
		$values["updusr"]      = MySQL::SQLValue(session::get('userid'));
		$values["upddat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));
		$wheres["id"]          = $this->id_ste;
		

        // If we have an error
		if($this->error == true){

			if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
				//$db->Kill();
				$this->log .= $db->Error();
				$this->error == false;
				$this->log .='</br>Enregistrement BD non réussie'; 

			}else{

				//$this->last_id = $result;
				$this->log .='</br>Enregistrement  réussie '. $this->_data['ste_name'];
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
	 * [s Echo value from ste_info array]
	 * @param  [string] $key [key ste_info array]
	 * @return [Print]      [print into output]
	 */
	public function s($key)
	{
		if($this->ste_info[$key] != null)
		{
			echo $this->ste_info[$key];
		}else{
			echo "";
		}

	}
    /**
	 * [s Get value from ste_info array]
	 * @param  [string] $key [key ste_info array]
	 * @return [string]      [use into code]
	 */
    public function g($key)
    {
    	if($this->ste_info[$key] != null)
    	{
    		return $this->ste_info[$key];
    	}else{
    		return null;
    	}

    }

    public  function get_ste_info_report_head($id_ste)
    {
    	$this->id_ste = $id_ste;
    	$this->get_ste_info();
    	
    	

    	$head = '<div style="color:#4A5375;font-size: 9pt;font-family: sans-serif;"><address><br>'.$this->ste_info['ste_adresse'].'<br>'.$this->ste_info['ste_ville'].' '.$this->ste_info['ste_pays'].'<br><abbr title="Phone">Tél: </abbr>'.$this->ste_info['ste_tel'].'<br>BP: </abbr>'.$this->ste_info['ste_bp'].' N\'Djamena<br>Email: '.$this->ste_info['ste_email'].'<br>Site web: '.$this->ste_info['ste_website'].'</address></div>';
    	return $head;
    }

    public  function get_ste_info_report_footer($id_ste)
    {
    	$this->id_ste = $id_ste;
    	$this->get_ste_info();
    	
    	/*$footer = '<h1>'.$this->ste_info['ste_name'].'</h1><p>Télécommunications – Réseaux - Sécurité électronique - Prestation de Services<br/> Numéro d’Identification Fiscale : '.$this->ste_info['ste_if'].'<br/>Compte Orabank n°20403500201</p>';
*/
    	$footer = '</br><p>Télécommunications – Réseaux - Sécurité électronique - Prestation de Services<br/> Numéro d’Identification Fiscale : '.$this->ste_info['ste_if'].'<br/>Compte Orabank n°20403500201</p>';
    	
    	return $footer;
    }

}