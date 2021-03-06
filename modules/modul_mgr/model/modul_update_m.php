<?php
/**
* 
*/
class Update_modul extends Mmodul
{
	

	public function __construct(){
		parent::__construct();
	}

	private function Store_existing_rules($modul_id)
	{
		global $db;
		$sql_isert = "INSERT INTO rules_action_temp (idf, service, userid, descrip)  (
                    SELECT
                    rules_action.idf, rules_action.`service`, rules_action.`userid`, rules_action.`descrip`
                    FROM
                        `rules_action`
                        INNER JOIN `task` 
                            ON (`rules_action`.`appid` = `task`.`id`)
                        INNER JOIN `modul` 
                            ON (`task`.`modul` = `modul`.`id`)
                    WHERE modul.id = $modul_id)";
        if(!$db->Query($sql_isert))
		{
			$this->error = false;
			$this->log  .= '<li>  Erreur Store existing rules <br/> No have rules on Rules table yet';
			$this->log  .= $db->Error();
		}else{
			$this->error = true;
			$this->log .= '<li>  Store Existing Rules';
		}
	}

	private function Clear_temp_rules()
	{
		global $db;
		$sql_delete = "DELETE FROM rules_action_temp ";
        if(!$db->Query($sql_delete))
		{
			$this->error = false;
			$this->log  .= '<li>  Erreur Clear rules $idf ';
			$this->log  .= $db->Error();
		}
	}

	Private function Check_exist_file($modul_name)
	{
		$terminison_file = '_script_export.php';

		
		$file     = MPATH_EXPORT_MOD.$modul_name.$terminison_file;
		$file_maj = MPATH_EXPORT_MOD.'maj_'.$modul_name.$terminison_file;
		
		if(!file_exists($file))
		{
			$this->log .= '<li>  '.$modul_name.$terminison_file .' N\'existe pas '.'</li>';
			$this->error = false;
		}else{
			
			$this->error = true;
			$this->log .= '<li>'.$modul_name.$terminison_file.'</li>';
		}
	}




	Public function Update_module($id_modul, $name_modul)
	{
		if($this->error == true)
		{
			$this->Check_exist_file($name_modul);
		}
		
		
		$this->modul_id = $id_modul;
		$this->id_modul = $id_modul;
		if($this->error == true)
		{
			$this->Clear_temp_rules();
		}
		if($this->error == true)
		{
			$this->Store_existing_rules($id_modul);
		}
		if($this->error == true)
		{
			$this->import_singl_modul($name_modul);
		}
		if($this->error == true)
		{
			$this->Import_rules_stored();
		}
		if($this->error == true)
		{
			$this->Clear_temp_rules();
		}
		
		
		
		
		
		//return Bol reading $this->error
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
	}



	Private function Erase_modul($id_modul)
	{
		global $db;
		$sql_delete = "DELETE FROM modul WHERE id = $id_modul";
        if(!$db->Query($sql_delete))
		{
			$this->error = false;
			$this->log  .= '<li> Erreur Erase Module $id_modul ';
			$this->log  .= $db->Error();
		}else{
			$this->error = true;
			$this->log  .= '<li> Erase Module $id_modul ';
		}
	}




	Private function import_singl_modul($modul_name)
	{
		

		
		$terminison_file = '_script_export.php';

		
		$file = MPATH_EXPORT_MOD.$modul_name.$terminison_file;
		
		if(file_exists($file))
		{
			$this->Erase_modul($this->modul_id);

			$this->error = true;
			include($file);
			$this->log .= '<li>'.$modul_name.$terminison_file.'</li>';
		}
		
		
	}



	Private function Import_rules_stored()
	{
		global $db;
		$creusr = MySQL::SQLValue(session::get('userid'));
		$sql_insert_rules = "INSERT INTO rules_action (appid, idf, descrip, action_id, type, 
		                    userid, service, creusr) 
		                    SELECT   ta.appid,  ta.idf,  ta.descrip,  ta.id,  
		                    ta.type,  rat.userid,  rat.service , $creusr
                            FROM
                              task_action ta, rules_action_temp rat 
                            WHERE  ta.idf = rat.`idf` ";
        if(!$db->Query($sql_insert_rules))
		{
			$this->error = false;
			$this->log  .= '<li>   Erreur Bkp existing rules '.$db->Error();
			$this->log  .= $db->Error();
		}else{
			$this->error = true;
			$this->log = '<li>   Bkp Existing Rules';
		}
	}
	

}

?>