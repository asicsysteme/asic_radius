<?php 
/**
* Password recovery
*/
class Mpswrecovery {
	
	private $_data;

	public function __construct(Array $properties=array()){
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

	static public function check_token($token){
		global $db;

		$chektoken = $db->QuerySingleValue("SELECT id 
			FROM forgot where CURRENT_TIMESTAMP < expir and
			token = '$token' and etat = 0");
		if($chektoken==NULL){
			return false;
		}else{
			return true;
		}

	}

	public function reset_passwors(){
		global $db;



		$token = $this->_data['token'];
		$pass  = $this->_data['pass'];

	     //befor check token again
		if($this->check_token($token)){
	     //Get user ID
			$user_id = $db->QuerySingleValue("SELECT user FROM forgot where
				token='$token' and etat = 0");

         //Format value for requet
			$value["pass"] = MySQL::SQLValue( md5($pass));
			$where["id"] =  MySQL::SQLValue($user_id);
        // Execute the update and show error case error
			if( !$result = $db->UpdateRows("users_sys", $value , $where)){
				return false;
			}else{
				//if good disable recovery link
				 $values["etat"] = MySQL::SQLValue( 1);
                 $wheres["token"] =  MySQL::SQLValue($token);
                // Execute the update
                 if(!$results = $db->UpdateRows("forgot", $values , $wheres)) $db->Kill($db->Error()); 
				 return true;
			}
		}else{

			return false; //token not good
		}

	}
}

/*function recovery_pass( $psw1, $token ) {
        //vèrifier si le lien valid
	global $db;
	//Get user ID
	$user_id = $db->QuerySingleValue("SELECT user FROM forgot where token='$token' and etat =0");


$value["pass"] = MySQL::SQLValue( md5($psw1));
$where["id"] =  MySQL::SQLValue($user_id);
// Execute the insert
$result = $db->UpdateRows("users_sys", $value , $where);
if (!$result) {
	// Show the error and kill the script
	$db->Kill('Error Update users_sys');
	return false;
} else {
    $values["etat"] = MySQL::SQLValue( 1);
    $wheres["token"] =  MySQL::SQLValue($token);
// Execute the insert
    $results = $db->UpdateRows("forgot", $values , $wheres); 
	      if (! $results) {
	// Show the error and kill the script
	          $db->Kill('Error Update forgot'.$db->Error());
	          return false;
            } else {    
	          return true;
            }   
	return true;
}	
	      
		  

}*/












?>

