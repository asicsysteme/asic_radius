<?php
class view {

	public $view;
	var $output = null;
//recuperer repertoir et nom de fichier à charger 

	static public function load($view_rep,$view_file)
	{
		$view_file_include = MPATH_MODULES.$view_rep.SLASH.'view/'.$view_file.'_v.php';
		if(file_exists($view_file_include))
		{
			include_once($view_file_include);
		}else{
			/*exit ('<div class="space-16"></div><div class="space-16"></div><div class="alert alert-block alert-danger"><i class="ace-icon fa fa-exclamation-circle red fa-2x icon-animated-vertical"></i> <strong class="red"> STOP: </strong>Le fichier View n\'exist pas ! contacter l\'administrateur sysème<br><a href="./" class="btn btn-danger btn-sm"><i class="ace-icon fa fa-reply icon-only"> Accueil</i></a></div>');*/
			exit('3#Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
	}
	static public function load_view($view_file)
	{
		$view_file_include = APP_VIEW.$view_file.'_v.php';
		if(file_exists($view_file_include))
		{
			include_once($view_file_include);
		}else{
			exit('3#Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
	}

	static public function load_from_template($view_file)
	{
		$view_file_include = MPATH_THEMES.MCfg::get('theme').'/'.$view_file.'.php';
		
		if(file_exists($view_file_include))
		{
			include_once($view_file_include);
		}else{
			exit('3#Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
	}

	/**
	 * [tab Index used on view profile]
	 * @param  [string] $app     [App for ste _tsk]
	 * @param  [text] $text    [Text of Button]
	 * @param  [url setting] $add_set [Parameteres to be add to url]
	 * @param  [int] $exec    [set to 1 is we want use ]
	 * @param  [string] $icon    [icon]
	 * @return [Html]          [render html or null]
	 */
	static public function tab_render($app = null, $text=NULL, $add_set=NULL, $icon = NULL, $active = false, $id)
	{
		if($app == null){
			$permission = 1;
		}else{
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

		}
		

		$active = $active == true ? 'active' : null;
		$icon_class = $icon == NULL ? 'plus' : $icon;
		$tab_index = $permission == "0" ? NULL : $tab_index = '<li class="'.$active.'">
						<a data-toggle="tab" href="#'.$id.'">
							<i class="blue ace-icon fa fa-'.$icon.' bigger-120"></i>
							'.$text.'
						</a>
					</li>';
        $tab_content_s = $permission == "0" ? NULL :'<div id="'.$id.'" class="tab-pane in '.$active.'">
						<div class="col-xs-12 col-sm-4"></div>
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<!-- #section:pages/invoice -->';
								

		$tab_content_e = $permission == "0" ? NULL :'<!-- /section:pages/invoice -->
							</div>
						</div><!-- /.row -->						
					</div>';
        $tab_true = $permission == "0" ? false : true;
		
        $output = array('tab_index' => $tab_index, 'tcs' => $tab_content_s, 'tce' => $tab_content_e, 'tb_rl' => $tab_true);
		return $output ;

	}

	static public function view_get_content($view_file)
	{
		$view_file_include = APP_VIEW.$view_file.'_v.php';
		ob_start();
		if(file_exists($view_file_include))
		{
			include($view_file_include);
			$contents = ob_get_contents();
		}else{
			$contents = ('Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
		       
        ob_end_clean();
        return $contents;


	}


	static public function load_content_from_template($view_file, $data = Null)
	{
		$view_file_include = MPATH_THEMES.MCfg::get('theme').'/'.$view_file.'.php';
		
		ob_start();
		if(file_exists($view_file_include))
		{
			include($view_file_include);
			$contents = ob_get_contents();
			$contents = str_replace("%data_error%", $data, $contents);
		}else{
			$contents = ('Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
		       
        ob_end_clean();
        return print($contents);
	}


}


?>
