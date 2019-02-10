<?php
/**
* 
*/
class Export_modul extends Mmodul
{
	
	var $list_task_of_modul     = null;
	var $list_action_of_task    = null;
	var $list_workflow_of_modul = null;
	var $list_creat_tables      = null;


	public function __construct(){
		parent::__construct();
	}


	public function Export_mod($id_modul, $bkp = null)
	{
		global $db;

		//Get and Insert Modul
		$this->id_modul = $id_modul;
		parent::get_modul();
		$modul         = MySQL::SQLValue($this->modul_info['modul']);
		$description   = MySQL::SQLValue($this->modul_info['description']);
		$rep_modul     = MySQL::SQLValue($this->modul_info['rep_modul']);
		$tables        = MySQL::SQLValue($this->modul_info['tables']);
		$app_modul     = MySQL::SQLValue($this->modul_info['app_modul']);
		$modul_setting = MySQL::SQLValue($this->modul_info['modul_setting']);
        $is_setting    = MySQL::SQLValue($this->modul_info['is_setting']);
        $etat          = MySQL::SQLValue($this->modul_info['etat']);
        $services      = MySQL::SQLValue($this->modul_info['services']);



		$content   = '<?php ' . PHP_EOL;
		$content  .= '//Export Module '.$modul.' Date: '.date('d-m-Y H:i:s') .'By'. session::get('username'). PHP_EOL;
		$content  .= 'global $db;' . PHP_EOL;
		$content  .= 'if(!$result_insert_modul = $db->Query("insert into sys_modules (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('.$modul.', '.$description.','.$rep_modul.','.$tables.','.$app_modul.','.$modul_setting.','.$is_setting.', '.$etat.', '.$services.')")){$this->error = false; $this->log .= "<li> Error Import Modul '. $modul.' </li>";}'. PHP_EOL; 

		/*//Get Tables Create scripts
		if($this->Creat_tables($this->modul_info['tables']))
		{
			$content .= $this->list_creat_tables;
		}*/

		//GET workflow of modul
		if($this->get_workflow_modul()){
			$content .= $this->list_workflow_of_modul;
		}

		//Get Task for this modul 
		if($this->get_tasks_modul()){
			$content .= $this->list_task_of_modul;
		}
		//creat file into Modul folder
		$old = $bkp == null ? null : 'bkp_'.date('d_m_Y_H_i_s').'_';
		$main_folder  = $bkp == null ? MPATH_EXPORT_MOD : MPATH_CACHE.'bkp_modul_updated/';
		
		$file = $main_folder.$this->modul_info['modul'].'_script_'.$old.'export.php';
		if(file_exists($file)){
			unlink($file);
		}

		if(!file_put_contents($file, $content, FILE_APPEND | LOCK_EX))
		{
			return false;
		}
		return true;
	}

	Private function Creat_tables($tables)
	{
		global $db;

		$content = '';
		$tables_array = explode(", ", $tables);
		foreach ($tables_array as $row) {
			$db->open();


			$sql = 'SHOW CREATE TABLE '.$row;
			$result = $db->Query($sql);
			if($db->RowCount() > 0)
			{
				$creat_script = $db->RowArray();
				$creat_script = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS ', $creat_script);
			    $content  .= '//Creat if not exist table '.$row.' Date: '.date('d-m-Y') . PHP_EOL;
			    $content  .= '//'.$sql. PHP_EOL;
			    $content  .= 'if(!$result_creat_table_'.$row.' = $db->Query("'.$creat_script[1].'")){$this->error = false; $this->log .= "<li> Error Create Table '.$row.' </li>";}'. PHP_EOL;
			}
			$db->close();			
		}
		$db->open();
		$this->list_creat_tables  .= $content;
		return true;
	}

	//Function Get all task for Modul
	Private function get_workflow_modul()
	{
		global $db;
		$sql = "SELECT sys_workflow.*
		FROM sys_workflow
		WHERE  sys_workflow.modul_id = ".$this->id_modul;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if (!$db->RowCount()) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$content = '';
				$workflow_array = $db->RecordsArray();
				

				foreach ($workflow_array as $row) { 
					
					$modul_id     = MySQL::SQLValue($this->id_modul);
					$id_wf        = MySQL::SQLValue($row['id']);
					$descrip       = MySQL::SQLValue($row['descrip']);
					$etat_line    = MySQL::SQLValue($row['etat_line']);
					$code         = MySQL::SQLValue($row['code']);					
					$color        = MySQL::SQLValue($row['color']);
					$etat_desc    = MySQL::SQLValue($row['etat_desc']);
					$message_etat = MySQL::SQLValue($row['message_etat']);
					$creusr       = MySQL::SQLValue(session::get('userid'));
					

					$content  .= '  //Workflow '.$id_wf.' '.$descrip.PHP_EOL;
					$content  .= '  if(!$result_task_'.$row['id'].' = $db->Query("INSERT INTO sys_workflow (descrip, modul_id, etat_line, code, color, etat_desc, message_etat, creusr)VALUES('.$descrip.', $result_insert_modul, ,'.$etat_line.', '.$code.', '.$color.', '.$etat_desc.', '.$message_etat.', '.$creusr.')")){$this->error = false; $this->log .= "<li> Error Import task '.$descrip.' </li>";}'. PHP_EOL;
							
				}

				$this->list_workflow_of_modul = $content;
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

	//Function Get all task for Modul
	Private function get_tasks_modul()
	{
		global $db;

		$sql = "SELECT sys_task.*
		FROM sys_task
		WHERE  sys_task.modul = ".$this->id_modul;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			} else {
				$content = '';
				$tasks_array = $db->RecordsArray();
				

				foreach ($tasks_array as $row) { 
					$id_app    = MySQL::SQLValue($row['id']);
					$app       = MySQL::SQLValue($row['app']);
					$modul     = MySQL::SQLValue($this->id_modul);
					$file      = MySQL::SQLValue($row['file']);
					$rep       = MySQL::SQLValue($row['rep']);
					$session   = MySQL::SQLValue($row['session']);
					$dscrip    = MySQL::SQLValue($row['dscrip']);
					$sbclass   = MySQL::SQLValue($row['sbclass']);
					$ajax      = MySQL::SQLValue($row['ajax']);
					$app_sys   = MySQL::SQLValue($row['app_sys']);
					$etat      = MySQL::SQLValue($row['etat']);
					$type_view = MySQL::SQLValue($row['type_view']);
					$services  = MySQL::SQLValue($row['services']);



					$content  .= '  //Task '.$app.' '.$dscrip.PHP_EOL;
					$content  .= '  if(!$result_task_'.$row['id'].' = $db->Query("INSERT INTO sys_task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('.$app.', $result_insert_modul, '.$file.','.$rep.', '.$session.', '.$dscrip.', '.$sbclass.', '.$ajax.', '.$app_sys.', '.$etat.','.$type_view.', '.$services.')")){$this->error = false; $this->log .= "<li> Error Import task '.$dscrip.' </li>";}'. PHP_EOL;

					if($this->list_action_task($row['id'])){
						$content  .= $this->list_action_of_task;
					}
					
				}

				$this->list_task_of_modul = $content;
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

	//Get Actions of each Task and push it into content
	private function list_action_task($task_id)
	{
		global $db;

		$sql = "SELECT sys_task_action.*
		FROM sys_task_action
		WHERE  sys_task_action.appid = ".$task_id;
		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
			
		}else{
			if ($db->RowCount() == 0) {
				$this->error = false;
				$this->log .= 'Aucun enregistrement trouvé ';
			}else{
				$content = '';
				$actions_array = $db->RecordsArray();

				foreach ($actions_array as $row){
					$action_id             = $row['id'];
					$appid                 = $row['appid'];
					$idf                   = MySQL::SQLValue($row['idf']);
					$descrip               = MySQL::SQLValue($row['descrip']);
					$app                   = MySQL::SQLValue($row['app']);
					$mode_exec             = MySQL::SQLValue($row['mode_exec']);
					$code                  = MySQL::SQLValue($row['code']);
					$code                  = $code == NULL ? '' : '".'.$code.'."';
					$type                  = MySQL::SQLValue($row['type']);
					$service               = MySQL::SQLValue($row['service']);
					$etat_line             = MySQL::SQLValue($row['etat_line']);
					$notif                 = MySQL::SQLValue($row['notif']);
					$etat_desc             = MySQL::SQLValue($row['etat_desc']);
					$message_etat          = MySQL::SQLValue($row['message_etat']);
					$message_etat          = $message_etat == NULL ? ' ' : '".'.$message_etat.'."';
					$message_class         = MySQL::SQLValue($row['message_class']);

					

					$content  .= '      // Action Task '.$appid.' - '.$descrip.PHP_EOL;
					$content  .= '      if(!$result_action_'.$action_id.' = $db->Query("INSERT INTO sys_task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_'.$task_id.', '.$idf.', '.$descrip.','.$app.', '.$mode_exec.', \''.$code.'\', '.$type.', '.$service.', '.$etat_line.', '.$notif.', '.$etat_desc.','.$message_class.',\''.$message_etat.'\')")){$this->error = false; $this->log .= "<li> Error Import task_action '.$descrip.' </li>";}'. PHP_EOL; 
				}
				
				$this->list_action_of_task = null;
				$this->list_action_of_task = $content;
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

	public function import_modul()
	{
		global $db;

		$modules_array = scandir(MPATH_EXPORT_MOD);
		$terminison_file = '_script_export.php';


		foreach ($modules_array as $row){
			if(strstr($row, $terminison_file))
			{
				$modul_name = str_replace($terminison_file,null,$row);
				$result = $db->QuerySingleValue0("SELECT modul FROM modul 
			    WHERE modul = ". MySQL::SQLValue($modul_name));
			    
		        if ($result == "0") 
		        {
		        	$file = MPATH_EXPORT_MOD.$modul_name.$terminison_file;
		        	if(!file_exists($file))
		        	{
		        		$this->log .= '<li>'.$modul_name.$terminison_file .' N\'existe pas '.'</li>';
		        		$this->error = false;
		        	}else{
		        		include($file);
		        		$this->log .= '<li>'.$modul_name.$terminison_file.'</li>';
		        	}
		        	
		        }  

			}
		}
		

		//return Bol reading $this->error
		if($this->error == false)
		{
			return false;
		}else{
			return true;
		}
	}
}

?>