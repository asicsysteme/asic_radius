<?php

class Template {


  static public $template;
  var $left_menu_arr      = array();//Menu Left returned Array
  var $moduls_setting     = array(); //List modules Setting
  var $sub_modul          = array();// sub modul;
  var $modul_have_setting = array();

  static public function load()
  {
   	//Define Theme depend to session
    define('THEME_PATH',MPATH_THEMES.Mcfg::get('theme'));
    
    $ajax  = MReq::tg('ajax') == 1 ? 1 : 0; 
    if($ajax == 1){
       //Excute app on ajax
     $execute_app = new MAjax();
     $execute_app->load();  

   }else{
      //Excute app on theme
    $theme_path = THEME_PATH;
    if(session::get('userid') == FALSE){
      $theme = $theme_path.'/mainns.php';
    }else{

      $salt = MD5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].session::get('ssid'));

      if(session::get('secur_ss') != $salt)
        {
          //exit('No way! you are been detected ');
          $new_logout = new  MLogin();
          $new_logout->token = session::get('ssid');
          if($new_logout->logout())
          {
            header('location:./');
          }else{
            MInit::msg_cor($new_logout->log, $err = "", $return = "");

          }
        //exit('No way! you are been detected ');
        //We need add loginig here

        }
        $theme = $theme_path.'/main.php';
      } 
      include ($theme);
    }
  }

  public function left_menu_render()
  {
    global $db;

      //Get user ID 
    $user = session::get('userid'); 
    
   
      //Format Query to get modul list
    $sql_modul = "SELECT sys_modules.modul AS modul, sys_modules.description AS descrip ,
    sys_modules.app_modul AS app , sys_task.sbclass AS class, sys_modules.modul_setting as parent
    FROM sys_rules, sys_task, sys_modules, sys_users
    WHERE (sys_rules.userid = sys_users.id) AND (sys_rules.appid = sys_task.id)
    AND  sys_task.app = sys_modules.app_modul AND  (sys_users.id = $user OR  $user = 1)
    AND sys_modules.is_setting <> 1 AND sys_modules.etat = 1
    GROUP BY CASE WHEN parent IS NOT NULL THEN parent ELSE sys_modules.modul END ORDER BY   sys_modules.id  "; 
    
    if(!$db->Query($sql_modul) or !$db->RowCount())
    {
      return false;
    }else{
      $this->left_menu_arr = $db->RecordsArray();
      return true;
    } 
  }
   public function get_modul_parent($modul)
   {
    global $db;
  
      //Format Query to get modul list
    $sql_parent_modul = "SELECT  sys_modules.modul AS modul , sys_modules.description AS descrip ,
    sys_modules.app_modul AS app , sys_task.sbclass AS class
    FROM sys_task, sys_modules
    WHERE 
    sys_task.app = sys_modules.app_modul AND  sys_modules.modul = '$modul' AND sys_modules.is_setting = 0 "; 
//exit($sql_sub_modul);
    if(!$db->Query($sql_parent_modul) or !$db->RowCount())
    {
      return false;
    }else{
      
        $parent_modul         = $db->RecordsArray();
        return $parent_modul;
      

   }
 }
  public function get_sub_modul($modul)
  {
    global $db;
    $render_sub_modul = NULL;
      //Get user ID 
    $user = session::get('userid');
      //Format Query to get modul list
    $sql_sub_modul = "SELECT  sys_modules.modul AS modul , sys_modules.description AS descrip ,
    sys_modules.app_modul AS app , sys_task.sbclass AS class
    FROM sys_rules, sys_task, sys_modules, sys_users
    WHERE (sys_rules.userid = sys_users.id) AND (sys_rules.appid = sys_task.id)
    AND (sys_users.id = $user OR $user = 1)
    AND  sys_task.app = sys_modules.app_modul AND  sys_modules.modul_setting = '$modul' AND sys_modules.is_setting = 2

    GROUP BY  sys_modules.app_modul ORDER BY   sys_modules.id  "; 
//exit($sql_sub_modul);
    if(!$db->Query($sql_sub_modul))
    {
      
      return false;
    }else{
      if($db->RowCount()){
        $render_sub_modul .= '<ul class="submenu">';
        $sub_modul         = $db->RecordsArray();

        $render_sub_modul .= '';
        foreach ($sub_modul as $row_s)
        {
          $render_sub_modul .= '<li left_menu="1" id="'.md5($row_s['descrip']).'">
          <a href="#" class="this_url" rel="'.$row_s['app'].'" title="'.$row_s['descrip'].'">
          <i class="menu-icon fa fa-'.$row_s['class'].'"></i>'.$row_s['descrip'].'
          </a>
          <b class="arrow">
          </b></li>';
        }
        $render_sub_modul .= '</ul>';

      }

    }
    return $render_sub_modul;

  }
  
  public function list_modul_have_setting()
  {
    global $db;
    
      //Get user ID 
    $user = session::get('userid'); 
      //Format Query to get modul list
    $sql_modul = "SELECT  sys_modules.id, sys_modules.modul AS modul , sys_modules.description AS descrip ,
    sys_modules.app_modul AS app , sys_task.sbclass AS class
    FROM sys_rules, sys_task, sys_modules, sys_users
    WHERE (sys_rules.userid = sys_users.id) AND (sys_rules.appid = sys_task.id)
    AND  sys_task.app = sys_modules.app_modul AND  sys_users.id = $user 
    AND sys_modules.is_setting = 0
    AND  sys_modules.modul IN (SELECT sys_modules.modul_setting FROM sys_modules)
    GROUP BY  sys_modules.app_modul ORDER BY   sys_modules.id  "; 
   
    if(!$db->Query($sql_modul))
    {
      $db->kill($db->Error());
      return false;
    }else{
      if($db->RowCount()){
        $this->modul_have_setting = $db->RecordsArray();
        return true;
      }else{
        return false;
      }

    } 
  }

  public function list_modul_setting($modul_base)
  {
    global $db;

      //Get user ID 
    $user = session::get('userid'); 
      //Format Query to get modul list
    $sql_modul = "SELECT  sys_modules.modul AS modul , sys_modules.description AS descrip ,
    sys_modules.app_modul AS app , sys_task.sbclass AS class
    FROM sys_rules, sys_task, sys_modules, sys_users
    WHERE (sys_rules.userid = sys_users.id) AND (sys_rules.appid = sys_task.id)
    AND  sys_task.app = sys_modules.app_modul AND  sys_users.id = $user 
    AND sys_modules.is_setting = 1
    AND  sys_modules.modul_setting = '$modul_base'
    GROUP BY  sys_modules.app_modul ORDER BY   sys_modules.id  "; 

    if(!$db->Query($sql_modul))
    {
      $db->kill($db->Error());
      return false;
    }else{
      if($db->RowCount()){
        $this->moduls_setting = $db->RecordsArray();
        return true;
      }else{
        return false;
      }


      }# code...
    }


  }


  ?>
