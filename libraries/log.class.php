<?php
/**
 * Class Loggining systeme 1.0
 */
class Mlog
{

	public function __construct(){
	}

	static public function log_exec($table, $idm, $message, $type)
	{
		global $db;
		
		$values["message"]   = MySQL::SQLValue($message);
		$values["type_log"]  = MySQL::SQLValue($type);
		$values["table_use"] = MySQL::SQLValue($table);
		$values["idm"]       = MySQL::SQLValue($idm);
		$values["sesid"]     = MySQL::SQLValue(session::get('ssid'));
		$values["user_exec"] = MySQL::SQLValue(session::get('username'));
		//If no error on Insert commande
		if (!$result = $db->InsertRow("sys_log", $values))
		{
			return false;  			 
		}else{
			return true;
		}
		
	}

	static public function get_log($table_use, $idm)
	{
		global $db;
        $table = 'sys_log';
        
        //$add_set = array('return' => '<a href="#" class="this_modal" rel="%crypt%"> <i class="ace-icon fa fa-print"></i></a>', 'data' => 'idc', 'clair' => true );
        $req_sql = "SELECT id, message, user_exec, DATE_FORMAT(datlog,'%d-%m-%Y %H:%i:%s') FROM $table WHERE idm = $idm AND table_use = '$table_use' ";

        if(!$db->Query($req_sql))
        {           
            var_dump($db->Error());
            return false;           
        }
        if(!$db->RowCount())
        {
            $output = '<div class="alert alert-danger">Pas des logs enregistrés pour cet ligne</div>'; 
            return $output;
        }      
        $headers = array(
			'ID'        => '5[#]center',
			'Message'   => '30[#]',
			'User'      => '10[#]',
			'Date time' => '10[#]alignRight',
        );                  
        $tableau = $db->GetMTable($headers, $add_set = null);
        return $tableau;
	}

	

}