<?php
/**
 * Creat files of modul
 */
class Mmodul_creat_files extends Mmodul
{
	
	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [creat_task_files create All file for new task]
	 * @param  [string regex] $modul_name [description]
	 * @param  [string regex] $task_name  [description]
	 * @return [bol]             [description]
	 */
	public function creat_task_files($modul_rep, $task_name, $modul_name, $type_view, $app_base, $table)
	{
		

		$modul_path = MPATH_MODULES.$modul_rep;
		//exit("2#".$modul_path);

		if($modul_rep == null)
		{
			$this->error = false;
			$this->log .='</br>Unable get Module Path'; 
			return false;

		}

		
		$file_c = $modul_path.'/controller/'.$task_name.'_c.php';
		$file_list_c = $modul_path.'/controller/list'.$task_name.'_c.php';
		$file_action_c = $modul_path.'/controller/action'.$task_name.'_c.php';
		$file_m = $modul_path.'/model/'.$modul_name.'_m.php';
		$file_v = $modul_path.'/view/'.$task_name.'_v.php';

		$templat_folder = MPATH_LIBRARIES.'templates_script/';
		$template = null;
		$content   = '<?php '. PHP_EOL .'//First check target no Hack'.PHP_EOL."if(!defined('_MEXEC'))die();".PHP_EOL.'//'.MCfg::get('sys_titre'). PHP_EOL .'// Modul: '.$modul_name.PHP_EOL.'//Created : '.date('d-m-Y'). PHP_EOL.'//';

		

		//Main task of modul (list)
        if($app_base == true && $type_view == 'list')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control.php')){
				$template_c = file_get_contents($templat_folder.'template_control.php');
				$template = str_replace('%task%', $task_name, $template_c);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//List
			$template = null;
			if(file_exists($templat_folder.'template_list.php')){
				$template_c = file_get_contents($templat_folder.'template_list.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_list_c) && !file_put_contents($file_list_c, $content.'Controller Liste'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Controller Liste'; 
				return false;
			}
			//Action
			$template = null;
			if(file_exists($templat_folder.'template_action.php')){
				$template_c = file_get_contents($templat_folder.'template_action.php');
				$template = str_replace('%model%', $modul_name, $template_c);
				$template = str_replace('%table%', $table, $template);
			}
			if(!file_exists($file_action_c) && !file_put_contents($file_action_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view.php')){
				$template_c = file_get_contents($templat_folder.'template_view.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
			//Model
			$template = null;
			if(file_exists($templat_folder.'template_model.php')){
				$template_c = file_get_contents($templat_folder.'template_model.php');
				$template = str_replace('%model%', $modul_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_modul%', $this->lines_modul, $template);
				
			}
			if(!file_exists($file_m) && !file_put_contents($file_m, $content.'Model'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Model'; 
				return false;
			}
		}
		//Task list no main
        if($app_base == false && $type_view == 'list')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control.php')){
				$template_c = file_get_contents($templat_folder.'template_control.php');
				$template = str_replace('%task%', $task_name, $template_c);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//List
			$template = null;
			if(file_exists($templat_folder.'template_list.php')){
				$template_c = file_get_contents($templat_folder.'template_list.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_list_c) && !file_put_contents($file_list_c, $content.'Controller Liste'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat Controller Liste'; 
				return false;
			}
			//Action
			$template = null;
			if(file_exists($templat_folder.'template_action.php')){
				$template_c = file_get_contents($templat_folder.'template_action.php');
				$template = str_replace('%model%', $modul_name, $template_c);
				$template = str_replace('%table%', $table, $template);
			}
			if(!file_exists($file_action_c) && !file_put_contents($file_action_c, $content.'Controller'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view.php')){
				$template_c = file_get_contents($templat_folder.'template_view.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%table%', $table, $template);
				$template = str_replace('%lines_select%', $this->lines_select, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}
		//Task form add
		if($app_base == false && $type_view == 'formadd')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_formadd.php')){
				$template_c = file_get_contents($templat_folder.'template_control_formadd.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				$template = str_replace('%lines_action%', $this->lines_action, $template);
				$template = str_replace('%lines_action_check%', $this->lines_action_check, $template);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller ADD Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_formadd.php')){
				$template_c = file_get_contents($templat_folder.'template_view_formadd.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				$template = str_replace('%list_input_add%', $this->lines_form_add, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}

		//Task form Edit
		if($app_base == false && $type_view == 'formedit')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_formedit.php')){
				$template_c = file_get_contents($templat_folder.'template_control_formedit.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				$template = str_replace('%lines_action%', $this->lines_action, $template);
				$template = str_replace('%lines_action_check%', $this->lines_action_check, $template);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller EDIT Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_formedit.php')){
				$template_c = file_get_contents($templat_folder.'template_view_formedit.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%list_input_edit%', $this->lines_form_edit, $template);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}

		//Task form Costumized
		if($app_base == false && $type_view == 'formpers')
		{
			
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_formpers.php')){
				$template_c = file_get_contents($templat_folder.'template_control_formpers.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
				
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller EDIT Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_formpers.php')){
				$template_c = file_get_contents($templat_folder.'template_view_formpers.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}

		//Task Exec
		if($app_base == false && $type_view == 'exec')
		{
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_exec.php')){
				$template_c = file_get_contents($templat_folder.'template_exec.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller EXEC Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			
		}

		//Task Profile
		if($app_base == false && $type_view == 'profil')
		{
			if($table != null)
			{
				$this->get_table_fields($table);
			}
			//Controller
			$template = null;
			if(file_exists($templat_folder.'template_control_profil.php')){
				$template_c = file_get_contents($templat_folder.'template_control_profil.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%modul%', $modul_name, $template);

				
			}
			if(!file_exists($file_c) && !file_put_contents($file_c, $content.'Controller PROFILE Form'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{

				$this->error = false;
				$this->log .='</br>Unable Creat Controller '; 
				return false;
			}
			//view
			$template = null;
			if(file_exists($templat_folder.'template_view_profil.php')){
				$template_c = file_get_contents($templat_folder.'template_view_profil.php');
				$template = str_replace('%task%', $task_name, $template_c);
				$template = str_replace('%list_profil%', $this->lines_profil, $template);
				$template = str_replace('%modul%', $modul_name, $template);
			}
			if(!file_exists($file_v) && !file_put_contents($file_v, $content.'View'.PHP_EOL.$template, FILE_APPEND | LOCK_EX))
			{
				$this->error = false;
				$this->log .='</br>Unable Creat View'; 
				return false;
			}
		}
		return true;



	}

}