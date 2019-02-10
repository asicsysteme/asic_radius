<?php

/**
* Class generate tools for a table
*/
class TableTools 
{
	var $app_action; //Array action for each row
	var $line_data; //Array info for each row
	var $log = null; //Sitring log errors
	var $any_etat = false;//Set to true to ignore etat for line
	/*function __construct(argument)
	{
		# code...
	}*/

	/**
	 * [btn_add Add Btn to an table ]
	 * @param  [string] $app     [App for ste _tsk]
	 * @param  [text] $text    [Text of Button]
	 * @param  [url setting] $add_set [Parameteres to be add to url]
	 * @param  [int] $exec    [set to 1 is we want use ]
	 * @param  [string] $icon    [icon]
	 * @return [Html]          [render html or null]
	 */
	static public function btn_add($app, $text=NULL, $add_set=NULL, $exec = NULL, $icon = NULL){
		global $db;
		$userid = session::get('userid');
		$sql = "SELECT
		1
		FROM
		`sys_rules`
		INNER JOIN `sys_task` 
		ON (`sys_rules`.`appid` = `sys_task`.`id`)
		INNER JOIN `sys_task_action` 
		ON (`sys_task_action`.`appid` = `sys_task`.`id`) AND (`sys_rules`.`action_id` = `sys_task_action`.`id`)
		INNER JOIN `sys_users` 
		ON (`sys_rules`.`userid` = `sys_users`.`id`)
		WHERE (sys_users.id = $userid OR $userid = 1)  

		AND sys_task.app =  ".MySQL::SQLValue($app)." ";

		$permission = $db->QuerySingleValue0($sql);

		$exec_class = $exec == NULL ? 'this_url' : 'this_exec';
		$icon_class = $icon == NULL ? 'plus' : $icon;

		$output = $permission == "0"?"":'<a href="#" rel="'.$app.'&'.$add_set.'" class=" btn btn-white btn-info btn-bold '.$exec_class.' spaced"><span><i class="fa fa-'.$icon_class.'"></i> '.$text.'</span></a>';

		$render = print ($output);


		return $render ;

	}
    /**
     * [btn_back description]
     * @param  [type] $app     [description]
     * @param  [type] $text    [description]
     * @param  [type] $id      [description]
     * @param  [type] $add_set [description]
     * @return [type]          [description]
     */
	static public function btn_back($app, $text=NULL, $id, $add_set=NULL){
		global $db;
		$userid = session::get('userid');
		$sql = "SELECT
		1
		FROM
		`sys_rules`
		INNER JOIN `sys_task` 
		ON (`sys_rules`.`appid` = `sys_task`.`id`)
		INNER JOIN `sys_task_action` 
		ON (`sys_task_action`.`appid` = `sys_task`.`id`) AND (`sys_rules`.`action_id` = `sys_task_action`.`id`)
		INNER JOIN `sys_users` 
		ON (`sys_rules`.`userid` = `sys_users`.`id`)
		WHERE (sys_users.id = $userid OR $userid = 1) 

		AND sys_task.app =  ".MySQL::SQLValue($app)." ";

		$id_format = '&id='.$id.'&idc='.md5(Minit::cryptage($id,1));

		$permission = $db->QuerySingleValue0($sql);
		$output = $permission == "0"?"":'<a href="#" rel="'.$app.$id_format.$add_set.'" class="ColVis_Button ColVis_MasterButton btn btn-red btn-info btn-bold this_url"><span><i class="fa fa-reply"></i> '.$text.'</span></a>';

		$render = print ($output);


		return $render ;

	}
    static public function btn_action($task, $id, $go_to)
	{
		//$output = '<button id="btn_action" href="#" rel="'.$task.'" data="'.MInit::crypt_tp('id', $id).'" class=" btn btn-white btn-info btn-bold dropdown-toggle  spaced" data-toggle="dropdown"><span><i class="ace-icon fa fa-bars smaller-90"></i> Actions </span></a>';
		$output = '<div id="btn_action" class="btn-group" rel="'.$task.'" data_id="'.$id.'" data="'.MInit::crypt_tp('id', $id).'" go_to="'.$go_to.'"><button  data-toggle="dropdown" class="btn btn-white btn-info btn-bold dropdown-toggle  spaced"><span><i class="ace-icon fa fa-bars smaller-90"></i> Actions </span></button></div>';


		$render = print ($output);


		return $render ;
	}
    /**
     * [btn_csv description]
     * @param  [type] $app  [description]
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
	static public function btn_csv($app)
	{
		$output = '<a title="Export XLS" rel="'.$app.'" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_csv"><span><i class="fa fa-file-excel-o fa-lg" style="color:green"></i></span></a>';


		$render = print ($output);


		return $render ;
	}


	/**
	 * [btn_pdf description]
	 * @param  [type] $app  [description]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	static public function btn_pdf($app, $text)
	{
		$output = '<a title="Export PDF" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_pdf"><span><i class="fa fa-file-pdf-o fa-lg" style="color:red"></i></span></a>';


		$render = print ($output);


		return $render ;
	}
	static public function btn_map($app, $text)
	{
		$output = '<a title="Export MAP" href="#"  class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold export_map"><span><i class="fa fa-map-o fa-lg" style="color:red"></i></span></a>';


		$render = print ($output);


		return $render ;
	}

	//Publique Function get action for user modul
    //depent of user connected
	public function action_line_table($app, $table_modul, $cre_usr = null, $task_exec = null, $etat_archive = 2)
	{
		//Etat line is null return false
		if($this->line_data['id'] == null)
		{
			$this->app_action .= 'Pas d\'Enregistrement trouvé';
			print($this->app_action);
			return false;
		}
		//Etat line is not 

		global $db;
		$user = session::get('userid');


		$table_from = $table_modul == 'sys_task' ? NULL : ', '.$table_modul;
		$and_sys_task = $table_modul == 'sys_task' ? NULL : ' AND sys_task.app ="'.$app.'"';


		$etat          = $this->line_data['etat'];
		$id_this_modul = APP_ID;
		$id            = $this->line_data['id'];
		$service       = session::get('service');
		$service_f     = '%-'.$service.'-%';

		$sql = "SELECT sys_task_action.code FROM 
		sys_task_action, sys_task, sys_rules $table_from 
		WHERE sys_rules.action_id = sys_task_action.id 
		AND $table_modul.etat = sys_task_action.etat_line 
		$and_sys_task 
		AND sys_task.id = sys_task_action.appid 
		AND (sys_rules.userid = $user OR $user = 1)
		AND sys_rules.service = $service
		AND $table_modul.etat = $etat 
		AND $table_modul.id = $id
		AND sys_task_action.type = 0 
		GROUP BY sys_task_action.id ";
        //for more secure add this AND sys_task_action.service LIKE  '$service_f' 
        //exit($sql);


		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
			//return false;
		}else{
			
			if (!$db->RowCount()) {
				$this->error = false;
				$this->app_action .= 'Pas d\'action trouvée!';
				return print($this->app_action);
			} else {
				//$this->log = $sql;
				//$this->app_action = $db->RowArray();
				
				$this->error = true;
				while (!$db->EndOfSeek())
				{
					$row = $db->Row();
					$this->app_action .= $row->code;

				}
				//Get array form setting for this modul
				
				$stanadr_archive = null;
				//check if current line have the max etat_line then show archive action.
				//var_dump($etat.' '.$etat_archive);
				if($this->check_rule_archive('archive'.$app) && is_array($etat_archive)  && in_array($etat, $etat_archive))
				{
					$stanadr_archive = '<li><a href="#" class="this_exec" data="'.MInit::crypt_tp('id',$id).'" rel="archive'.$app.'"  ><i class="ace-icon fa fa-archive bigger-100"></i> Archiver ligne</a></li>';
				}
				
				$stanadr_delete = null;
				if($etat == 0 AND ($cre_usr == session::get('userid') OR session::get('service') == 1) AND $task_exec != null)
				{
						$stanadr_delete = '<li class="divider"></li><li><a href="#" class="this_exec" data="'.MInit::crypt_tp('id',$id).'" rel="'.$task_exec.'"  ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer ligne</a></li>';
				}

					$retour =  str_replace('%id%', MInit::crypt_tp('id',$id), $this->app_action);
					return print($retour.$stanadr_delete.$stanadr_archive);

			}


		}
		//return true;
	}
	private function check_rule_archive($app)
	{
		global $db;
		$user = session::get('userid');
		$sql = "SELECT
		1
		FROM
		`sys_rules`
		INNER JOIN `sys_task` 
		ON (`sys_rules`.`appid` = `sys_task`.`id`)
		INNER JOIN `sys_users` 
		ON (`sys_rules`.`userid` = `sys_users`.`id`)
		WHERE sys_users.id = ".$user." 

		AND sys_task.app =  ".MySQL::SQLValue($app)." ";
		if($db->QuerySingleValue0($sql) == '0')
		{
			return false;

		}else{
			return true;
		}

	}

	//Publique Function get action for user modul
    //depent of user connected
	public function action_profil_view($app, $table_modul, $add_set, $cre_usr = null, $task_exec = null)
	{
		//Etat line is null return false
		if($this->line_data['id'] == null)
		{
			$this->app_action .= 'Pas d\'Enregistrement trouvé';
			print($this->app_action);
			return false;
		}
		//Etat line is not 

		global $db;
		$user = session::get('userid');


		$table_from = $table_modul == 'sys_task' ? NULL : ', '.$table_modul;
		$and_task = $table_modul == 'sys_task' ? NULL : ' AND sys_task.app ="'.$app.'"';


		$etat          = $this->line_data['etat'];
		$id_this_modul = APP_ID;
		$id            = $this->line_data['id'];
		$service       = session::get('service');
		$service_f     = '%-'.$service.'-%';
		

		$sql = "SELECT sys_task_action.app, sys_task_action.descrip,
		 sys_task_action.mode_exec, sys_task_action.etat_desc ,
		 sys_task_action.message_class, sys_task_action.class
		  FROM 
		sys_task_action, sys_task, sys_rules $table_from 
		WHERE sys_rules.action_id = sys_task_action.id 
		AND $table_modul.etat = sys_task_action.etat_line 
		$and_task 
		AND sys_task.id = sys_task_action.appid 
		AND (sys_rules.userid = $user OR $user = 1) 
		AND sys_task_action.service LIKE  '$service_f'
		AND sys_rules.service = $service
		AND $table_modul.etat = $etat 
		AND $table_modul.id = $id
		AND sys_task_action.type = 0 ";

//exit($sql);


		if(!$db->Query($sql))
		{
			$this->error = false;
			$this->log  .= $db->Error();
			//return false;
		}else{
			
			if ($db->RowCount() == false) {
				$this->error = false;
				$this->app_action .= 'Pas d\'action trouvée!';
				return print($this->app_action);
			} else {
				//$this->log = $sql;
				//$this->app_action = $db->RowArray();
				
				$this->error = true;
				while (!$db->EndOfSeek())
				{
					$row = $db->Row();
					$this->app_action .= '<a href="#" rel="'.$row->app.'&'.$add_set.'" class=" btn btn-white '.$row->mode_exec.' btn-info btn-bold '.$row->message_class.' spaced"><span><i class="fa fa-'.$row->class.'"></i> '.$row->descrip.'</span></a>';

				}
				$stanadr_delete = null;
				if($etat == 0 AND ($cre_usr == session::get('userid') OR session::get('service') == 1) AND $task_exec != null)
				{
						$stanadr_delete = '<a href="#" rel="'.$task_exec.'&'.MInit::crypt_tp('id',$id).'" class=" btn btn-white this_exec btn-info btn-bold '.$row->message_class.' spaced"><span><i class="fa fa-trash red bigger-100"></i> Supprimer ligne</span></a>';
				}

					$retour =  str_replace('%id%', MInit::crypt_tp('id',$id), $this->app_action);
					return ($retour.$stanadr_delete);

			}


		}
		//return true;
	}

    /**
     * Line notification for notify user that line have an action should be execute
     * @param  [string] $table     [man table for this liste]
     * @param  [String] $task_name [task name calling this liste]
     * @return [String] [return Sql code to concate with any culomn (input hidden or null)]
     */
    static public function line_notif($table, $task_name)
    {
    	$get_notif = "CASE 1
    	WHEN (SELECT 
    		COUNT(sys_task_action.notif) 
    		FROM
    		sys_task_action, sys_rules , sys_task
    		WHERE sys_task_action.`etat_line` = `$table`.etat
    		AND sys_task_action.appid = sys_task.id 
    		AND sys_task_action.`notif` = 1 
    		AND sys_task.`app` = '$task_name'  
    		AND sys_task_action.id = sys_rules.`action_id`
    		AND (sys_rules.`userid` = ".session::get('userid')." OR ".session::get('userid')." = 1)   
    		AND sys_task_action.`type` = 0) > 0
    		THEN '<input type=hidden value=isnotif>'
    		ELSE ' ' END";
    	return $get_notif;
    }
    /**
     * [line_notif_new description]
     * @param  [type] $table     [description]
     * @param  [type] $task_name [description]
     * @return [type]            [description]
     */   
    static public function line_notif_new($table, $task_name)
    {

        //Check if for export result then select colonne adequat
    	if(Mreq::tp('export') == 1)
    	{
    			$message_etat = "CASE
    			WHEN SUM(sys_task_action.`notif`) > 0 
    			THEN 
    			sys_workflow.`etat_desc`                            
    			ELSE CONCAT(sys_workflow.`etat_desc`,' ') 
    			END ";
    	}else{
    			$message_etat = " CASE
    			WHEN SUM(sys_task_action.`notif`) > 0 
    			THEN CONCAT(
    				sys_workflow.`message_etat`,
    				'<input type=hidden value=isnotif>'
    			) 
    			WHEN sys_workflow.`message_etat` IS NULL THEN 'No ETAT'
    			ELSE CONCAT(sys_workflow.`message_etat`) 
    			END ";
    	}
    	$get_notif = "(SELECT 
    	$message_etat 
    	FROM
    	sys_task_action,
    	sys_rules,
    	sys_task,
    	sys_workflow,
    	sys_modules 
    	WHERE sys_workflow.`etat_line` = `$table`.etat 
    	AND sys_task_action.etat_line = sys_workflow.`etat_line`
    	AND sys_task_action.appid = sys_task.id 
    	AND sys_workflow.modul_id = sys_modules.id 
    	AND sys_modules.id = sys_task.modul 
    	AND sys_task_action.etat_desc IS NOT NULL
    	AND sys_task.`app` = '$task_name' 
    	AND sys_task_action.id = sys_rules.`action_id` 
    	AND (sys_rules.`userid` = ".session::get('userid')." OR ".session::get('userid')." = 1)   
    	AND sys_task_action.`type` = 0) AS statut";
    	return $get_notif;
    }
    /**
     * [order_bloc description]
     * @param  [type] $order_column [description]
     * @return [type]               [description]
     */
    static public function order_bloc($order_column)
    {



    	if(Mreq::tp('export') == 1)
    	{
    		$order_notif = " CASE WHEN LOCATE('*', statut) = 0  THEN 0 ELSE 1 END DESC, ";
    	}else{
    		$order_notif = " CASE WHEN LOCATE('notif', statut) = 0  THEN 0 ELSE 1 END DESC, ";
    	}
    	return $order_notif;

    }

       
    /**
     * [where_etat_line description]
     * @param  [type] $table     [description]
     * @param  [type] $task_name [description]
     * @return [type]            [description]
     */
    static public function where_etat_line($table, $task_name)
    {
    	$etat = Cookie::Get($task_name."_grid_zip", null);

    	$wher_etat = $etat == null ? ' AND sys_task_action.`etat_line` <> 100 ' : ' AND sys_task_action.`etat_line` = 100 ';
    	

    	$where_etat_line = " WHERE   (SELECT 
    		COUNT(sys_task_action.id) 
    		FROM
    		sys_task_action, sys_rules , sys_task
    		WHERE sys_task_action.`etat_line` = `$table`.etat
    		$wher_etat
    		AND sys_task_action.appid = sys_task.id 
    		AND sys_task.`app` = '$task_name'  
    		AND sys_task_action.id = sys_rules.`action_id`
    		AND (sys_rules.`userid` = ".session::get('userid')." OR ".session::get('userid')." = 1)) > 0 " ;
    	return $where_etat_line; 
    }
    /**
     * [where_search_etat description]
     * @param  [type] $table     [description]
     * @param  [type] $task_name [description]
     * @param  [type] $search    [description]
     * @return [type]            [description]
     */
    static public function where_search_etat($table, $task_name, $search)
    {


    	$where_search_etat = " OR (SELECT 
    		COUNT(sys_task_action.id) 
    		FROM
    		sys_task_action,
    		sys_rules,
    		sys_task 
    		WHERE sys_task_action.`etat_line` = `$table`.etat 
    		AND sys_task_action.appid = sys_task.id 
    		AND sys_task_action.etat_desc IS NOT NULL 
    		AND sys_task.`app` = '$task_name' 
    		AND sys_task_action.id = sys_rules.`action_id` 
    		AND (sys_rules.`userid` = ".session::get('userid')." OR ".session::get('userid')." = 1)
    		AND sys_task_action.`type` = 0
    		AND sys_task_action.`message_etat` LIKE '%$search%')
    	)";
    	return $where_search_etat;                        
    }




}

?>